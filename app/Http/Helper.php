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