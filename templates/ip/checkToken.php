<?php

$sAllowedToken = 'TOKEN_HERE';
if (!isset($_GET['token']) || $_GET['token'] !== $sAllowedToken) {
    header("HTTP/1.0 404 Not Found");
    die();
}