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
        errDiv.id = 'err' + this.index++;
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
        if (options) {
            let sel = document.createElement('select');
            sel.classList.add('dropdown');
            wrapDiv.appendChild(sel);
            options.forEach(option => {
                let opt = document.createElement('option');
                opt.value = option;
                opt.innerText = option;
                sel.appendChild(opt);
            });
        }
        wrapDiv.appendChild(document.createElement('br'));
        wrapDiv.appendChild(document.createElement('br'));
        if (reason) {
            let reasonSpan = document.createElement('span');
            reasonSpan.classList.add('rsnspan');
            reasonSpan.innerText = reason;
            wrapDiv.appendChild(reasonSpan);
        }
        return errDiv;
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

document.getElementById('sendbtn').onclick = e => {
    e.preventDefault();

    let txtdiv = document.getElementById('txtdiv');
    let errdiv = document.getElementById('errors');
    let text = txtdiv.innerText;//.replace('\n', ' ');
    let lang = document.getElementById('lang').value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", document.URL, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let xhrBuilder = new XHRBuilder();
    xhrBuilder.addField('lang', lang);
    xhrBuilder.addField('text', text);
    xhr.onreadystatechange = () => {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let data = JSON.parse(xhr.responseText);
                let textBuilder = new TextBuilder(text);
                let errDivCreator = new ErrorDivCreator(text);
                { // clean errors div
                    let chld = errdiv.lastElementChild;
                    while (chld){
                        errdiv.removeChild(chld);
                        chld = errdiv.lastElementChild;
                    }
                }
                //let i = 0;
                data.forEach(err_info => {
                    textBuilder.addError(err_info.offset, err_info.length);
                    let diverr = errDivCreator.createDiv(err_info.offset, err_info.length, ['abc', 'koefrk'], "Your grammar is wrong");
                    /*diverr.innerText = 'error: ' + err_info.offset + ' ' + err_info.length;
                    let iter = i;
                    diverr.onclick = e => {
                        let bspan = document.getElementById('bspan' + iter);
                        bspan.classList.add('big');
                    };*/
                    errdiv.appendChild(diverr);
                    //diverr.id = 'error' + i++;
                });
                let newText = textBuilder.build();
                txtdiv.innerHTML = "";
                txtdiv.appendChild(newText);
            } catch (error) {
                console.log(error);
            }
        }
    };
    xhr.send(xhrBuilder.build());
}

document.getElementById('imgbtn').onclick = e => {
    e.preventDefault();
    let lang = document.getElementById('lang').value;
    let formData = new FormData();
    formData.append("lang", lang);
    formData.append("fileToUpload", document.getElementById("fileToUpload").files[0]);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/image.php");
    xhr.onreadystatechange = async function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let data = JSON.parse(this.response);
                if (!data.hasOwnProperty('success') || data['success'] !== true || !data.hasOwnProperty('text')) {
                    console.log('text extraction failed');
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
document.getElementById('speakbtn').onclick = e => {
    e.preventDefault();
    let builder = new XHRBuilder();
    builder.addField('text', document.getElementById("txtdiv").innerText);
    builder.addField('lang', "en-US");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/speak.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.responseType = 'blob';
    xhr.onreadystatechange = async function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            let cont_type = xhr.getResponseHeader('Content-Type');
            if (cont_type === 'audio/mpeg') {
                let blob = new Blob([this.response], {
                    type: 'audio/mpeg'
                });
                let aud = document.createElement("audio");
                aud.style = "display: none";
                document.body.appendChild(aud);
                let url = window.URL.createObjectURL(blob);
                aud.src = url;
                aud.onload = evt => {
                    URL.revokeObjectURL(url);
                };
                aud.onended = evt => {
                    document.body.removeChild(aud);
                }
                aud.play();
            } else {
                console.log("error");
            }
        }
    };
    xhr.send(builder.build());
};