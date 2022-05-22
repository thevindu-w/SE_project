let submitBtn = document.getElementById('submitBtn');
let emailInput = document.getElementById('email');
let passwdInput = document.getElementById('password');
let cnfpasswdInput = document.getElementById('cnfpassword');

function keyPressFn(e, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        if (nxt == '') {
            document.getElementById('submitBtn').click();
        } else {
            let nextElem = document.getElementById(nxt);
            if (nextElem) {
                nextElem.focus();
            }
        }
    }
}

submitBtn.onclick = e => {
    e.preventDefault();
    let email = emailInput.value;
    let passwd = passwdInput.value;
    let cnfpasswd = cnfpasswdInput.value;
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        alert("Invalid email");
        return;
    }
    if (!/^[\x21-\x7E]{8,15}$/.test(passwd)) {
        alert("Invalid password");
        return;
    }
    if (passwd !== cnfpasswd) {
        alert("Passwords doesn't match");
        return;
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", document.URL, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let xhrBuilder = new XHRBuilder();
    xhrBuilder.addField('email', email);
    xhrBuilder.addField('password', passwd);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let data = JSON.parse(xhr.responseText);
                if (!data.hasOwnProperty('success') || data['success'] !== true) {
                    alert('Account creation failed!');
                    return;
                }
                alert('Account created. You will receive the account activation link to your email');
            } catch (error) {
                alert('Error occured!');
            }
        }
    };
    xhr.send(xhrBuilder.build());
}