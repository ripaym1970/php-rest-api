<?php
session_start();

//if ($_SESSION['user']) {
//    header('Location: profile.php');
//}

$auth  = $_SESSION['user'] ? '' : ' hidden';
$guest = $_SESSION['user'] ? ' hidden' : '';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Test API</title>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/style.css">
    </head>

    <body>
        <header class="header">
            <div class="container">
                <div class="header-wrap">
                    <div class="header-item header-item-left">
                        <div class="header-menu">
                            <a href="#" class="header-menu-item<?=$auth?>">Компании</a>
                            <a href="#" class="header-menu-item<?=$auth?>">Пользователи</a>
                            <a href="/vendor/logout.php" class="logout<?=$auth?>">Выход</a>
<!--                            <a href="/register.php" class="header-menu-item--><?//=$guest?><!--">Регистрация</a>-->
<!--                            <a href="/" class="header-menu-item--><?//=$guest?><!--">Авторизация</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <section class="users<?=$auth?>">
                <h1>Пользователи</h1>
                <div class="container mt-5">
                    <div class="row list">

                    </div>

                    <div class="row col-12">
                        <div class="md-5">
                            <h5>Добавление</h5>
                            <div class="form-group">
                                <label for="usernameadd">User Name</label>
                                <input type="text" class="form-control" id="usernameadd" name="usernameadd" value="">
                            </div>
                            <button class="btn btn-primary" onClick="addUser()">Добавить пользователя</button>
                        </div>
                        <div class="md-5">
                            <h5>Редактирование</h5>
                            <div class="form-group">
                                <label for="usernameupdate">User Name</label>
                                <input type="text" class="form-control" id="usernameupdate" name="usernameupdate" value="">
                            </div>
                            <button class="btn btn-primary" onClick="updateUser()">Сохранить</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="signup<?=$auth?>"">
                <form>
                    <label>Логин</label>
                    <input type="text" name="login" placeholder="Введите логин" value="ripa1">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Введите пароль" value="qwerty123">
                    <label>Имя</label>
                    <input type="text" name="first_name" placeholder="Введите свое имя" value="Юрий">
                    <label>Фамилия</label>
                    <input type="text" name="last_name" placeholder="Введите свое фамилию" value="Рипа">
                    <label>Почта</label>
                    <input type="email" name="email" placeholder="Введите свою почту" value="a1@i.ua">
                    <label>Телефон</label>
                    <input type="text" name="phone" placeholder="Введите свой телефон" value="+3804523906">
                    <p class="signup-msg hidden">Проверьте правильность полей</p>
                    <button type="button" class="register-btn">Зарегистрироваться</button>
                    <p><a href="#" onClick="$('.signup').addClass('hidden');$('.login').removeClass('hidden');"">Aвторизация</a></p>
                </form>
            </section>

            <section class="login<?=$guest?>">
                <form>
                    <label>Логин</label>
                    <input type="text" name="login" placeholder="Введите свой логин" value="ripa1">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Введите пароль" value="">
                    <p class="login-msg hidden">Проверьте правильность полей</p>
                    <button type="button" class="login-btn">Войти</button>
                    <p><a href="#" onClick="$('.login').addClass('hidden');$('.signup').removeClass('hidden');">Регистрация</a></p>
                </form>
            </section>
        </main>

        <script src="/js/jquery-3.4.1.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>

<script>


    // Регистрация
    $('.register-btn').click(function (e) {
        e.preventDefault();

        $(`input`).removeClass('error');

        let login = $('input[name="login"]').val(),
            password = $('input[name="password"]').val(),
            full_name = $('input[name="full_name"]').val(),
            email = $('input[name="email"]').val(),
            password_confirm = $('input[name="password_confirm"]').val();

        let formData = new FormData();
        formData.append('login', login);
        formData.append('password', password);
        formData.append('password_confirm', password_confirm);
        formData.append('full_name', full_name);
        formData.append('email', email);
        formData.append('avatar', avatar);

        $.ajax({
            url: 'vendor/signup.php',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success (data) {
                if (data.status) {
                    document.location.href = '/index.php';
                } else {
                    if (data.type === 1) {
                        data.fields.forEach(function (field) {
                            $(`input[name="${field}"]`).addClass('error');
                        });
                    }
                    $('.msg').removeClass('none').text(data.message);
                }
            }
        });
    });
</script>
