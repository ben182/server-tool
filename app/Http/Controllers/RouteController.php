<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RouteController extends Controller
{
    public static function getAll() {
        $aRoutes = [];
        if (Storage::exists('routes')) {
            $aRoutes = json_decode(Storage::get('routes'), TRUE);
        }
        return $aRoutes;
    }

    public static function get($sIndex) {
        $aRoutes = self::getAll();
        return $aRoutes[$sIndex] ?? FALSE;
    }

    public static function add($aData) {

        $aRoutes = self::getAll();

        do {
            $sId = random_string_random_length();
        } while (isset($aRoutes[$sId]));

        $aData = array_merge($aData, [
            'secret' => random_string_random_length(),
        ]);

        $aData['dir'] = '/var/www/' . $aData['dir'];

        $aRoutes[$sId] = $aData;


        Storage::put('routes', json_encode($aRoutes));

        return array_merge($aRoutes[$sId], [
            'id' => $sId,
        ]);
    }
}
