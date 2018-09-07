<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class BackupService
{
    /**
     * Backups a file to storage
     *
     * @param string $sStorage Storage type. local or spaces
     * @param string $sType The subfolder
     * @param string $sFileName The filename. It must be a valid file saved in the root folder
     *
     * @return void
     * @author Benjamin Bortels <benjamin.bortels@ggh-mullenlowe.de>
     */
    public static function backup($sStorage, $sType, $sFileName)
    {
        Storage::disk($sStorage)->put('backups/' . ($sStorage === 'spaces' ? gethostname() . '/' : '') . "$sType/$sFileName", file_get_contents(base_path($sFileName)));

        unlink(base_path($sFileName));
    }
}
