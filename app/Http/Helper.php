<?php

function scripts_path()
{
    return str_replace('\\', '/', base_path('scripts')) . '/';
}

function templates_path()
{
    return str_replace('\\', '/', base_path('templates')) . '/';
}

function replace_string_in_file($filename, $string_to_replace, $replace_with)
{
    $content = file_get_contents($filename);
    $content_chunks = explode($string_to_replace, $content);
    $content = implode($replace_with, $content_chunks);
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

function random_string_random_length()
{
    return str_random(random_int(15, 30));
}

function getMysqlCredentials()
{
    $aMysql = getConfig()['mysql'];

    $sMysqlUser = $aMysql['username'];
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
function buildMysqlCommand($sCommand)
{
    return shell_exec('mysql ' . getMysqlCredentials() . " -e \"$sCommand\"");
}

function createMysqlDatabase($sDatabase)
{
    $sDatabase = str_slug($sDatabase, null, 'de');

    buildMysqlCommand("CREATE DATABASE $sDatabase;");

    return $sDatabase;
}

function createMysqlUserAndGiveAccessToDatabase($sDatabase, $sUser = null, $sPassword = null)
{
    if (!$sUser) {
        $sUser = random_string_random_length();
    }
    if (!$sPassword) {
        $sPassword = random_string_random_length();
    }
    buildMysqlCommand("CREATE USER '$sUser'@'localhost';") . buildMysqlCommand("GRANT ALL PRIVILEGES ON $sDatabase.* To '$sUser'@'localhost' IDENTIFIED BY '$sPassword';") . buildMysqlCommand('FLUSH PRIVILEGES;');

    return [
        'user' => $sUser,
        'password' => $sPassword,
    ];
}

function deleteMysqlUser($sUser)
{
    return buildMysqlCommand("DROP USER '$sUser'@'localhost';");
}

function editEnvKey($sPath, $sKey, $sValue)
{
    if (!file_exists($sPath)) {
        return false;
    }

    $sFile = file_get_contents($sPath);

    preg_match("/(?<=$sKey=).*/", $sFile, $match);

    if (!isset($match[0])) {
        return false;
    }

    file_put_contents($sPath, str_replace(
        "$sKey=" . $match[0],
        "$sKey=" . $sValue,
        $sFile
    ));

    return true;
}

function buildBackupPath($sType, $sFilename)
{
    return 'backups/' . gethostname() . "/$sType/$sFilename";
}
