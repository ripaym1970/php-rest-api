<?php

function signinUser($connect, $data) {
    $login    = $data['login'];
    $password = $data['password'];

    $error_fields = [];

    if ($login === '') {
        $error_fields[] = 'login';
    }

    if ($password === '') {
        $error_fields[] = 'password';
    }

    if (!empty($error_fields)) {
        $response = [
            'status'  => false,
            'message' => 'Проверьте правильность полей',
            'fields'  => $error_fields,
        ];

        echo json_encode($response);
        die();
    }

    $password = md5($password);

    $check_user = mysqli_query($connect, "SELECT * FROM `user` WHERE `login` = '$login' AND `password` = '$password'");
    if (mysqli_num_rows($check_user) === 1) {
        $user = mysqli_fetch_assoc($check_user);

        $_SESSION['user'] = [
            'id'         => $user['id'],
            'login'      => $user['login'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
            'phone'      => $user['phone'],
        ];

        $response = [
            'status'    => true,
            'message'   => 'Авторизация успешна',
            'userId'    => $user['id'],
            'userLogin' => $user['login'],
        ];
    } else {
        $response = [
            'status'  => false,
            'message' => 'Не верный логин или пароль',
        ];
    }

    echo json_encode($response);
}

function addUser($connect, $data) {
    $login      = mysqli_real_escape_string($connect, $data['login']);
    $password   = md5($data['password']);
    $first_name = mysqli_real_escape_string($connect, $data['first_name']);
    $last_name  = mysqli_real_escape_string($connect, $data['last_name']);
    $email      = mysqli_real_escape_string($connect, $data['email']);
    $phone      = mysqli_real_escape_string($connect, $data['phone']);

    $check_login = mysqli_query($connect, "SELECT * FROM `user` WHERE `login` = '$login'");
    if (mysqli_num_rows($check_login) > 0) {
        $response = [
            'status'  => false,
            'type'    => 1,
            'message' => 'Такой логин уже существует',
            'fields'  => ['login'],
        ];

        echo json_encode($response);
        die();
    }

    $result = mysqli_query($connect , "INSERT INTO `user` (`id`,`login`,`password`,`first_name`,`last_name`,`email`,`phone`) VALUES (NULL, '$login', '$password', '$first_name', '$last_name', '$email', $phone)");

    $insertId = mysqli_insert_id($connect);

    if ($result && $insertId) {
        http_response_code(201);
        $res = [
            'status'  => true,
            'message' => 'Вы успешно зарегистрировались',
            'userId'  => $insertId,
        ];
    } else {
        http_response_code(404);
        $res = [
            'status'  => false,
            'message' => 'User not add',
        ];
    }
    echo json_encode($res);
}

function getCompanies($connect, $data) {
    if (empty($data['userId'])) {
        http_response_code(404);
        $response = [
            'status'  => false,
            'message' => 'Not parameter userId',
            '$data'   => $data,
        ];
        echo json_encode($response);
        die();
    }

    $userId = mysqli_real_escape_string($connect, $data['userId']);

    $companies = mysqli_query($connect , "SELECT * FROM `company` WHERE `user_id`={$userId}");

    $companyList = [];

    while ($company = mysqli_fetch_assoc($companies)) {
        $companyList[] = $company;
    }

    $response = [
        'status'  => true,
        'message' => 'Получены компании',
        'data'  => $companyList,
    ];

    echo json_encode($response);
}

function addCompany($connect, $data) {
    $name        = mysqli_real_escape_string($connect, $data['name']);
    $description = mysqli_real_escape_string($connect, $data['description']);
    $email       = mysqli_real_escape_string($connect, $data['email']);
    $phone       = mysqli_real_escape_string($connect, $data['phone']);
    $userId      = mysqli_real_escape_string($connect, $data['user_id']);

    $check_login = mysqli_query($connect, "SELECT * FROM `company` WHERE `name` = '$name'");
    if (mysqli_num_rows($check_login) > 0) {
        //http_response_code(404);
        $response = [
            'status'  => false,
            'message' => 'Такая компания уже существует',
            'fields'  => ['name'],
        ];

        echo json_encode($response);
        die();
    }

    $result = mysqli_query($connect , "INSERT INTO `company` (`id`,`name`,`description`,`email`,`phone`,`user_id`) VALUES (NULL, '$name', '$description', '$email', '$phone', '$userId')");

    $insertId = mysqli_insert_id($connect);

    if ($result && $insertId) {
        http_response_code(201);
        $res = [
            'status'  => true,
            'message' => 'Компания успешно добавлена',
            'userId'  => $insertId,
        ];
    } else {
        http_response_code(404);
        $res = [
            'status'  => false,
            'message' => 'Компания не добавлена',
        ];
    }
    echo json_encode($res);
}

function recoverPassword($connect, $data) {
    http_response_code(404);
    $res = [
        'status'  => false,
        'message' => 'Не понятна функциональность',
    ];

    echo json_encode($res);
}
