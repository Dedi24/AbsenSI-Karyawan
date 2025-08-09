<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogController extends Controller
{
    protected $logPath;

    public function __construct()
    {
        // Tentukan path direktori log Laravel
        $this->logPath = storage_path('logs');
    }

    /**
     * Menampilkan daftar file log.
     */
    public function index()
    {
        $logFiles = [];

        // Pastikan direktori logs ada
        if (File::exists($this->logPath)) {
            // Ambil semua file .log
            $files = File::files($this->logPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => $file->getSize(),
                        'modified' => $file->getMTime(),
                    ];
                }
            }

            // Urutkan berdasarkan waktu modifikasi terbaru
            usort($logFiles, function ($a, $b) {
                return $b['modified'] <=> $a['modified'];
            });
        }

        return view('admin.logs.index', compact('logFiles'));
    }

    /**
     * Menampilkan isi file log.
     */
    public function show($filename)
    {
        // Validasi nama file untuk keamanan
        if (!$this->isValidLogFile($filename)) {
            return response()->json(['error' => 'File tidak valid atau tidak ditemukan.'], 404);
        }

        $filePath = $this->logPath . DIRECTORY_SEPARATOR . basename($filename);

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File log tidak ditemukan.'], 404);
        }

        // Baca isi file
        $content = File::get($filePath);

        // Untuk tampilan di browser, kirim sebagai text/plain
        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Mengunduh file log.
     */
    public function download($filename)
    {
        // Validasi nama file untuk keamanan
        if (!$this->isValidLogFile($filename)) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $filePath = $this->logPath . DIRECTORY_SEPARATOR . basename($filename);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File log tidak ditemukan.');
        }

        return Response::download($filePath, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Menghapus satu file log.
     */
    public function destroy($filename)
    {
        // Validasi nama file untuk keamanan
        if (!$this->isValidLogFile($filename)) {
            return response()->json(['success' => false, 'message' => 'File tidak valid.'], 400);
        }

        $filePath = $this->logPath . DIRECTORY_SEPARATOR . basename($filename);

        if (File::exists($filePath)) {
            try {
                File::delete($filePath);
                return response()->json(['success' => true, 'message' => 'Log berhasil dihapus.']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus log: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 404);
    }

    /**
     * Menghapus semua file log.
     */
    public function clear()
    {
        try {
            // Hapus semua file .log di direktori logs
            $files = File::glob($this->logPath . DIRECTORY_SEPARATOR . '*.log');
            foreach ($files as $file) {
                File::delete($file);
            }

            return redirect()->back()->with('success', 'Semua log berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }

    /**
     * Memvalidasi nama file log untuk mencegah path traversal.
     * Hanya izinkan nama file yang terdiri dari huruf, angka, titik, dan underscore,
     * dan harus berakhiran .log.
     */
    private function isValidLogFile($filename)
    {
        // Cek apakah nama file hanya mengandung karakter yang diizinkan
        // dan berakhiran .log
        return preg_match('/^[a-zA-Z0-9._-]+\.log$/', $filename) === 1;
    }
}
