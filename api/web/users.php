<?php

function getUsers($connect) {
    $users = mysqli_query($connect , "SELECT * FROM `user`");

    $userList = [];

    while ($user = mysqli_fetch_assoc($users)) {
        $userList[] = $user;
    }

    echo json_encode($userList);
}

function getUser($connect, $id) {
    $user = mysqli_query($connect , "SELECT * FROM `user` WHERE `id`='$id'");

    if (mysqli_num_rows($user) === 1) {
        $user = mysqli_fetch_assoc($user);
        echo json_encode($user);
    } else {
        http_response_code(404);
        $res = [
            'status'  => false,
            'message' => 'User not found',
        ];
        echo json_encode($res);
    }
}

function addUser($connect, $data) {
    $username = $data['username'];
    //$username = 'Второй';
    $username = mysqli_real_escape_string($connect, $username);
    $result = mysqli_query($connect , "INSERT INTO `user` (`id`,`username`) VALUES (NULL, '$username')");

    $userId = mysqli_insert_id($connect);

    if ($result && $userId) {
        http_response_code(201);
        $res = [
            'status'  => true,
            'message' => 'User is add',
            'userId'  => $userId,
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

function updateUser($connect, $id, $data) {
    $username = $data['username'];
    //$username = 'Третий';
    $username = mysqli_real_escape_string($connect, $username);
    $result = mysqli_query($connect , "UPDATE `user` SET `username` = '$username' WHERE `user`.`id` = '$id'");

    if ($result === true) {
        http_response_code(201);
        $res = [
            'status'  => true,
            'message' => 'User is updated',
        ];
    } else {
        http_response_code(404);
        $res = [
            'status'  => false,
            'message' => 'User not updated',
        ];
    }
    echo json_encode($res);
}

function deleteUser($connect, $id) {
    $result = mysqli_query($connect , "DELETE FROM `user` WHERE `id`='$id'");

    if ($result === true) {
        http_response_code(200);
        $res = [
            'status'  => true,
            'message' => 'User is deleted',
        ];
    } else {
        http_response_code(404);
        $res = [
            'status'  => false,
            'message' => 'User not deleted',
        ];
    }
    echo json_encode($res);
}
