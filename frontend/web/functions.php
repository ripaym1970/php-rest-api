<?php

/**
 * Debug function with die() after
 *
 * @param        $data
 * @param string $data_name
 */
function dd($data, $data_name='$data') {
    $tmp_var = debug_backtrace(1);
    $caller = array_shift($tmp_var);

    error_reporting(-1);
    header('Content-Type: text/html; charset=utf-8');

    echo '<code>File: ' . $caller['file'] . ' / Line: ' . $caller['line'] . '</code>';
    echo '<pre>';
    echo $data_name . '=', PHP_EOL;
    var_dump($data);
    echo '</pre>';

    die();
}
