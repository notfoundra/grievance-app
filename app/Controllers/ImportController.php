<?php

namespace App\Controllers;

use App\Libraries\WovoImporter;

class ImportController extends BaseController
{
    public function index()
    {
        return view('grievance/import');
    }

    public function process()
    {
        $file = $this->request->getFile('wovo_file');
        $source = $this->request->getPost('source');
        return $this->response->setJSON([
            'status' => false,
            'message' => $source,
        ]);
        if (! $file || ! $file->isValid()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'message' => 'File tidak valid atau belum dipilih.',
            ]);
        }

        $ext = strtolower($file->getClientExtension());

        if (! in_array($ext, ['xlsx', 'xls'], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'message' => 'Format file harus .xlsx atau .xls',
            ]);
        }

        $tmpPath = WRITEPATH . 'uploads/tmp_' . $file->getRandomName();
        $file->move(dirname($tmpPath), basename($tmpPath));

        try {
            $importer = new WovoImporter();
            $result   = $importer->run($tmpPath);
        } catch (\Throwable $e) {
            @unlink($tmpPath);

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ]);
        }

        @unlink($tmpPath);

        return $this->response->setJSON([
            'status' => true,
            'result' => $result,
        ]);
    }
}
