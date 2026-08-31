<?php

namespace App\Libraries;

use App\Models\GrievanceCaseModel;
use App\Models\MasterSiteModel;
use App\Models\MasterDepartmentModel;
use App\Models\MasterCaseTypeModel;
use App\Models\MasterMessageTypeModel;
use App\Models\MasterChannelModel;
use App\Models\MasterStatusModel;
use App\Models\MasterPriorityModel;
use Config\WovoImport as WovoImportConfig;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WovoImporter
{
    protected WovoImportConfig $config;

    protected GrievanceCaseModel $caseModel;
    protected MasterSiteModel $siteModel;
    protected MasterDepartmentModel $departmentModel;
    protected MasterCaseTypeModel $caseTypeModel;
    protected MasterMessageTypeModel $messageTypeModel;
    protected MasterChannelModel $channelModel;
    protected MasterStatusModel $statusModel;
    protected MasterPriorityModel $priorityModel;

    /** @var array<string,int> cache nama(lowercase) => id, per master table */
    protected array $cache = [];

    protected array $sitesLoaded = [];

    protected int $created = 0;
    protected int $skippedDuplicate = 0;
    protected int $skippedFiltered = 0;
    protected array $errors = []; // ['row' => int, 'reason' => string]

    public function __construct()
    {
        $this->config = config('WovoImport');

        $this->caseModel        = new GrievanceCaseModel();
        $this->siteModel        = new MasterSiteModel();
        $this->departmentModel  = new MasterDepartmentModel();
        $this->caseTypeModel    = new MasterCaseTypeModel();
        $this->messageTypeModel = new MasterMessageTypeModel();
        $this->channelModel     = new MasterChannelModel();
        $this->statusModel      = new MasterStatusModel();
        $this->priorityModel    = new MasterPriorityModel();

        $this->sitesLoaded = $this->siteModel->findAll();
    }

    public function run(string $filePath, ?int $forcedChannelId = null): array
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);



        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $highestRow  = $sheet->getHighestDataRow();

        $db = db_connect();
        $db->transStart();

        for ($row = 2; $row <= $highestRow; $row++) {

            $data = $this->readRow($sheet, $row);

            if ($data === null) {
                continue;
            }

            try {
                $this->importRow($data, $row, $forcedChannelId);
            } catch (\Throwable $e) {
                $this->errors[] = ['row' => $row, 'reason' => $e->getMessage()];
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Transaksi database gagal di-commit. Cek log aplikasi untuk detail.');
        }

        return [
            'created'           => $this->created,
            'skipped_duplicate' => $this->skippedDuplicate,
            'skipped_filtered'  => $this->skippedFiltered,
            'errors'            => $this->errors,
            'total_rows'        => $highestRow - 1,
        ];
    }

    protected function readRow($sheet, int $row): ?array
    {
        $get = fn($col) => trim((string) $sheet->getCell($col . $row)->getCalculatedValue());

        $caseId = $get('D'); // Case Id

        if ($caseId === '') {
            return null;
        }

        return [
            'case_id'         => $caseId,
            'created_date'    => $get('F'), // Case Created Date
            'classification'  => $get('G'), // Classification
            'case_type'       => $get('H'), // Case Type
            'case_status'     => $get('I'), // Case Status
            'resolve_date'    => $get('J'), // Case Resolve Date
            'messages'        => $get('K'), // Messages
            'agent'           => $get('M'), // Agent
            'responses'       => $get('N'), // Responses
            'initial_hours'   => $get('P'), // Initial Response Hours
            'satisfaction'    => $get('R'), // Case Satisfaction
            'channel'         => $get('T'), // Channel Used
            'gender'         => $get('U'), // Channel Used
            'department_raw'  => $get('Z'), // Department
        ];
    }

    protected function importRow(array $r, int $row, ?int $forcedChannelId = null): void
    {
        // ---------- Filter: skip case type tertentu (mis. Junk) ----------
        foreach ($this->config->skipCaseTypes as $skip) {
            if (strcasecmp($skip, $r['case_type']) === 0) {
                $this->skippedFiltered++;
                return;
            }
        }

        // ---------- Duplikat check ----------
        $existing = $this->caseModel
            ->where('source', 'wovo_import')
            ->where('external_id', $r['case_id'])
            ->first();

        if ($existing) {
            $this->skippedDuplicate++;
            return;
        }

        // ---------- Site (dari kolom Department = lokasi Garmen/Socks) ----------
        $siteId = $this->resolveSiteId($r['department_raw']);

        if ($siteId === null) {
            $this->errors[] = [
                'row'    => $row,
                'reason' => "Site tidak dapat ditentukan dari Department \"{$r['department_raw']}\". Pastikan master_sites punya nama yang mengandung kata kunci Garmen/Socks.",
            ];
            return;
        }

        // ---------- Case Type + Department penanggung jawab ----------
        [$caseTypeName, $departmentName] = $this->config->caseTypeMap[$r['case_type']]
            ?? [$r['case_type'] ?: 'Others', 'Others'];

        $caseTypeId = $this->findOrCreate($this->caseTypeModel, $caseTypeName);

        $departmentId = $this->findOnly($this->departmentModel, $departmentName);

        if ($departmentId === null) {
            $this->errors[] = [
                'row'    => $row,
                'reason' => "Department internal \"{$departmentName}\" belum ada di master_departments. Tambahkan dulu atau sesuaikan Config\\WovoImport::\$caseTypeMap.",
            ];
            return;
        }

        // ---------- Message Type ----------
        $messageTypeName = $this->config->classificationMap[strtolower($r['classification'])] ?? 'Ask';
        $messageTypeId   = $this->findOrCreate($this->messageTypeModel, $messageTypeName);

        // ---------- Channel ----------
        if ($forcedChannelId !== null) {
            $channelId = $forcedChannelId;
        } else {
            $channelName = $this->config->channelMap[strtolower($r['channel'])] ?? ($r['channel'] ?: 'WOVO App');
            $channelId   = $this->findOrCreate($this->channelModel, $channelName);
        }

        // ---------- Status ----------
        $statusName = $this->config->statusMap[strtolower($r['case_status'])] ?? $this->config->defaultStatus;
        $statusId   = $this->findOnly($this->statusModel, $statusName);

        if ($statusId === null) {
            $this->errors[] = ['row' => $row, 'reason' => "Status \"{$statusName}\" tidak ditemukan di master_statuses."];
            return;
        }

        // ---------- Priority (default, raw WOVO gak punya kolom priority) ----------
        $priorityId = $this->findOnly($this->priorityModel, $this->config->defaultPriority);

        if ($priorityId === null) {
            $this->errors[] = ['row' => $row, 'reason' => "Priority default \"{$this->config->defaultPriority}\" tidak ditemukan di master_priorities."];
            return;
        }

        // ---------- Tanggal ----------
        $receivedDate = $this->parseDate($r['created_date']);
        $closedDate   = $r['resolve_date'] ? $this->parseDate($r['resolve_date']) : null;

        if ($receivedDate === null) {
            $this->errors[] = ['row' => $row, 'reason' => "Case Created Date tidak valid: \"{$r['created_date']}\"."];
            return;
        }

        $responseDate = null;

        if (is_numeric($r['initial_hours']) && (float) $r['initial_hours'] >= 0) {
            $responseDate = date('Y-m-d', strtotime($receivedDate . ' +' . (int) ceil((float) $r['initial_hours'] / 24) . ' days'));
        }

        // ---------- Satisfaction / Rating ----------
        $satisfactionKey = strtolower($r['satisfaction']);
        $rating          = $this->config->satisfactionRatingMap[$satisfactionKey] ?? null;
        $satisfaction    = $r['satisfaction'] ?: null;

        // ---------- Pembersihan teks message/response ----------
        $message  = $this->cleanText($r['messages']);
        $response = $this->cleanText($r['responses']);

        // ---------- Insert ----------
        $data = [
            'case_number'           => $this->generateCaseNumberForDate($receivedDate),
            'source'                => 'wovo_import',
            'external_id'           => $r['case_id'],
            'gender'           => $r['gender'],
            'site_id'               => $siteId,
            'channel_id'            => $channelId,
            'message_type_id'       => $messageTypeId,
            'case_type_id'          => $caseTypeId,
            'department_id'         => $departmentId,
            'priority_id'           => $priorityId,
            'status_id'             => $statusId,
            'received_date'         => $receivedDate,
            'target_response_date'  => $receivedDate,
            'target_closure_date'   => $closedDate ?? $receivedDate,
            'response_date'         => $responseDate,
            'closed_date'           => $closedDate,
            'message'               => $message,
            'management_response'   => $response ?: null,
            'confidential'          => 'No',
            'repeated_case'         => 'No',
            'rating'                => $rating,
            'satisfaction'          => $satisfaction,
            'pic'                   => $r['agent'] ?: null,
        ];

        $this->caseModel->insert($data);
        $this->created++;
    }

    protected function resolveSiteId(string $departmentRaw): ?int
    {
        $departmentRaw = strtolower($departmentRaw);

        foreach ($this->config->siteKeywords as $keyword) {
            foreach ($keyword as $needle) {
                if (str_contains($departmentRaw, $needle)) {
                    $siteId = $this->matchSiteByKeyword($needle);
                    if ($siteId !== null) return $siteId;
                }
            }
        }

        if ($this->config->fallbackSiteKeyword) {
            return $this->matchSiteByKeyword($this->config->fallbackSiteKeyword);
        }

        return null;
    }

    protected function matchSiteByKeyword(string $needle): ?int
    {
        // needle bisa berupa 'garment' -> cari site yg namanya mengandung 'garmen'
        $normalized = str_contains($needle, 'kaos') || str_contains($needle, 'sock') ? 'socks' : 'garmen';

        foreach ($this->sitesLoaded as $site) {
            if (str_contains(strtolower($site['name']), $normalized)) {
                return (int) $site['id'];
            }
        }

        return null;
    }

    protected function findOnly($model, string $name): ?int
    {
        $key = get_class($model) . ':' . strtolower($name);

        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        $row = $model->where('LOWER(name)', strtolower($name))->first();

        $this->cache[$key] = $row ? (int) $row['id'] : null;

        return $this->cache[$key];
    }

    protected function findOrCreate($model, string $name): int
    {
        $id = $this->findOnly($model, $name);

        if ($id !== null) {
            return $id;
        }

        $newId = $model->insert(['name' => $name, 'is_active' => 1], true);

        $key = get_class($model) . ':' . strtolower($name);
        $this->cache[$key] = (int) $newId;

        return (int) $newId;
    }

    protected function generateCaseNumberForDate(string $receivedDate): string
    {
        $year = date('Y', strtotime($receivedDate));

        $last = $this->caseModel->builder()
            ->like('case_number', "GRV-{$year}", 'after')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();

        $number = 1;

        if ($last) {
            $number = (int) substr($last['case_number'], -5) + 1;
        }

        return sprintf('GRV-%s-%05d', $year, $number);
    }

    protected function parseDate(string $value): ?string
    {
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    protected function cleanText(string $text): string
    {
        // Buang baris penutup boilerplate "The case has been resolved. ( ... ) - App"
        $text = preg_replace('/The case has been resolved\.\s*\([^)]*\)\s*-\s*App\s*/i', '', $text);
        return trim($text);
    }
}
