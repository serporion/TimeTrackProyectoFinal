<?php

namespace App\Components\Media\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Media\Requests\StoreQRRequest;
use App\Components\Media\Services\QRService;

class QRController extends Controller
{
    protected $qrService;

    public function __construct(QRService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function store(StoreQRRequest $request)
    {
        $qr = $this->qrService->generarQR($request->validated());

        return response()->json([
            'message' => 'QR generado correctamente',
            'data' => $qr
        ]);
    }
}
