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

function buildMysqlCommand($sCommand) {
    $aMysql = getConfig()['mysql'];

    $sMysqlUser = $aMysql['username'];
    $sMysqlPassword = $aMysql['password'];

    return shell_exec("mysql -u $sMysqlUser -p\"$sMysqlPassword\" -e \"$sCommand\"");
}

function createMysqlDatabase($sDatabase) {

    echo buildMysqlCommand("CREATE DATABASE $sDatabase;");
}

function createMysqlUserAndGiveAccessToDatabase() {

}