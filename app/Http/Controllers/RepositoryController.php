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

        switch ($_SERVER['CONTENT_TYPE']) {
            case 'application/json':
                $postBody = file_get_contents('php://input');
                break;
            case 'application/x-www-form-urlencoded':
                $postBody = $_POST['payload'];
                break;
            default:
                throw new \Exception("Unsupported content type: {$_SERVER['CONTENT_TYPE']}");
        }
        $oPostBody = json_decode($postBody);

        if ('sha1=' . hash_hmac('sha1', file_get_contents('php://input'), $oRepository->secret) !== $_SERVER['HTTP_X_HUB_SIGNATURE']) {
            return response('Wrong Secret', 500);
        }

        $oPostBody->ref = $oPostBody->ref ?? 'refs/heads/master';

        if (! str_contains($oPostBody->ref, 'refs/heads/')) {
            return 'Not Head';
        }

        if (str_replace('refs/heads/', '', $oPostBody->ref) !== $oRepository->branch) {
            return 'Wrong Branch';
        }

        putenv("COMPOSER_HOME=/home/stool/.composer");

        Deploy::dispatch($oRepository, $oPostBody);
    }
}
