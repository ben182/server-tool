<?php

namespace App\Services;

use App\Setting;
use App\Services\ApiRequestService;

class Slack
{
    protected $api;
    protected $channel;

    public function __construct(ApiRequestService $api)
    {
        $this->api = $api;
        $this->channel = Setting::where('key', 'slack_channel')->value('value');
    }
    public function send($sText, $sFormat = null)
    {
        if (! $this->channel) {
            return false;
        }

        return $this->api->request('slack/send', [
            'text' => $sText,
            'channel' => $this->channel,
            'format' => $sFormat,
        ]);
    }
}
