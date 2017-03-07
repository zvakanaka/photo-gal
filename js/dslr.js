// so far this file saves and loads the server upload form
let uploadForm = document.querySelector('#upload_form');
let saveUploadFormButton = uploadForm.querySelector('.save_button');
let serverName = uploadForm.querySelector('#server_name');
let username = uploadForm.querySelector('#username');
let port = uploadForm.querySelector('#port');
saveUploadFormButton.addEventListener('click', e => {
  localStorage.setItem('serverName', serverName.value);
  localStorage.setItem('username', username.value);
  localStorage.setItem('port', port.value);
});

let storedServerName = localStorage.getItem('serverName');
let storedUsername = localStorage.getItem('username');
let storedPort = localStorage.getItem('port');
serverName.value = storedServerName;
username.value = storedUsername;
port.value = storedPort;
