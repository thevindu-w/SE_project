let emailInput = document.getElementById('email');
let passwdInput = document.getElementById('password');

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
    keyPressFn(event, '');
}