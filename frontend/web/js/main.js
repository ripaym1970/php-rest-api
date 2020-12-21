let selectUserId = null;
let list = document.querySelector('.list');
//console.log('list=',list);
let username = document.querySelector('#usernameadd');
//console.log('username=',username);
let usernameupdate = document.querySelector('#usernameupdate');
//console.log('usernameupdate=',usernameupdate);

async function getUsers() {
    let res = await fetch(`http://api.phprestapi.loc/users`);
    let users = await res.json();
    list.innerHTML = '';
    users.forEach((user) => {
        list.innerHTML += `
<div class="card" style="">
    <div class="card-body">
        <h5 class="card-title">${user.id}</h5>
        <p class="card-text">${user.username}</p>
        <a href="#" class="card-link" onClick="getUser(${user.id})">Подробнее</a>
        <a href="#" class="card-link" onClick="selectUser(${user.id},'${user.username}')">Редактировать</a>
        <a href="#" class="card-link" onClick="deleteUser(${user.id})">Удалить</a>
    </div>
</div>
`;
    })
}

async function getUser(userId) {
    let res = await fetch(`http://api.phprestapi.loc/users/${userId}`);
    let user = await res.json();
    list.innerHTML = `
<div class="card" style="">
    <div class="card-body">
        <h5 class="card-title">${user.id}</h5>
        <p class="card-text">${user.username}</p>
        <a href="#" class="card-link" onClick="getUsers()">Все</a>
        <a href="#" class="card-link" onClick="selectUser(${user.id},'${user.username}')">Редактировать</a>
        <a href="#" class="card-link" onClick="deleteUser(${user.id})">Удалить</a>
    </div>
</div>
`;
}

async function addUser() {
    let formData = new FormData();
    formData.append('username', username.value);
    let res = await fetch(`http://api.phprestapi.loc/users`, {
        method: 'POST',
        body: formData
    });
    let data = await res.json();
    if (data.status === true) {
        await getUsers();
    }
}

async function deleteUser(userId) {
    let res = await fetch(`http://api.phprestapi.loc/users/${userId}`, {
        method: 'DELETE'
    });
    let data = await res.json();
    if (data.status === true) {
        await getUsers();
    }
}

async function selectUser(userId, userName) {
    selectUserId = userId;
    usernameupdate.value = userName;
}

async function updateUser() {
    let formData = {
        'username': usernameupdate.value
    };
    let res = await fetch(`http://api.phprestapi.loc/users/${selectUserId}`, {
        method: 'PATCH',
        body: JSON.stringify(formData)
    });
    let data = await res.json();
    if (data.status === true) {
        await getUsers();
    }
}


// Авторизация
let login    = $('input[name="login"]');
let password = $('input[name="password"]');
let loginMsg = $('.login-msg');

$('.login-btn').click(function (e) {
    console.log('login-btn click');
    loginUser();
});

async function loginUser() {
    console.log('loginUser()');
    loginMsg.addClass('hidden');
    $(`input`).removeClass('error');

    let formData = new FormData();
    formData.append('login', login.value);
    formData.append('password', password.value);
    let res = await fetch(`http://api.phprestapi.loc/user/sign-in`, {
        method: 'POST',
        body: formData
    });
    let data = await res.json();
    if (data.status === true) {
        await $('.login').addClass('hidden');
        await $('.users').removeClass('hidden');
        await getUsers();
    } else {
        await data.fields.forEach(function (field) {
            $(`input[name="${field}"]`).addClass('error');
        });
        await loginMsg.removeClass('hidden').text(data.message);
    }
}

getUsers();
