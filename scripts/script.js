class TextBuilder {
    constructor(text) {
        this.goodArr = [];
        this.badArr = [];
        this.text = text;
        this.lastpos = 0;
    }

    addError(offset, length) {
        this.goodArr.push(this.text.substring(this.lastpos, offset));
        this.badArr.push(this.text.substring(offset, offset + length));
        this.lastpos = offset + length;
    }

    build() {
        this.goodArr.push(this.text.substring(this.lastpos));
        let editDiv = document.createElement('div');
        for (let i = 0; i < this.badArr.length; i++) {
            let gspan = document.createElement('span');
            gspan.innerText = this.goodArr[i];
            editDiv.appendChild(gspan);
            let bspan = document.createElement('span');
            bspan.innerText = this.badArr[i];
            bspan.id = "bspan" + i;
            bspan.onclick = e => {
                document.getElementById('error' + i).classList.add('red');
            }
            bspan.classList.add('red');
            editDiv.appendChild(bspan);
        }
        let gspan = document.createElement('span');
        gspan.innerText = this.goodArr[this.goodArr.length - 1];
        editDiv.appendChild(gspan);
        return editDiv;
    }
}

class ErrorDivCreator {
    constructor(text) {
        this.text = text;
        this.index = 0;
    }

    createDiv(offset, length, options, reason = '') {
        let errTxt = this.text.substring(offset, offset + length);
        let errDiv = document.createElement('div');
        errDiv.id = 'err' + this.index;
        let wrapDiv = document.createElement('div');
        wrapDiv.classList.add('wrapper');
        errDiv.appendChild(wrapDiv);
        let errSpan = document.createElement('span');
        errSpan.classList.add('errspan');
        errSpan.innerText = errTxt;
        wrapDiv.appendChild(errSpan);
        let spaceSpan = document.createElement('span');
        spaceSpan.innerText = ' : ';
        wrapDiv.appendChild(spaceSpan);
        if (options && Array.isArray(options) && options.length > 0) {
            let sel = document.createElement('select');
            sel.classList.add('dropdown');
            wrapDiv.appendChild(sel);
            {
                let opt = document.createElement('option');
                opt.value = errTxt;
                opt.innerText = 'select...';
                sel.appendChild(opt);
            }
            options.forEach(option => {
                let opt = document.createElement('option');
                opt.value = option;
                opt.innerText = option;
                sel.appendChild(opt);
            });
            let index = this.index;
            let originalTxt = errTxt;
            sel.onchange = function () {
                let bspan = document.getElementById('bspan' + index);
                bspan.innerText = sel.value;
                if (sel.value == originalTxt) {
                    bspan.classList.remove('green');
                    bspan.classList.add('red');
                } else {
                    bspan.classList.remove('red');
                    bspan.classList.add('green');
                }
            };
        }
        wrapDiv.appendChild(document.createElement('br'));
        wrapDiv.appendChild(document.createElement('br'));
        if (reason) {
            let reasonSpan = document.createElement('span');
            reasonSpan.classList.add('rsnspan');
            reasonSpan.innerText = reason;
            wrapDiv.appendChild(reasonSpan);
        }
        this.index++;
        return errDiv;
    }
}

const txtdiv = document.getElementById('txtdiv');
const langSelect = document.getElementById('lang');

document.getElementById('sendbtn').onclick = e => {
    e.preventDefault();

    const errdiv = document.getElementById('errors');
    const noErrdiv = document.getElementById('msgDiv');
    noErrdiv.hidden = true;
    let text = txtdiv.innerText.trim();
    let lang = langSelect.value;

    if (!text) return;

    // clean errors div
    let chld = errdiv.lastElementChild;
    while (chld) {
        errdiv.removeChild(chld);
        chld = errdiv.lastElementChild;
    }

    let xhrSender = new XHRSender();
    xhrSender.addField('lang', lang);
    xhrSender.addField('text', text);
    xhrSender.send(document.URL, function (xhr) {
        try {
            let data = JSON.parse(xhr.responseText);
            let textBuilder = new TextBuilder(text);
            let errDivCreator = new ErrorDivCreator(text);
            if (data.length == 0) {
                noErrdiv.hidden = false;
            } else {
                data.forEach(err_info => {
                    textBuilder.addError(err_info.offset, err_info.length);
                    let offset = err_info.offset;
                    let length = err_info.length;
                    let options = err_info.hasOwnProperty('correct') ? err_info.correct : [];
                    let reason = err_info.hasOwnProperty('description') ? err_info.description : undefined;
                    let diverr = errDivCreator.createDiv(offset, length, options, reason);
                    errdiv.appendChild(diverr);
                });
                let newText = textBuilder.build();
                txtdiv.innerHTML = "";
                txtdiv.appendChild(newText);
            }
        } catch (error) {
            console.log(error);
        }
    });
}

document.getElementById('imgbtn').onclick = e => {
    e.preventDefault();
    let lang = langSelect.value;
    let file = document.getElementById("fileToUpload").files[0]
    if (!file) return;
    let formData = new FormData();
    formData.append("lang", lang);
    formData.append("fileToUpload", file);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/image.php");
    xhr.onreadystatechange = async function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let data = JSON.parse(this.response);
                if (!data.hasOwnProperty('success') || data['success'] !== true || !data.hasOwnProperty('text')) {
                    let errMsg = 'text extraction failed';
                    if (data.hasOwnProperty('reason') || data['reason']){
                        errMsg = data['reason'];
                    }
                    console.log(errMsg);
                    return;
                }
                document.getElementById("txtdiv").innerText = data['text'];
            } catch (error) {
                console.log(error);
            }
        }
    };
    xhr.send(formData);
};

let aud = null;

let toggle = b => {
    let speakBtn = document.getElementById('speakbtn');
    let stopBtn = document.getElementById('stopbtn');
    if (b) {
        speakBtn.hidden = true;
        stopBtn.hidden = false;
    } else {
        stopBtn.hidden = true;
        speakBtn.hidden = false;
    }
}

document.getElementById('speakbtn').onclick = e => {
    e.preventDefault();
    let lang = langSelect.value;
    let text = txtdiv.innerText.trim();

    if (!text) return;

    let xhrSender = new XHRSender();
    xhrSender.addField('lang', lang);
    xhrSender.addField('text', text);
    xhrSender.send("/speak.php", async function (xhr) {
        try {
            let cont_type = xhr.getResponseHeader('Content-Type');
            if (cont_type === 'audio/mpeg' || cont_type === 'audio/x-wav') {
                let blob = new Blob([xhr.response], {
                    type: cont_type
                });
                if (aud != null) {
                    aud.pause();
                    aud.remove();
                }
                aud = document.createElement("audio");
                let url = window.URL.createObjectURL(blob);
                aud.src = url;
                aud.onload = evt => {
                    URL.revokeObjectURL(url);
                };
                aud.onended = evt => {
                    toggle(false);
                    aud.remove();
                }
                aud.play();
                toggle(true);
            } else if (cont_type === 'application/json') {
                let blob = new Blob([xhr.response], { type: 'application/json' });
                const jsonData = await (new Response(blob)).text();
                let data = JSON.parse(jsonData);
                if (data.hasOwnProperty('reason') && data['reason'] && typeof data['reason'] === 'string') {
                    console.log(data['reason']);
                    return;
                }
            } else {
                console.log("error");
            }
        } catch (error) {
            console.log(error);
        }
    }, 'blob');
};

document.getElementById('stopbtn').onclick = e => {
    e.preventDefault();
    if (aud != null) {
        aud.pause();
        aud.remove();
        toggle(false);
    }
};

document.getElementById('copybtn').onclick = e => {
    e.preventDefault();
    let text = txtdiv.innerText.trim();
    if (!text) return;

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).catch(reason => { console.log("You should allow clipboard access"); });
    } else {
        console.log("navigator.clipboard.writeText is false or not available");
    }
};

// When the user clicks on div, open the popup
document.getElementById('downbtn').onclick = e => {
    e.preventDefault();
    e.stopImmediatePropagation(); // prevents document.onclick()
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
};

document.onclick = e => {
    let popup = document.getElementById("myPopup");
    popup.classList.remove("show");
}

document.getElementById('textbtn').onclick = e => {
    e.preventDefault();
    let text = txtdiv.innerText.trim();
    if (!text) return;

    let element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', 'multigrammar.txt');
    element.click();
}

document.getElementById('pdfbtn').onclick = e => {
    e.preventDefault();
    let text = txtdiv.innerText.trim();
    if (!text) return;

    let xhrSender = new XHRSender();
    xhrSender.addField('text', text);
    xhrSender.send("/pdf.php", function (xhr) {
        let cont_type = xhr.getResponseHeader('Content-Type');
        if (cont_type === 'application/pdf') {
            let blob = new Blob([xhr.response], { type: 'application/pdf' });
            let a = document.createElement("a");
            a.target = '_blank';
            let url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = 'multigrammar.pdf';
            a.click();
            window.URL.revokeObjectURL(url);
        } else {
            console.log('PDF download error');
        }
    }, 'blob');
}
