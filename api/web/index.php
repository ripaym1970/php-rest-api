<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: json/application');

session_start();

//require 'users.php';
require 'user.php';

$connect = mysqli_connect('localhost', 'root', 'root', 'phprestapi');

$q = $_GET['q'];
$params = explode('/', $q);
//$params = explode('/', $_SERVER['REDIRECT_URL']);
//$response = [
//    'status'  => false,
//    'message' => 'Извините, но вам нужно авторизоваться',
//    '$_SERVER' => $_SERVER,
//    '$params' => $params,
//    '$q' => $q,
//];
//echo json_encode($response);
//die();

$controller = (string)$params[0];
$id         = (int)$params[1];
$method     = $_SERVER['REQUEST_METHOD'];

//if ($controller === 'users') {
    //if ($method === 'GET') {
    //    if (!empty($id)) {
    //        getUser($connect, $id);
    //    } else {
    //        getUsers($connect);
    //    }
    ////} elseif ($method === 'POST') {
    ////    addUser($connect, $_POST);
    //} elseif ($method === 'DELETE') {
    //    if (!empty($id)) {
    //        deleteUser($connect, $id);
    //    }
    //} elseif ($method === 'PATCH') {
    //    if (!empty($id)) {
    //        $data = file_get_contents('php://input');
    //        $data = json_decode($data, true);
    //        updateUser($connect, $id, $data);
    //    }
    //}
//} else
if ($controller === 'user') {
    $action = (string)$params[1];
    if ($method === 'GET') {
        if ($action === 'companies') {
            //unauthirized();
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
            //unauthirized();
            addCompany($connect, $_POST);
        } elseif ($action === 'logout') {
            logoutUser();
        }
    }
}
