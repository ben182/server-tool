<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

class StoolTest extends TestCase
{
    public function testCronjobs() {

        $aCronjobs = [
            '* * * * * stool schedule:run >> /dev/null 2>&1',
            '0 0 * * * composer self-update >> /dev/null 2>&1',
            '0 0 * * 0 apt-get autoremove && apt-get autoclean -y >> /dev/null 2>&1',
        ];

        if (getInstallationConfigKey('certbot')) {
            $aCronjobs[] = '0 */12 * * * certbot renew --post-hook "systemctl reload apache2"';
        }

        $this->assertThatCommandOutputContains($aCronjobs, 'crontab -l');
    }
}
