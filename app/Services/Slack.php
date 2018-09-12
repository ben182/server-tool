<?php

namespace App\Services;

use App\Setting;
use App\Services\ApiRequestService;

class Slack
{
    public $token;

    public function __construct()
    {
        $this->token = Setting::where('key', 'deploy_slack_token')->value('value');
    }
    public function send($sText)
    {
        if (! $this->token) {
            return false;
        }

        return (new ApiRequestService())->request('sendSlack', [
            'public_id' => $this->token,
            'text' => $sText,
        ]);
    }
}
