<?php

namespace App\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeController extends BaseController
{
    public function poster()
    {
        return view('grievance/qr_poster', ['formUrl' => site_url('lapor')]);
    }

    public function image()
    {
        if (! class_exists(QrCode::class)) {
            return $this->response
                ->setStatusCode(500)
                ->setBody('Library endroid/qr-code belum terpasang. Jalankan: composer require endroid/qr-code');
        }

        try {
            $qrCode = new QrCode(
                data: site_url('lapor'),
                size: 500,
                margin: 10,
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
        } catch (\Throwable $e) {
            log_message('error', 'QR generation failed: ' . $e->getMessage());

            return $this->response
                ->setStatusCode(500)
                ->setBody('Gagal generate QR Code: ' . $e->getMessage());
        }

        return $this->response
            ->setContentType('image/png')
            ->setBody($result->getString());
    }
}
