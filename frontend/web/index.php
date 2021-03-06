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
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body onload="user.load()">
        <header class="header">
            <div class="container">
                <div class="header-wrap">
                    <div class="header-item header-item-left">
                        <div class="header-menu">
                            <a href="/">Тестовое задание</a>
                            <a href="#" class="logout hidden" onClick="logoutUser()">Выход (<span class="userlogin"></span>)</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <h1 class="mb30">Тестовое задание</h1>

            <section class="users hidden">
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

            <section class="companies hidden">
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
                                <input type="text" class="form-control company-all" id="company-name-add" name="companyNameAdd" value="">
                            </div>
                            <div class="form-group">
                                <label for="company-email-add">E-mail</label>
                                <input type="text" class="form-control company-all" id="company-email-add" name="companyEmailAdd" value="">
                            </div>
                            <div class="form-group">
                                <label for="company-phone-add">Phone</label>
                                <input type="text" class="form-control company-all" id="company-phone-add" name="companyPhoneAdd" value="">
                            </div>
                            <div class="form-group">
                                <label for="company-description-add">Description</label>
                                <textarea class="form-control company-all" id="company-description-add" name="companyDescriptionAdd"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onClick="addCompany()">Добавить компанию</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="register hidden">
                <h2>Регистрация</h2>
                <form>
                    <label for="login">Логин</label>
                    <input id="login" type="text" name="login" placeholder="Введите логин" value="" autocomplete="off">
                    <label for="password">Пароль</label>
                    <input id="password" type="password" name="password" placeholder="Введите пароль" value="" autocomplete="off">
                    <label for="first_name">Имя</label>
                    <input id="first_name" type="text" name="first_name" placeholder="Введите свое имя" value="" autocomplete="off">
                    <label for="last_name">Фамилия</label>
                    <input id="last_name" type="text" name="last_name" placeholder="Введите свое фамилию" value="" autocomplete="off">
                    <label for="email">Почта</label>
                    <input id="email" type="email" name="email" placeholder="Введите свою почту" value="" autocomplete="off">
                    <label for="phone">Телефон</label>
                    <input id="phone" type="tel" name="phone" placeholder="Введите свой телефон" value="" pattern="/^[+0-9]+$/"  maxlength="14" autocomplete="off">
                    <p class="register-all register-error red hidden"></p>
                    <p class="register-all register-ok green hidden"></p>
                    <button type="button" class="register-btn">Зарегистрироваться</button>
                    <p><a href="#" onClick="$('.register').addClass('hidden');$('.signin').removeClass('hidden');"">Aвторизация</a></p>
                </form>
            </section>

            <section class="signin">
                <h2>Авторизация</h2>
                <form>
                    <label for="login2">Логин</label>
                    <input id="login2" type="text" name="login2" placeholder="Введите свой логин" value="" autocomplete="off">
                    <label for="password2">Пароль</label>
                    <input id="password2" type="password" name="password2" placeholder="Введите пароль" value="" autocomplete="off">
                    <p class="signin-all signin-error red hidden">Не заданы логин и/или пароль</p>
                    <p class="signup-all signin-ok green hidden"></p>
                    <button type="button" class="signin-btn">Войти</button>
                    <p><a href="#" onClick="$('.signin').addClass('hidden');$('.register').removeClass('hidden');">Регистрация</a></p>
                </form>
            </section>
        </main>

        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
