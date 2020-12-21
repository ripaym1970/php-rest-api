
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

// Авторизация
let userId = null;
let companyList = document.querySelector('.company-list');
let login       = $('input[name="login"]');
let password    = $('input[name="password"]');
let signinAll   = $('.signin-all');
let signinError = $('.signin-error');
let registerError = $('.register-error');
let registerOk    = $('.register-ok');

// Регистрация
$('.register-btn').click(function (e) {
    //console.log('register-btn click');
    e.preventDefault();

    $(`input`).removeClass('error');
    $(`.signup-all`).addClass('hidden');

    let login = $('input[name="login"]').val(),
        password = $('input[name="password"]').val(),
        first_name = $('input[name="first_name"]').val(),
        last_name = $('input[name="last_name"]').val(),
        email = $('input[name="email"]').val(),
        phone = $('input[name="phone"]').val();

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
                console.log(registerOk);
                registerOk.removeClass('hidden').text(res.message);
            } else {
                console.log(res);
                registerError.removeClass('hidden').text(res.message);
                if (res.fields) {
                    res.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
            }
        }
    });
});

// Компании пользователя
function getCompanies() {
    $.ajax({
        url: 'http://api.phprestapi.loc/user/companies',
        type: 'GET',
        cache: false,
        dataType: 'text',
        data: {
            'userId': userId
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
        }
    });
}

// Авторизация
$('.signin-btn').click(function () {
    signinAll.addClass('hidden');
    $(`input`).removeClass('error');

    let formData = new FormData();
    formData.append('login', login.val());
    formData.append('password', password.val());

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
                $('.signin').addClass('hidden');
                $('.companies,.logout').removeClass('hidden');
                $('.userlogin').text(res.userLogin);
                userId = res.userId;
                getCompanies();
            } else {
                signinError.removeClass('hidden').text(data.message);
                if (res.fields) {
                    res.fields.forEach(function (field) {
                        $(`input[name="${field}"]`).addClass('error');
                    });
                }
            }
        }
    });
});

// Добавление компании
function addCompany() {
    let formData = new FormData();
    formData.append('user_id', userId);
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
        }
    });
}
