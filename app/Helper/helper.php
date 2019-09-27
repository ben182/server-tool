<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

function scripts_path($sPath = '')
{
    return str_replace('\\', '/', base_path('scripts')) . '/' . $sPath;
}

function templates_path($sPath = '')
{
    return str_replace('\\', '/', base_path('templates')) . '/' . $sPath;
}

function replace_string_in_file($filename, $string_to_replace, $replace_with)
{
    $content        = file_get_contents($filename);
    $content_chunks = explode($string_to_replace, $content);
    $content        = implode($replace_with, $content_chunks);
    file_put_contents($filename, $content);
}

function getStringBetween($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));

    return substr($sub, 0, strpos($sub, $to));
}

function getConfig()
{
    return json_decode(file_get_contents(base_path('config.json')), true);
}
function getExampleConfig()
{
    return json_decode(file_get_contents(base_path('config.example.json')), true);
}

function getInstallationConfig()
{
    return json_decode(file_get_contents(base_path('installation.json')), true);
}

function getInstallationConfigKey($sKey)
{
    return optional(json_decode(file_get_contents(base_path('installation.json'))))->$sKey === 'true';
}

function random_string_random_length()
{
    return Str::random(random_int(15, 30));
}

function getMysqlCredentials()
{
    $aMysql = getConfig()['mysql'];

    $sMysqlUser     = $aMysql['username'];
    $sMysqlPassword = $aMysql['password'];

    return "-u $sMysqlUser -p\"$sMysqlPassword\"";
}

function getRedisPassword()
{
    return getConfig()['redis']['password'];
}
function isSpacesSet()
{
    return env('DO_SPACES_KEY') != false;
}
function buildMysqlCommand($sCommand, $bOutIn = false)
{
    return shell_exec('mysql ' . getMysqlCredentials() . " -e \"$sCommand\"" . ($bOutIn ? ' 2>&1' : ''));
}

function createMysqlDatabase($sDatabase)
{
    $sDatabase = Str::slug($sDatabase, null);

    buildMysqlCommand("CREATE DATABASE $sDatabase;");

    return $sDatabase;
}

function createMysqlUserAndGiveAccessToDatabase($sDatabase, $sUser = null, $sPassword = null)
{
    if (! $sUser) {
        $sUser = random_string_random_length();
    }
    if (! $sPassword) {
        $sPassword = random_string_random_length();
    }
    buildMysqlCommand("CREATE USER '$sUser'@'localhost';") . buildMysqlCommand("GRANT ALL PRIVILEGES ON $sDatabase.* To '$sUser'@'localhost' IDENTIFIED BY '$sPassword';") . buildMysqlCommand('FLUSH PRIVILEGES;');

    return [
        'user'     => $sUser,
        'password' => $sPassword,
    ];
}

function deleteMysqlUser($sUser)
{
    return buildMysqlCommand("DROP USER '$sUser'@'localhost';");
}

function editEnvKey($sPath, $sKey, $sValue)
{
    if (! file_exists($sPath)) {
        return false;
    }

    $sFile = file_get_contents($sPath);

    preg_match("/(?<=$sKey=).*/", $sFile, $match);

    if (! isset($match[0])) {
        return false;
    }

    file_put_contents($sPath, str_replace(
        "$sKey=" . $match[0],
        "$sKey=" . $sValue,
        $sFile
    ));

    return true;
}

function editConfigKey($sKey, $sValue)
{
    $sOldValue = Arr::get(getConfig(), $sKey);

    $sFile = file_get_contents(base_path('config.json'));

    $aKeys    = explode('.', $sKey);
    $sLastKey = $aKeys[count($aKeys) - 1];

    file_put_contents(base_path('config.json'), str_replace(
        '"' . $sLastKey . '": "' . $sOldValue . '"',
        '"' . $sLastKey . '": "' . $sValue . '"',
        $sFile
    ));

    return true;
}

function editInstallationKey($sKey, $sValue)
{
    $sOldValue = Arr::get(getInstallationConfig(), $sKey);

    $sFile = file_get_contents(base_path('installation.json'));

    $aKeys    = explode('.', $sKey);
    $sLastKey = $aKeys[count($aKeys) - 1];

    file_put_contents(base_path('installation.json'), str_replace(
        '"' . $sLastKey . '": "' . $sOldValue . '"',
        '"' . $sLastKey . '": "' . $sValue . '"',
        $sFile
    ));

    return true;
}

function buildBackupPath($sType, $sFilename)
{
    return 'backups/' . gethostname() . "/$sType/$sFilename";
}

/**
 * Checks if a specific port is already blocked.
 *
 * @param int $iPort
 *
 * @return bool
 */
function checkIfPortIsUsed($iPort)
{
    exec('netstat -tulnp', $results);

    foreach ($results as $result) {
        if (Str::contains($result, ':' . $iPort) && Str::contains($result, 'LISTEN')) {
            return true;
        }
    }

    return false;
}

function quietCommand($sCommand)
{
    shell_exec($sCommand . ' 2>&1');
}

function fixApachePermissions()
{
    quietCommand('chown -R stool:stool /home/stool');
    quietCommand('chmod -R 755 /home/stool');
    quietCommand('chmod g+s /home/stool');
    // quietCommand('chmod -R 700 /home/stool/.ssh');
}

function restartApache()
{
    quietCommand('sudo service apache2 restart');
}

function isSubdomain($sDomain)
{
    return count(explode('.', $sDomain)) >= 3;
}

function getIp()
{
    return str_replace(['http://', '/stool'], '', config('app.url'));
}

function is_sha1($str)
{
    return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
}
