<?php

namespace App\Livewire\Dashboard;

use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\On;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode as SimpleSoftwareIO;

class QrCode extends Component {
    public $qrCode;

    public function mount($address) {
        $this->cleanupOldQRCodes();
        $this->qrCode = $this->generateQrWithLogo($address);
    }

    private function generateQrWithLogo($walletAddress) {
        $qrSize = 512;
        $logoScale = 0.15; // Logo size relative to QR size

        $dir = public_path('qrcodes');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $fileName = Auth::id() . '_' . time() . '.png';
        $qrPath = "$dir/$fileName";

        $logoPath = public_path('images/coin-logo.png');
        if (!file_exists($logoPath)) abort(500, 'Logo not found');

        // Generate QR PNG
        $options = new QROptions([
            'eccLevel' => \chillerlan\QRCode\QRCode::ECC_H,
            'scale' => 10,
            'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
        ]);

        $qr = new \chillerlan\QRCode\QRCode($options);
        $qr->render($walletAddress, $qrPath);

        // Overlay logo in center
        $qrImg = imagecreatefrompng($qrPath);
        $logoImg = imagecreatefrompng($logoPath);

        $qrW = imagesx($qrImg);
        $qrH = imagesy($qrImg);

        $logoW = imagesx($logoImg);
        $logoH = imagesy($logoImg);

        $newLogoW = $qrW * $logoScale;
        $newLogoH = $logoH * ($newLogoW / $logoW);

        $x = ($qrW - $newLogoW) / 2;
        $y = ($qrH - $newLogoH) / 2;

        // 1️⃣ Draw white circle behind the logo
        $circleRadius = max($newLogoW, $newLogoH) / 2 + 8; // padding
        $circleX = $qrW / 2;
        $circleY = $qrH / 2;

        $white = imagecolorallocate($qrImg, 255, 255, 255);
        imagefilledellipse($qrImg, $circleX, $circleY, $circleRadius*2, $circleRadius*2, $white);

        // 2️⃣ Place logo on top
        imagecopyresampled($qrImg, $logoImg, $x, $y, 0, 0, $newLogoW, $newLogoH, $logoW, $logoH);

        imagepng($qrImg, $qrPath);

        imagedestroy($qrImg);
        imagedestroy($logoImg);

        return asset("qrcodes/$fileName");
    }

    private function cleanupOldQRCodes($minutes = 20) {
        $dir = public_path('qrcodes');
        if (!File::exists($dir)) return;

        $files = File::files($dir);
        $now = time();

        foreach ($files as $file) {
            if ($now - $file->getCTime() > ($minutes * 60)) {
                File::delete($file->getPathname());
            }
        }
    }

    public function render() {
        return view('livewire.dashboard.qr-code');
    }


}
