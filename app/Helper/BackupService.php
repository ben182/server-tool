<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class BackupService
{
    public static function backup($sStorage, $sType, $sFilename)
    {
        Storage::disk($sStorage)->put('backups/' . ($sStorage === 'spaces' ? gethostname() . '/' : '') . "$sType/$sFilename", file_get_contents(base_path($sFileName)));

        unlink(base_path($sFileName));
    }
}
