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
    xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let data = JSON.parse(xhr.responseText);
                let textBuilder = new TextBuilder(text);
                let i = 0;
                data.forEach(err_info => {
                    textBuilder.addError(err_info.offset, err_info.length);
                    let diverr = document.createElement('div');
                    diverr.innerText = 'error: '+ err_info.offset + ' ' + err_info.length;
                    let iter = i;
                    diverr.onclick = e =>{
                        let bspan = document.getElementById('bspan' + iter);
                        bspan.classList.add('big');
                    };
                    errdiv.appendChild(diverr);
                    diverr.id = 'error' + i++;
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
    let formData = new FormData();
    formData.append("fileToUpload", document.getElementById("fileToUpload").files[0]);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/image.php");
    xhr.onreadystatechange = async function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            document.getElementById("txtdiv").innerText = this.response;
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
    xhr.onreadystatechange = async function() {
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