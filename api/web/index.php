<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: json/application');

require 'users.php';
require 'user.php';

$connect = mysqli_connect('localhost', 'root', 'root', 'phprestapi');

$method = $_SERVER['REQUEST_METHOD'];

$q = $_GET['q'];
$params = explode('/', $q);

$controller = (string)$params[0];
$id         = (int)$params[1];
//$action     = (string)$params[2];

if ($controller === 'users') {
    if ($method === 'GET') {
        if (!empty($id)) {
            getUser($connect, $id);
        } else {
            getUsers($connect);
        }
    //} elseif ($method === 'POST') {
    //    addUser($connect, $_POST);
    } elseif ($method === 'DELETE') {
        if (!empty($id)) {
            deleteUser($connect, $id);
        }
    } elseif ($method === 'PATCH') {
        if (!empty($id)) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            updateUser($connect, $id, $data);
        }
    }
} elseif ($controller === 'user') {
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
        }
    }
}
