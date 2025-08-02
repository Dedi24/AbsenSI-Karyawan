<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function index()
    {
        $logFiles = $this->getLogFiles();
        
        return view('admin.logs.index', compact('logFiles'));
    }

    public function show($filename)
    {
        $logContent = $this->getLogContent($filename);
        
        return view('admin.logs.show', compact('logContent', 'filename'));
    }

    public function destroy($filename)
    {
        $this->deleteLogFile($filename);
        
        return redirect()->route('admin.logs.index')->with('success', 'File log berhasil dihapus.');
    }

    public function clear()
    {
        $this->clearLogs();
        
        return redirect()->route('admin.logs.index')->with('success', 'Semua log berhasil dihapus.');
    }

    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        $logFiles = [];

        if (File::exists($logPath)) {
            $files = File::files($logPath);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'modified' => $file->getMTime(),
                        'path' => $file->getPathname()
                    ];
                }
            }
        }

        // Sort by modified time (newest first)
        usort($logFiles, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return $logFiles;
    }

    private function getLogContent($filename)
    {
        $filePath = storage_path('logs/' . $filename);
        
        if (File::exists($filePath)) {
            $content = File::get($filePath);
            return $content;
        }

        return 'File log tidak ditemukan.';
    }

    private function deleteLogFile($filename)
    {
        $filePath = storage_path('logs/' . $filename);
        
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    private function clearLogs()
    {
        $logPath = storage_path('logs');
        
        if (File::exists($logPath)) {
            $files = File::files($logPath);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    File::delete($file->getPathname());
                }
            }
        }
    }
}