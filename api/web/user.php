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
            'status'  => true,
            'message' => 'Авторизация успешна',
            'userId'  => $user['id'],
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
    $login      = mysqli_real_escape_string($connect, validationString($data['login']));
    $password   = md5($data['password']);
    $first_name = mysqli_real_escape_string($connect, validationString($data['first_name']));
    $last_name  = mysqli_real_escape_string($connect, validationString($data['last_name']));
    $email      = mysqli_real_escape_string($connect, validationString($data['email']));
    $phone      = mysqli_real_escape_string($connect, validationString($data['phone']));

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

    $result = mysqli_query($connect , "INSERT INTO `user` (`id`,`login`,`password`,`first_name`,`last_name`,`email`,`phone`) VALUES (NULL, '$login', '$password', '$first_name', '$last_name', '$email', '$phone')");

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
            'errors'  => mysqli_error($connect),
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
    $userId      = mysqli_real_escape_string($connect, $data['userId']);

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

    $result = mysqli_query($connect, "INSERT INTO `company` (`id`,`name`,`description`,`email`,`phone`,`user_id`) VALUES (NULL, '$name', '$description', '$email', '$phone', '$userId')");

    $insertId = mysqli_insert_id($connect);

    if ($result && $insertId) {
        http_response_code(201);
        $res = [
            'status'    => true,
            'message'   => 'Компания успешно добавлена',
            'companyId' => $insertId,
        ];
    } else {
        http_response_code(404);
        $res = [
            'status'  => false,
            'message' => 'Компания не добавлена',
            'errors'  => mysqli_error($connect),
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

function unauthirized() {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Private Area"');
        header('HTTP/1.0 401 Unauthirized');
        //echo 'Sorry, you need proper credendtials';
        $response = [
            'status'  => false,
            'message' => 'Извините, но вам нужно авторизоваться',
        ];
        echo json_encode($response);
        die();
    }
}

function logoutUser() {
    unset($_SESSION['user']);
    $response = [
        'status'  => true,
        'message' => 'Извините, но вам нужно авторизоваться',
    ];
    echo json_encode($response);
}

/**
 * Валидация входящего параметра типа string
 *
 * @param $s
 * @return mixed|string
 */
function validationString($s) {
    $s = (string) $s; // преобразуем в строковое значение
    $s = strip_tags($s); // убираем HTML-теги
    $s = str_replace(["\n", "\r"], " ", $s); // убираем перевод каретки
    //$s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
    $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
    $s = trim($s); // убираем пробелы в начале и конце строки

    return $s; // возвращаем результат
}
