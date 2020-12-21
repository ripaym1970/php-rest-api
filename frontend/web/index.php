<?php

require 'functions.php';

session_start();

$auth  = $_SESSION['user'] ? '' : ' hidden';
$guest = $_SESSION['user'] ? ' hidden' : '';

//dd($_SESSION);

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
<!--                            <a href="#" class="header-menu-item--><?//=$auth?><!--">Компании</a>-->
<!--                            <a href="#" class="header-menu-item--><?//=$auth?><!--">Пользователи</a>-->
<!--                            <a href="/register.php" class="header-menu-item--><?//=$guest?><!--">Регистрация</a>-->
<!--                            <a href="/" class="header-menu-item--><?//=$guest?><!--">Авторизация</a>-->
                            <a href="logout.php" class="logout<?=$auth?>">Выход (<span class="userlogin"></span>)</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <h1 class="mb30">Тестовое задание</h1>
            <section class="users<?=$auth?>">
                <h2>Пользователи</h2>
                <div class="container mt-5">
                    <div class="row users-list">

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

            <section class="companies<?=$auth?>">
                <h2>Компании</h2>
                <div class="container mt-5">
                    <div class="row company-list">
                        <p class="">У Вас еще нет компаний</p>
                    </div>

                    <div class="row col-12 mt20">
                        <div class="md-5">
                            <h5>Добавление</h5>
                            <div class="form-group">
                                <label for="company-name-add">Company Name</label>
                                <input type="text" class="form-control" id="company-name-add" name="companyNameAdd" value="Первая">
                            </div>
                            <div class="form-group">
                                <label for="company-email-add">E-mail</label>
                                <input type="text" class="form-control" id="company-email-add" name="companyEmailAdd" value="company1@i.ua">
                            </div>
                            <div class="form-group">
                                <label for="company-phone-add">Phone</label>
                                <input type="text" class="form-control" id="company-phone-add" name="companyPhoneAdd" value="+3804523908">
                            </div>
                            <div class="form-group">
                                <label for="company-description-add">Description</label>
                                <textarea class="form-control" id="company-description-add" name="companyDescriptionAdd">Описание компании</textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onClick="addCompany()">Добавить компанию</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="register<?=$auth?>">
                <h2>Регистрация</h2>
                <form>
                    <label>Логин</label>
                    <input type="text" name="login" placeholder="Введите логин" value="ripa1">
                    <label>Пароль</label>
                    <input type="text" name="password" placeholder="Введите пароль" value="qwerty123">
                    <label>Имя</label>
                    <input type="text" name="first_name" placeholder="Введите свое имя" value="Юрий">
                    <label>Фамилия</label>
                    <input type="text" name="last_name" placeholder="Введите свое фамилию" value="Рипа">
                    <label>Почта</label>
                    <input type="email" name="email" placeholder="Введите свою почту" value="a1@i.ua">
                    <label>Телефон</label>
                    <input type="text" name="phone" placeholder="Введите свой телефон" value="+3804523906">
                    <p class="register-all register-error red hidden"></p>
                    <p class="register-all register-ok green hidden"></p>
                    <button type="button" class="register-btn">Зарегистрироваться</button>
                    <p><a href="#" onClick="$('.register').addClass('hidden');$('.signin').removeClass('hidden');"">Aвторизация</a></p>
                </form>
            </section>

            <section class="signin<?=$guest?>">
                <h2>Авторизация</h2>
                <form>
                    <label>Логин</label>
                    <input type="text" name="login" placeholder="Введите свой логин" value="ripa1">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Введите пароль" value="qwerty123">
                    <p class="signin-all signin-error red hidden"></p>
                    <p class="signup-all signin-ok green hidden"></p>
                    <button type="button" class="signin-btn">Войти</button>
                    <p><a href="#" onClick="$('.signin').addClass('hidden');$('.register').removeClass('hidden');">Регистрация</a></p>
                </form>
            </section>
        </main>

        <script src="/js/jquery-3.4.1.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
