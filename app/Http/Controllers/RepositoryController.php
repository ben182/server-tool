<?php

namespace App\Http\Controllers;

use App\Repository;

class RepositoryController extends Controller
{
    public function index(Repository $oRepository)
    {
        $postBody = file_get_contents('php://input');

        if ('sha1=' . hash_hmac('sha1', $postBody, $oRepository->secret) !== $_SERVER['HTTP_X_HUB_SIGNATURE']) {
            return response('Wrong Secret', 500);
        }

        $sCommand = 'cd ' . $oRepository->dir;
        if ($oRepository->reset) {
            $sCommand .= ' && git reset --hard HEAD 2>&1';
        }

        $sCommand .= ' && git pull origin ' . $oRepository->branch . ' 2>&1';

        echo shell_exec($sCommand);
    }
}