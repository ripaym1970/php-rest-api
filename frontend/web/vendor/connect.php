<?php

$connect = mysqli_connect('localhost', 'root', 'root', 'phprestapi');

if (!$connect) {
    die('Error connect to DataBase');
}
