// Пользователь
let user = {
    id: null,
    login: '',
    password: '',

    // Получение с устройства
    load: function () {
        let ls = window.localStorage;
        let login = ls.getItem('login');
        let password = ls.getItem('password');
        if (!login && !password) {
        } else {
            signinUser(login, password);
        }
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
    //console.log('signinUser login=',login);
    //console.log('signinUser password=',password);

    let fieldErrors = [];
    if (login === '') {
        fieldErrors.push('login2');
    }
    if (password === '') {
        fieldErrors.push('password2');
    }
    if (fieldErrors.length > 0) {
        //console.log('fieldErrors Не заданы логин и/или пароль');
        fieldErrors.forEach(function (field) {
            $(`input[name="${field}"]`).addClass('error');
        });
        signinError.removeClass('hidden');
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
let companyAll      = $('.company-all');

// Регистрация с формы
$('.register-btn').click(function (e) {
    //console.log('register-btn click');
    e.preventDefault();

    $('input').removeClass('error');
    $('.signup-all,.register-all').addClass('hidden');

    let login = validationString(inputLogin.val()),
        password = validationString(inputPassword.val()),
        first_name = validationString($('input[name="first_name"]').val()),
        last_name = validationString($('input[name="last_name"]').val()),
        email = validationString($('input[name="email"]').val()),
        phone = validationString($('input[name="phone"]').val());

    let fieldErrors = [];
    if (login === '') {
        fieldErrors.push('login');
    }
    if (password === '') {
        fieldErrors.push('password');
    }
    if (first_name === '') {
        fieldErrors.push('first_name');
    }
    if (last_name === '') {
        fieldErrors.push('last_name');
    }
    if (email === '') {
        fieldErrors.push('email');
    }
    if (phone === '') {
        fieldErrors.push('phone');
    }
    if (fieldErrors.length > 0) {
        fieldErrors.forEach(function (field) {
            $(`input[name="${field}"]`).addClass('error');
        });
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
                inputLogin2.val(login);
                inputPassword2.val(password);
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
    let login = validationString(inputLogin2.val());
    let password = validationString(inputPassword2.val());

    signinUser(login, password);
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
    companyAll.removeClass('error');
    let name = $('#company-name-add').val();
    if (name === '') {
        $('#company-name-add').addClass('error');
        return false;
    }
    let description = $('#company-description-add').val();
    if (description === '') {
        $('#company-description-add').addClass('error');
        return false;
    }

    let formData = new FormData();
    formData.append('userId', user.id);
    formData.append('name', name);
    formData.append('email', $('#company-email-add').val());
    formData.append('phone', $('#company-phone-add').val());
    formData.append('description', description);

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
                companyAll.val('');
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


function validationString($s) {
    return $s.trim()
             .replace(/<[^>]+>/g, '')
             .replace(/^\s+/g, '')
             .replace(new RegExp('&mdash;', 'g'), '')
             .replace(new RegExp('&ndash;', 'g'), '')
             .replace(new RegExp('&nbsp;', 'g'), '')
             .replace(new RegExp('&rsquo;', 'g'), '')
             .replace(new RegExp('&laquo;', 'g'), '')
             .replace(new RegExp('&raquo;', 'g'), '')
             .replace(new RegExp('&deg;', 'g'), '')
             .replace(new RegExp('&prime;', 'g'), '')
             .replace(/^\s+/, '')
             .replace(/\s+$/, '')
             .trim();
}
