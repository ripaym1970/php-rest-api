<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: json/application');

session_start();

require 'user.php';

$connect = mysqli_connect('localhost', 'root', 'root', 'phprestapi');

$q = $_GET['q'];
$params = explode('/', $q);

$controller = (string)$params[0];
$id         = (int)$params[1];
$method     = $_SERVER['REQUEST_METHOD'];

if ($controller === 'user') {
    $action = (string)$params[1];
    if ($method === 'GET') {
        if ($action === 'companies') {
            getCompanies($connect, $_GET);
        }
    } elseif ($method === 'PATCH') {
        if ($action === 'recover-password') {
            recoverPassword($connect, $_POST);
        }
    } elseif ($method === 'POST') {
        if ($action === 'sign-in') {
            signinUser($connect, $_POST);
        } elseif ($action === 'register') {
            addUser($connect, $_POST);
        } elseif ($action === 'companies') {
            addCompany($connect, $_POST);
        } elseif ($action === 'logout') {
            logoutUser();
        }
    }
}
