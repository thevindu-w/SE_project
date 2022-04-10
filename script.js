class XHRBuilder {
    constructor() {
        this.fields = {};
    }

    addField(fieldName, value) {
        this.fields[fieldName] = value;
    }

    build() {
        let encoded = Object.keys(this.fields).map((index) => {
            return encodeURIComponent(index) + '=' + encodeURIComponent(this.fields[index]);
        });
        return encoded.join("&");
    }
}

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
            bspan.classList.add('red');
            editDiv.appendChild(bspan);
        }
        let gspan = document.createElement('span');
        gspan.innerText = this.goodArr[this.goodArr.length-1];
        editDiv.appendChild(gspan);
        return editDiv;
    }
}

document.getElementById('sendbtn').onclick = e => {
    e.preventDefault();

    let txtdiv = document.getElementById('txtdiv');
    let text = txtdiv.innerText;//.replace('\n', ' ');
    let lang = document.getElementById('lang').value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", document.URL, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let xhrBuilder = new XHRBuilder();
    xhrBuilder.addField('lang', lang);
    xhrBuilder.addField('text', text);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let data = JSON.parse(xhr.responseText);
                let textBuilder = new TextBuilder(text);
                data.forEach(err_info => {
                    textBuilder.addError(err_info.offset, err_info.length);
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