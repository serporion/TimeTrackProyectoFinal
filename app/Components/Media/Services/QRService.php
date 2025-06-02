<?php

namespace App\Components\Media\Services;

use App\Components\Media\Models\QR;

class QRService
{
    public function generarQR(array $data): QR
    {
        return QR::create($data);
    }
}
