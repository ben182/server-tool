<?php

function scripts_path() {
    return str_replace('\\', '/', base_path('scripts')) . '/';
}

function templates_path() {
    return str_replace('\\', '/', base_path('templates')) . '/';
}

function replace_string_in_file($filename, $string_to_replace, $replace_with){
    $content=file_get_contents($filename);
    $content_chunks=explode($string_to_replace, $content);
    $content=implode($replace_with, $content_chunks);
    file_put_contents($filename, $content);
}

function apache_permissions() {
    echo shell_exec('chown -R www-data:www-data /var/www 2>&1');
    echo shell_exec('chmod -R 755 /var/www 2>&1');
    echo shell_exec('chmod g+s /var/www 2>&1');
    echo shell_exec('chmod -R 700 /var/www/.ssh 2>&1');
}

function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}

function getConfig() {
    return json_decode(file_get_contents(base_path('config.json')), TRUE);
}

function random_string_random_length() {
    return str_random(random_int(15, 30));
}

function buildMysqlCommand($sCommand) {
    $aMysql = getConfig()['mysql'];

    $sMysqlUser = $aMysql['username'];
    $sMysqlPassword = $aMysql['password'];

    return shell_exec("mysql -u $sMysqlUser -p\"$sMysqlPassword\" -e \"$sCommand\"");
}

function createMysqlDatabase($sDatabase) {

    $sDatabase = str_slug($sDatabase, NULL, 'de');

    buildMysqlCommand("CREATE DATABASE $sDatabase;");

    return $sDatabase;
}

function createMysqlUserAndGiveAccessToDatabase($sDatabase, $sUser = NULL, $sPassword = NULL) {
    if (!$sUser) {
        $sUser = random_string_random_length();
    }
    if (!$sPassword) {
        $sPassword = random_string_random_length();
    }
    buildMysqlCommand("CREATE USER '$sUser'@'localhost';") . buildMysqlCommand("GRANT ALL PRIVILEGES ON $sDatabase.* To '$sUser'@'localhost' IDENTIFIED BY '$sPassword';") . buildMysqlCommand("FLUSH PRIVILEGES;");

    return [
        'user' => $sUser,
        'password' => $sPassword,
    ];
}

function deleteMysqlUser($sUser) {
    return buildMysqlCommand("DROP USER '$sUser'@'localhost';");
}