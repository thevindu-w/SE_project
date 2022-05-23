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