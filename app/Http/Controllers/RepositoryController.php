<?php

namespace App\Http\Controllers;

use App\Jobs\Deploy;
use App\Repository;

class RepositoryController extends Controller
{
    public function index(Repository $oRepository)
    {
        if (! isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
            abort(404);
        }

        $postBody = file_get_contents('php://input');

        if ('sha1=' . hash_hmac('sha1', $postBody, $oRepository->secret) !== $_SERVER['HTTP_X_HUB_SIGNATURE']) {
            return response('Wrong Secret', 500);
        }

        putenv("COMPOSER_HOME=/var/www/.composer");

        Deploy::dispatch($oRepository);
    }
}
