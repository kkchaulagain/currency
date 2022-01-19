let form = document.getElementById('mainForm');

let responseDom = document.getElementById('responseDiv');
form.addEventListener('submit', function (e) {
    e.preventDefault();
    const data = serializeForm(this);

    req('./index.php', data.action, JSON.stringify(data))
        .then(function (response) {
            responseDom.innerHTML = response;
        }).catch(function (error) {
            responseDom.innerHTML = JSON.stringify(error);
        });

});

//http://localhost:9091/update?action=post&curr=JPY

var serializeForm = function (form) {
    var obj = {};
    var formData = new FormData(form);
    for (var key of formData.keys()) {
        obj[key] = formData.get(key);
    }
    return obj;
};

const req = function (url, method, data) {
    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, url);
        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };
        xhr.send(data);
    });
}