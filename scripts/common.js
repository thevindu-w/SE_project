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