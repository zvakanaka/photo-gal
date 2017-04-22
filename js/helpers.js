
function sendJSON(method, url, data, sessionid) {
  return new Promise(function(resolve, reject) {
    var http = new XMLHttpRequest();
    http.open(method, url, true);
    http.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    if (sessionid) {
      http.setRequestHeader('Authorization', 'Bearer '+sessionid);
    }
    http.onreadystatechange = function() {
      if (Number(http.readyState) === 4 && Number(http.status) === 200) {
        //response
        var resData = (http.responseText);
        resolve(JSON.parse(resData));
      } else if (Number(http.status) < 200 || Number(http.status) >= 300) {
        reject(new Error(http.status+' data: '+data));
      }
    };
    if (typeof data === 'string' || data instanceof String) {
      http.send(data);
    } else if (!data) {
      http.send();
    } else {
      http.send(JSON.stringify(data));
    }
  });
}
function getJSON(url, sessionid) {
  return sendJSON('GET', url, null, sessionid);
}
function deleteJSON(url, sessionid) {
  return sendJSON('DELETE', url, null, sessionid);
}

// Probably the coolest form validation turorial atround: https://bitsofco.de/form-validation-techniques/
// input.validity = {
//     valid:false // If the input is valid
//     customError:false // If a custom error message has been set
//     patternMismatch:false // If the invalidity is against the pattern attribute
//     rangeOverflow:false // If the invalidity is against the max attribute
//     rangeUnderflow:true // If the invalidity is against the min attribute
//     stepMismatch:true // If the invalidity is against the step attribute
//     tooLong:false // If the invalidity is against the maxlength attribute
//     tooShort:false // If the invalidity is against the minlength attribute
//     typeMismatch:false // If the invalidity is against the type attribute
//     valueMissing:false // If the input is required but empty
// }
function CustomValidation() { }
CustomValidation.prototype = {
  invalidities: [],

  checkValidity: function(input) {
    var validity = input.validity;

    if (validity.patternMismatch) {
      this.addInvalidity('This is the wrong pattern for this field');
    }
    if (validity.rangeOverflow) {
      var max = getAttributeValue(input, 'max');
      this.addInvalidity('The maximum value should be ' + max);
    }
    if (validity.rangeUnderflow) {
      var min = getAttributeValue(input, 'min');
      this.addInvalidity('The minimum value should be ' + min);
    }
    if (validity.stepMismatch) {
      var step = getAttributeValue(input, 'step');
      this.addInvalidity('This number needs to be a multiple of ' + step);
    }
    if (validity.valueMissing) {
      this.addInvalidity('This field cannnot be empty');
    }
  },

  addInvalidity: function(message) {
    this.invalidities.push(message);
  },

  getInvalidities: function() {
    return this.invalidities.join('\n');
  },

  getInvaliditiesForHTML: function() {
    return this.invalidities.join('<br>');
  }
};

// use: see ValidateInputs.md
function validateInputs(inputs) {
  var hakunaMatata = true;
  for (var i = 0; i < inputs.length; i++) {
    var input = inputs[i];
    // Use native JavaScript checkValidity() function to check if input is valid
    if (!input.checkValidity()) {
      var inputCustomValidation = new CustomValidation(); // New instance of CustomValidation
      inputCustomValidation.invalidities = [];
      inputCustomValidation.checkValidity(input); // Check Invalidities
      var customValidityMessage = inputCustomValidation.getInvalidities(); // Get custom invalidity messages
      input.setCustomValidity(customValidityMessage); // set as custom validity message

      // display errors in html
      var customValidityMessageForHTML = inputCustomValidation.getInvaliditiesForHTML();
      input.parentElement.querySelector('.input-error-message').innerHTML = customValidityMessageForHTML;
      hakunaMatata = input.checkValidity();
    }
  }
  return hakunaMatata;
}

function CustomURLSearchParams() {
  // this.url = url;
}
CustomURLSearchParams.prototype = {

  get: function(paramName) {
    if (!this.url) {
      this.url = window.location.href;// eslint-disable-line
    }
    paramName = paramName.replace(/[\[\]]/g, "\\$&");// eslint-disable-line
    var regex = new RegExp("[?&]" + paramName + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(this.url);
    if (!results) {
      return null;
    }
    if (!results[2]) {
      return '';
    }
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }
};
/*********** End Helpers ***********/
let downloadBtn = document.querySelector('#download');
downloadBtn.addEventListener('click', function processDownloadForm() {
  var progressBarProgress = document.querySelector("div.nice.progress-bar-progress");
  progressBarProgress.parentNode.classList.add('hidden');
  document.querySelector('#progress-spacer').classList.add('hidden');
  let downloadInputs = document.querySelectorAll('div#download-album input');
  let hakunaMatata = validateInputs(downloadInputs);
  if (!hakunaMatata) return;

  let albumName = document.querySelector('#new-album-name').value;
  progressBarProgress.parentNode.classList.remove('hidden');
  document.querySelector('#progress-spacer').classList.remove('hidden');
  let interval = {id:0};
  var width = 0;
  var numImages = 0;
  var totalNumImages = 0;
  let random = 'time=' + new Date().getTime();
  getJSON('/photo-gal?action=num_images_on_camera&'+random)
  .then(function(response) {
    totalNumImages = Number(response['totalNumImages']);
    totalNumImages = totalNumImages*5+2;
    getJSON('/photo-gal?action=download_and_process&new_album='+albumName+'&num_images_on_camera='+totalNumImages+'&'+random)
    .then(function(response2) {
      console.log(response2);
      interval.id = setInterval(getDownloadProgress, 2000);
    }).catch(function(err) {
      console.error(err);
    });
  }).catch(function(err) {
    console.error(err);
  });
  function getDownloadProgress() {
    getJSON('/photo-gal?action=download_progress&album='+albumName+'&'+random)
    .then(function(response) {
      console.log(response);
      numImages = response['numImages'];
      let computedPercentage = (numImages/totalNumImages)*100;
      console.log(numImages + '/'+totalNumImages+' percentage: '+computedPercentage);
      if (computedPercentage === 100) {
        width = 100;
        progressBarProgress.style.width = '100%';
        progressBarProgress.innerHTML = 100 * 1  + '%';
        clearInterval(interval.id);
      } else {
        width = Math.round(computedPercentage);
        progressBarProgress.style.width = width + '%';
        progressBarProgress.innerHTML = width * 1  + '%';
      }
    }).catch(function(err) {
      console.error(err);
    });
  }
});

// save and load the server upload form (localStorage)
let uploadForm = document.querySelector('div#upload-to-server');
let saveUploadFormBtn = uploadForm.querySelector('#save-server-details');
let serverName = uploadForm.querySelector('#domain-name');
let username = uploadForm.querySelector('#user-name');
let port = uploadForm.querySelector('#port-number');
saveUploadFormBtn.addEventListener('click', e => {
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

let uploadBtn = uploadForm.querySelector('#upload');
