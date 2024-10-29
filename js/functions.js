const bodyLogin = document.querySelector('#login-body');

bodyLogin.classList.remove('opacity0');
bodyLogin.classList.add('opacity1');

function layoutInit() {
    const email = document.querySelector('#eml');
    email.classList.add('layout-active');
}

setTimeout(layoutInit, 1000);

document.querySelector('#btn-next-pss').onclick = function () {
    const emailUser = document.querySelector('#usr').value;
    const alertEmailLogin = document.querySelector('#alert-eml-login');
    const spanEmail = document.querySelector('#span-eml');
    const emailValid = validateEmail(emailUser);

    if (!emailValid) {
        alertEmailLogin.innerHTML = '<p style="color: red;">Escribe una dirección de correo electrónico.</p>';
        alertEmailLogin.style.display = "block";
    } else {
        alertEmailLogin.style.display = "none";
        spanEmail.innerHTML = emailUser;
        nextLayout('#eml', '#pss');
    }
}

document.querySelector('#btn-prev').onclick = function () {
    prevLayout('#pss', '#eml');
}

function nextLayout(parent, next) {
    const parentDiv = document.querySelector(parent);
    const nextDiv = document.querySelector(next);

    parentDiv.classList.remove('layout-left', 'layout-right', 'layout-active');
    nextDiv.classList.remove('layout-left', 'layout-right', 'layout-active');

    parentDiv.classList.toggle('layout-left');
    nextDiv.classList.toggle('layout-active');
}

function prevLayout(parent, prev) {
    const parentDiv = document.querySelector(parent);
    const prevDiv = document.querySelector(prev);

    parentDiv.classList.remove('layout-left', 'layout-right', 'layout-active');
    prevDiv.classList.remove('layout-left', 'layout-right', 'layout-active');

    parentDiv.classList.toggle('layout-right');
    prevDiv.classList.toggle('layout-active');
}

function validateEmail(email) {
    const stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9-]{2,4})+$/);
    return stringEmail.test(email);
}

function cancelEnter(e) {
    key = (document.querySelectorAll) ? e.keyCode : e.which;
    return (key != 13);
}