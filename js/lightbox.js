const params = new URLSearchParams(location.search);
var img = '';

function getAndShow(webUrl, thumbUrl, fullsizeUrl, album) {
  img = thumbUrl.substr(thumbUrl.lastIndexOf("/")+1);
  fullsizeEnd = fullsizeUrl.substr(fullsizeUrl.lastIndexOf("/")+1);
  var lightPic = document.getElementById('lightbox-picture');
  // lightPic.style.filter = 'blur(0px)';
  lightPic.setAttribute('src', thumbUrl);
  lightPic.setAttribute('src', webUrl);

  document.getElementById('lightbox-picture').setAttribute('onError', `this.onerror=null;this.src='${fullsizeUrl}';`);
  params.set('photo', img);
  window.history.replaceState({}, '', `${location.pathname}?${params}`);

  document.getElementById('download-link').setAttribute('href', fullsizeUrl);
  document.getElementById('download-link').setAttribute('download', fullsizeUrl);

  document.getElementById('set-as-album-thumb').setAttribute('href', `?action=set_album_thumb&album_name=${album}&photo_name=${fullsizeEnd}`);
  document.getElementById('move-to-trash').setAttribute('href', `?action=move_to_trash&album_name=${album}&photo_name=${fullsizeEnd}`);

  document.getElementById('favorite').setAttribute('href', `?action=favorite&album_name=${album}&photo_name=${img}`);

  document.getElementById('prev-picture').onclick = function() { changePhoto(img, 'prev'); };
  document.getElementById('next-picture').onclick = function() { changePhoto(img, 'next'); };

  document.getElementById('light').style.display = 'block';
  document.getElementById('fade').style.display = 'block';
  // set up last photo view properly
  var nextPhotoEl = document.getElementById(`thumb-${img}`).nextElementSibling;
  var nextPhoto;
  if (nextPhotoEl === null) { // get previous image if no next image
    nextPhotoEl = document.getElementById(`thumb-${img}`).previousElementSibling;
  }
  nextPhoto = nextPhotoEl.id.substr(6);
  document.getElementById('delete-photo').setAttribute('href', `?action=delete_photo&album_name=${album}&photo_name=${fullsizeEnd}&next_photo=${nextPhoto}`);
}

var lightboxClose = document.querySelector('#close-lightbox');
lightboxClose.onclick = function clickLightboxClose () {
   document.getElementById('light').style.display='none';
   document.getElementById('fade').style.display='none';
   params.delete('photo');
   window.history.replaceState({}, '', `${location.pathname}?${params}`);
}

// Rotate and align
// thanks to http://stackoverflow.com/a/18536194/4151489 for the idea
var step = 0;
var rotateLightbox = document.getElementById('rotate-lightbox-img');
rotateLightbox.addEventListener('click', function rotateImg() {
  var rotateMe = document.getElementById('lightbox-picture');
  var curAngle = rotateMe.className;
  step += 1;
  var offset = rotateMe.width - rotateMe.height;
  rotateMe.style.transform = 'translateY('+ offset/2*(step%2) +'px) '+'rotate('+ step*90 +'deg)';
});

document.onkeydown = function(e) {
    e = e || window.event;
    switch(e.which || e.keyCode) {
        case 37: // left
        changePhoto(img, "prev");
        e.preventDefault();
        break;

        case 39: // right
        changePhoto(img, "next");
        e.preventDefault();
        break;

        case 82:// r
        rotateLightbox.click();
        break;

        case 88: // x
        document.getElementById('close-lightbox').click();
        break;

        default: return;
    }
};

function changePhoto(img, way) {
  var nextPhoto;
  if (way == "next") {
    nextPhoto = document.getElementById(`thumb-${img}`).nextElementSibling;
  } else {
    nextPhoto = document.getElementById(`thumb-${img}`).previousElementSibling;
  }
  if (nextPhoto !== null) {
    nextPhoto.click();
  }
}
