// Пользователь
let user = {
    id: null,
    login: '',
    password: '',

    // Получение с устройства
    load: function () {
        let ls = window.localStorage;
        signinUser(ls.getItem('login'), ls.getItem('password'))
    },
    // Сохранение на устройстве
    save: function () {
        let ls = window.localStorage;
        ls.setItem('iser_id', this.id);
        ls.setItem('login', this.login);
        ls.setItem('password', this.password);
    },
}

// Авторизация пользователя
function signinUser(login, password) {
    if (login === '' || password === '') {
        console.log('Не заданы логин и/или пароль');
        //alert('Не заданы логин и/или пароль');
        return false;
    }
    signinAll.addClass('hidden');
    registerAll.addClass('hidden');
    $('input').removeClass('error');

    let formData = new FormData();
    formData.append('login', login);
    formData.append('password', password);

    $.ajax({
        url: 'http://api.phprestapi.loc/user/sign-in',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success (res) {
            if (res.status) {
                user.id       = res.userId;
                user.login    = login;
                user.password = password;
                user.save();
                $('.userlogin').text(login);

                $('.signin').addClass('hidden');
                $('.companies,.logout').removeClass('hidden');

                getCompanies();
            } else {
                signinError.removeClass('hidden').text(res.message);
                if (res.fields) {
                    res.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
            }
        },
        error(res) {
            console.log(res);
            let result = JSON.parse(res.responseText);
            alert(result.message);
        }
    });
}

// Выход
function logoutUser() {
    signinAll.addClass('hidden');
    registerAll.addClass('hidden');
    $('input').removeClass('error');

    $.ajax({
        url: 'http://api.phprestapi.loc/user/logout',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        data: {},
        success (res) {
            if (res.status) {
                user.id       = null;
                user.login    = '';
                user.password = '';
                user.save();

                $('.signin').removeClass('hidden');
                $('.companies,.logout').addClass('hidden');
            } else {
                signinError.removeClass('hidden').text(res.message);
                if (res.fields) {
                    res.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
            }
        },
        error(res) {
            console.log(res);
            let result = JSON.parse(res.responseText);
            alert(result.message);
        }
    });
}

//let selectUserId = null;
//let userList = document.querySelector('.user-list');
//let username = document.querySelector('#usernameadd');
//let usernameupdate = document.querySelector('#usernameupdate');
//
//async function getUsers() {
//    let res = await fetch(`http://api.phprestapi.loc/users`);
//    let items = await res.json();
//    userList.innerHTML = '';
//    items.forEach((item) => {
//        userList.innerHTML += `
//<div class="card" style="">
//    <div class="card-body">
//        <h5 class="card-title">${item.id}</h5>
//        <p class="card-text">${item.username}</p>
//        <a href="#" class="card-link" onClick="getUser(${item.id})">Подробнее</a>
//        <a href="#" class="card-link" onClick="selectUser(${item.id},'${user.username}')">Редактировать</a>
//        <a href="#" class="card-link" onClick="deleteUser(${item.id})">Удалить</a>
//    </div>
//</div>
//`;
//    })
//}
//
//async function getUser(userId) {
//    let res = await fetch(`http://api.phprestapi.loc/users/${userId}`);
//    let item = await res.json();
//    userList.innerHTML = `
//<div class="card" style="">
//    <div class="card-body">
//        <h5 class="card-title">${item.id}</h5>
//        <p class="card-text">${item.username}</p>
//        <a href="#" class="card-link" onClick="getUsers()">Все</a>
//        <a href="#" class="card-link" onClick="selectUser(${item.id},'${item.username}')">Редактировать</a>
//        <a href="#" class="card-link" onClick="deleteUser(${item.id})">Удалить</a>
//    </div>
//</div>
//`;
//}
//
//async function addUser() {
//    let formData = new FormData();
//    formData.append('username', username.value);
//    let res = await fetch(`http://api.phprestapi.loc/users`, {
//        method: 'POST',
//        body: formData
//    });
//    let data = await res.json();
//    if (data.status === true) {
//        await getUsers();
//    }
//}
//
//async function deleteUser(userId) {
//    let res = await fetch(`http://api.phprestapi.loc/users/${userId}`, {
//        method: 'DELETE'
//    });
//    let data = await res.json();
//    if (data.status === true) {
//        await getUsers();
//    }
//}
//
//async function selectUser(userId, userName) {
//    selectUserId = userId;
//    usernameupdate.value = userName;
//}
//
//async function updateUser() {
//    let formData = {
//        'username': usernameupdate.value
//    };
//    let res = await fetch(`http://api.phprestapi.loc/users/${selectUserId}`, {
//        method: 'PATCH',
//        body: JSON.stringify(formData)
//    });
//    let data = await res.json();
//    if (data.status === true) {
//        await getUsers();
//    }
//}

let companyList    = document.querySelector('.company-list');
let inputLogin     = $('input[name="login"]');
let inputPassword  = $('input[name="password"]');
let registerAll    = $('.register-all');
let registerError  = $('.register-error');
let registerOk     = $('.register-ok');

let inputLogin2    = $('input[name="login2"]');
let inputPassword2 = $('input[name="password2"]');
let signinAll      = $('.signin-all');
let signinError    = $('.signin-error');

// Регистрация с формы
$('.register-btn').click(function (e) {
    //console.log('register-btn click');
    e.preventDefault();

    $('input').removeClass('error');
    $('.signup-all,.register-all').addClass('hidden');

    let login = inputLogin.val(),
        password = inputPassword.val(),
        first_name = $('input[name="first_name"]').val(),
        last_name = $('input[name="last_name"]').val(),
        email = $('input[name="email"]').val(),
        phone = $('input[name="phone"]').val();

    if (login === '') {
        inputLogin.addClass('error');
        return false;
    }
    if (password === '') {
        inputPassword.addClass('error');
        return false;
    }

    let formData = new FormData();
    formData.append('login', login);
    formData.append('password', password);
    formData.append('first_name', first_name);
    formData.append('last_name', last_name);
    formData.append('email', email);
    formData.append('phone', phone);

    $.ajax({
        url: 'http://api.phprestapi.loc/user/register',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success (res) {
            //console.log(res);
            if (res.status) {
                registerOk.removeClass('hidden').text(res.message);
            } else {
                registerError.removeClass('hidden').text(res.message);
                if (res.fields) {
                    res.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
            }
        },
        error(res) {
            console.log(res);
            let result = JSON.parse(res.responseText);
            alert(result.message);
        }
    });
});

// Авторизация с формы
$('.signin-btn').click(function (e) {
    //console.log('signin-btn click');
    e.preventDefault();
    signinUser(inputLogin2.val(), inputPassword2.val());
});

// Компании пользователя
function getCompanies() {
    if (!user.id) {
        return false;
    }
    $.ajax({
        url: 'http://api.phprestapi.loc/user/companies',
        type: 'GET',
        cache: false,
        dataType: 'text',
        data: {
            'userId': user.id
        },
        success(res) {
            //console.log(res);
            let result = JSON.parse(res);
            if (result.status) {
                companyList.innerHTML = '';
                result.data.forEach((item) => {
                    companyList.innerHTML += `
<div class="card" style="">
    <div class="card-body">
        <h5 class="card-title">${item.id}</h5>
        <p class="card-text">${item.name}</p>
        <p class="card-text">${item.email}</p>
        <p class="card-text">${item.phone}</p>
        <p class="card-text">${item.description}</p>
    </div>
</div>
`;
                });
            } else {
                console.log(res);
                alert(result.message);
            }
        },
        error(res) {
            console.log(res);
            let result = JSON.parse(res.responseText);
            alert(result.message);
        }
    });
}

// Добавление компании пользователя
function addCompany() {
    let formData = new FormData();
    formData.append('userId', user.id);
    formData.append('name', $('#company-name-add').val());
    formData.append('email', $('#company-email-add').val());
    formData.append('phone', $('#company-phone-add').val());
    formData.append('description', $('#company-description-add').val());

    $.ajax({
        url: 'http://api.phprestapi.loc/user/companies',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success(res) {
            //console.log(res);
            if (res.status) {
                getCompanies();
            } else {
                alert(res.message);
            }
        },
        error(res) {
            console.log(res);
            let result = JSON.parse(res.responseText);
            alert(result.message);
        }
    });
}
