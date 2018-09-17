<?php

namespace App\Services;

use App\Setting;
use App\Services\ApiRequestService;

class Slack
{
    public $channel;

    public function __construct()
    {
        $this->channel = Setting::where('key', 'slack_channel')->value('value');
    }
    public function send($sText, $sFormat = null)
    {
        if (! $this->channel) {
            return false;
        }

        return (new ApiRequestService())->request('sendSlack', [
            'text' => $sText,
            'channel' => $this->channel,
            'format' => $sFormat,
        ]);
    }
}
