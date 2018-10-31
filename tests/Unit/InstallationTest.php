<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstallationTest extends TestCase
{
    public function testThatStoolConfigGetsCopied() {
        $this->assertFileExists(base_path('config.json'));
    }

    public function testThatStoolInstallationGetsCopied() {
        $this->assertFileExists(base_path('installation.json'));
    }

    /**
     * During the installation all sensitive data is written to config.json. This test will test if all sensitive data of the installed modules are written to this file
     *
     * @return void
     * @author Benjamin Bortels <benjamin.bortels@ggh-mullenlowe.de>
     */
    public function testThatConfigIsPopulated() {

        $cConfigsThatNeedToChangeDuringInstallation = collect([
            0 => [
                'mysql.password',
                'mysql.password',
            ],
            'redis' => [
                'redis.password',
            ],
            'phpmyadmin' => [
                'phpmyadmin.htaccess.username',
                'phpmyadmin.htaccess.password',
            ],
        ]);

        $cConfigsThatNeedToChangeDuringInstallation->each(function($items, $key) {
            if (getInstallationConfigKey($key) || $key === 0) {
                foreach ($items as $item) {
                    $this->assertNotEquals(
                        array_get(getExampleConfig(), $item),
                        array_get(getConfig(), $item)
                    );
                }
            }
        });
    }
}
