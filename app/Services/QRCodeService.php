<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    public function generateAttendanceQR($userId, $date)
    {
        $data = [
            'user_id' => $userId,
            'date' => $date,
            'timestamp' => now()->timestamp
        ];

        $jsonData = json_encode($data);
        return QrCode::size(300)->generate($jsonData);
    }

    public function validateAttendanceQR($qrData, $userId)
    {
        try {
            $data = json_decode($qrData, true);

            if (!$data || !isset($data['user_id']) || !isset($data['date'])) {
                return false;
            }

            // Validasi apakah QR untuk user ini
            if ($data['user_id'] != $userId) {
                return false;
            }

            // Validasi apakah QR masih valid (max 5 menit)
            $qrTime = $data['timestamp'];
            $currentTime = now()->timestamp;

            if (($currentTime - $qrTime) > 300) { // 5 menit
                return false;
            }

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
