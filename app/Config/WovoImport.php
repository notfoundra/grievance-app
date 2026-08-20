<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class WovoImport extends BaseConfig
{
    /**
     * Kata kunci buat nentuin Site berdasarkan kolom "Department" raw WOVO.
     * Dicocokkan dengan nama master_sites secara case-insensitive "contains".
     * Kalau nama site di database lo beda, cukup sesuaikan keyword di sini
     * ATAU ganti nama site di master_sites biar mengandung kata ini.
     */
    public array $siteKeywords = [
        'garmen' => ['garment'],
        'socks'  => ['kaos kaki', 'sock'],
    ];

    /**
     * Site fallback kalau kolom Department raw kosong / gak match keyword apapun.
     * Set null kalau mau baris seperti itu di-skip (masuk laporan error) bukan dipaksa masuk.
     */
    public ?string $fallbackSiteKeyword = null;

    /**
     * Case Type raw (WOVO) => [nama Case Type internal, nama Department penanggung jawab]
     * Case Type internal akan di-auto-create kalau belum ada di master_case_types.
     * Department WAJIB sudah ada di master_departments (tidak di-auto-create).
     */
    public array $caseTypeMap = [
        '1. OCCUPATIONAL HEALTH, SAFETY & ENVIRONMENT'       => ['Occupational Health, Safety & Environment', 'HSE & Compliance'],
        '2. WAGES AND INCENTIVES'                            => ['Wages & Incentives', 'HRD'],
        '3. BENEFITS'                                        => ['Benefits', 'HRD'],
        '4. GENERAL FACILITIES'                              => ['General Facilities', 'GA'],
        '5.. HARASSMENT AND ABUSE'                           => ['Harassment & Abuse', 'HRD'],
        '6. WORKING HOURS'                                   => ['Working Hours', 'HRD'],
        '7. PRODUCTION'                                      => ['Production', 'Production'],
        '8. RECRUITMENT AND CONTRACT'                        => ['Recruitment & Contract', 'HRD'],
        '9. TERMINATION AND RESIGNATION'                     => ['Termination & Resignation', 'HRD'],
        '10. PERSONNEL CHANGE AND PERFORMANCE APPRAISAL'     => ['Personal Change & Performance Appraisal', 'HRD'],
        '11. DISCIPLINARY ACTIONS'                           => ['Disciplinary Action', 'HRD'],
        '13. COMMUNICATION AND GRIEVANCE CHANNELS'           => ['Communication & Grievance Channels', 'HRD'],
        '14. FREEDOM OF ASSOCIATION AND WORKER REPRESENTATIONS' => ['Freedom of association and workers representation', 'Union/Worker Representative'],
        '15. PERSONAL AFFAIRS'                               => ['Personal Affairs', 'HRD'],
        '16. OTHER'                                          => ['Others', 'Others'],
        '17. JUNK'                                           => ['Others', 'Others'],
    ];

    /**
     * Case Type raw yang mau di-skip total (gak diimport), misal data sampah.
     * Cocok persis (case-insensitive) dengan value kolom "Case Type".
     */
    public array $skipCaseTypes = [
        '17. JUNK',
    ];

    /**
     * Classification raw (WOVO) => nama Message Type internal (master_message_types).
     * Auto-create kalau belum ada.
     */
    public array $classificationMap = [
        'ask'     => 'Ask',
        'suggest' => 'Suggestion',
        'report'  => 'Report',
    ];

    /**
     * Case Status raw => nama Status internal (master_statuses). Harus sudah ada.
     * Value yang gak ketemu di map ini otomatis fallback ke 'defaultStatus'.
     */
    public array $statusMap = [
        'resolved' => 'Closed',
        'open'     => 'Open',
        'pending'  => 'In Progress',
    ];

    public string $defaultStatus = 'Open';

    /** Priority default untuk semua case hasil import (tidak ada kolom priority di raw WOVO). */
    public string $defaultPriority = 'Medium';

    /** Channel raw (WOVO) => nama Channel internal. Auto-create kalau belum ada. */
    public array $channelMap = [
        'app' => 'WOVO App',
        'sms' => 'WOVO SMS',
    ];

    /** Case Satisfaction raw => rating angka (1-5). */
    public array $satisfactionRatingMap = [
        'satisfied'   => 5,
        'unsatisfied' => 2,
    ];
}
