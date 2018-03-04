<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeployController extends Controller
{

    public function __construct()
    {
        $this->middleware('routeFromId');
    }
    public function index() {

        $aRoute = request()->routeFromId;

        $postBody = file_get_contents( 'php://input' );

        if( 'sha1=' . hash_hmac( 'sha1', $postBody, $aRoute['secret'] ) !== $_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]) {
                return 'wrong secret';
        }

        $sCommand = 'cd ' . $aRoute['dir'];
        if ($aRoute['reset'])
            $sCommand .= ' && git reset --hard HEAD 2>&1';

        $sCommand .= ' && git pull origin ' . $aRoute['branch'] . ' 2>&1';

        echo shell_exec($sCommand);
    }
}
