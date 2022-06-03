class XHRSender {
    constructor() {
        this.fields = {};
    }

    addField(fieldName, value) {
        this.fields[fieldName] = value;
    }

    send(url, callback, responseType = '') {
        let encoded = Object.keys(this.fields).map((index) => {
            return encodeURIComponent(index) + '=' + encodeURIComponent(this.fields[index]);
        });
        let reqBody = encoded.join("&");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.responseType = responseType;
        xhr.onload = () => {
            if (xhr.status == 200) {
                var unauthorized = xhr.getResponseHeader("Unauthorized");
                if (unauthorized && unauthorized !== "") {
                    window.location = unauthorized;
                    return;
                }
                callback(xhr);
            }
        };
        xhr.send(reqBody);
    }
}