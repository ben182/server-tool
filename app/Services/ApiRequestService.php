<?php

namespace App\Services;

class ApiRequestService
{
    public $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => config('services.stool.api.url'),
            'headers'  => [
                'Accept' => 'application/vnd.stool.' . config('services.stool.api.version') . '+json',
            ],
        ]);
    }
    public function request($sRoute, $aParams)
    {
        return $this->client->request('POST', $sRoute, [
            'form_params' => $aParams,
        ])->getBody();
    }
}
