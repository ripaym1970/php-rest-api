<?php

session_start();
require_once 'connect.php';

$login      = $_POST['login'];
$password   = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name  = $_POST['last_name'];
$email      = $_POST['email'];
$phone      = $_POST['phone'];


$check_login = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login'");
if (mysqli_num_rows($check_login) > 0) {
    $response = [
        "status"  => false,
        "type"    => 1,
        "message" => "Такой логин уже существует",
        "fields"  => ['login'],
    ];

    echo json_encode($response);
    die();
}

$error_fields = [];

if ($login === '') {
    $error_fields[] = 'login';
}

if ($password === '') {
    $error_fields[] = 'password';
}

if ($first_name === '') {
    $error_fields[] = 'first_name';
}

if ($last_name === '') {
    $error_fields[] = 'last_name';
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_fields[] = 'email';
}

if ($phone === '') {
    $error_fields[] = 'phone';
}

if (!empty($error_fields)) {
    $response = [
        'status'  => false,
        'type'    => 1,
        'message' => 'Проверьте правильность полей',
        'fields'  => $error_fields
    ];

    echo json_encode($response);

    die();
}

if ($password) {
    $password = md5($password);

    mysqli_query($connect, "INSERT INTO `user` (`id`, `login`, `password`, `first_name`, `last_name`, `email`, `phone`) VALUES (NULL, '$login', '$password', '$first_name', '$last_name', '$email', '$phone')");

    $response = [
        'status' => true,
        'message' => 'Регистрация прошла успешно!',
    ];
    header('Content-type: json/application');
    echo json_encode($response);
}

?>
