let submitBtn = document.getElementById('submitBtn');
let emailInput = document.getElementById('email');
let passwdInput = document.getElementById('password');
let cnfpasswdInput = document.getElementById('cnfpassword');
let msgDiv = document.getElementById('msgDiv');

/**
 * Prevents a form from submittiog when enter key is pressed. Instead,
 * it sets the keyboard focus to next form input field.
 * If the field is the last input field of the form, triggers the onclick
 * of the submit button.
 */
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

emailInput.onkeydown = event => {
    keyPressFn(event, 'password');
}
passwdInput.onkeydown = event => {
    keyPressFn(event, 'cnfpassword');
}
cnfpasswdInput.onkeydown = event => {
    keyPressFn(event, '');
}

/**
 * Shows a success or error message to user.
 * 
 * If success is true, shows a success message. Otherwise, shows an error message
 */
function showMsg(msg, success = false) {
    msgDiv.classList.remove('wrapper-error');
    msgDiv.classList.remove('wrapper-success');
    msgDiv.hidden = false;
    msgDiv.innerText = msg;
    if (success) {
        msgDiv.classList.add('wrapper-success');
    } else {
        msgDiv.classList.add('wrapper-error');
    }
    document.body.scrollTop = document.documentElement.scrollTop = 0;
}

/**
 * Check if a string is empty
 * 
 * Returns true if string is empty, and false otherwise.
 */
function isEmpty(str) {
    return (!str || str.length === 0);
}

submitBtn.onclick = e => {
    e.preventDefault();
    let email = emailInput.value;
    let passwd = passwdInput.value;
    let cnfpasswd = cnfpasswdInput.value;
    if (isEmpty(email) || isEmpty(passwd) || isEmpty(cnfpasswd)) {
        showMsg("Some fileds are empty");
        return;
    }
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        showMsg("Invalid email");
        return;
    }
    if (!/^[\x21-\x7E]{8,15}$/.test(passwd)) {
        showMsg("Invalid password");
        return;
    }
    if (passwd !== cnfpasswd) {
        showMsg("Passwords doesn't match");
        return;
    }
    let xhrSender = new XHRSender();
    xhrSender.addField('email', email);
    xhrSender.addField('password', passwd);
    xhrSender.send(document.URL, function (xhr) {
        try {
            let data = JSON.parse(xhr.responseText);
            if (!data.hasOwnProperty('success') || data['success'] !== true) {
                if (data.hasOwnProperty('reason') && typeof (data['reason']) === "string") {
                    showMsg(data['reason']);
                } else {
                    showMsg('Account creation failed!');
                }
                return;
            }
            showMsg('Account created. You will receive an account activation link to your email', true);
        } catch (error) {
            showMsg('Something went wrong! Please try again.');
        }
    });
}