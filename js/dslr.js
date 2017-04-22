// save and load the server upload form (localStorage)
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

function updateNumImagesProgress(albumName, totalNumImages) {
  // var elem = document.getElementById("myBar");
  var message = document.querySelector('.message');
  var numImages = 0;
  var id = setInterval(getNumImagesInAlbum, 500);
  function getNumImagesInAlbum() {
    let random = 'rand=' + new Date().getTime();
    fetch('/photo-gal?action=get_num_images&album='+albumName+'&'+random, {
      method: 'get'
    }).then(function(response) {
      if(response && response.ok) {
        // If result was ok save it to cache
        cache.put(event.request, response.clone());
        console.log(response.body);
        numImages = response.body['num_images'];
        console.log('IMAGES:',numImages);
      }
      if (numImages >= totalNumImages) {
        clearInterval(id);
      } else {
        message.textContent = 'Downloading '+numImages+'/'+totalNumImages+' from '+albumName;
        // width++;
        // elem.style.width = width + '%';
        // elem.innerHTML = width * 1  + '%';
      }
    }).catch(function(err) {
      console.error(err);
    });
  }
}
