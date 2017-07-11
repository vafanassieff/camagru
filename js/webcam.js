(function() {

  var width = 1280;   
  var height = 0;
  var streaming = false;
  var video = null;
  canvas = null;
  photo = null;
  var startbutton = null;

  function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas-result');
    photo = document.getElementById('photo');
    startbutton = document.getElementById('startbutton');

    navigator.getMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);

    navigator.getMedia(
      {
        video: true,
        audio: false
      },
      function(stream) {
        if (navigator.mozGetUserMedia) {
          video.mozSrcObject = stream;
        } else {
          var vendorURL = window.URL || window.webkitURL;
          video.src = vendorURL.createObjectURL(stream);
        }
        video.play();
      },
      function(err) {
        console.log("An error occured! " + err);
      }
    );

    video.addEventListener('canplay', function(ev){
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);
        if (isNaN(height)) {
          height = width / (16/9);
        }
      
        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

    startbutton.addEventListener('click', function(ev){
      takepicture();
    
      ev.preventDefault();
    }, false);

  }

  function takepicture() {
    var test;

    test = true;
    test = check();

    if (test == false)
      return;
    var context = canvas.getContext('2d');
    if (width && height) {
      canvas.width = width;
      canvas.height = height;
      context.drawImage(video, 0, 0, width, height);
    
      var data = canvas.toDataURL('image/png');
      photo.setAttribute('src', data);
    } else {
      clearphoto();
    }
  }
 function check(){
     var radios = document.getElementsByTagName("input");

     for (var i = 0, len = radios.length; i < len; i++) {
          if (radios[i].checked) {
              return true;
          }
     }

     return false;
 }
  window.addEventListener('load', startup, false);
})();

function clearphotobis() {
    var photo = document.getElementById('photo');
    var video = document.getElementById("video");
    var filter = document.getElementById("filterimg");

    photo.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
    filter.src = '';
    video.className = 'video';
    photo.className = 'photo';
    var ele = document.getElementsByTagName("input");
      for(var i=0;i<ele.length;i++)
        ele[i].checked = false;
    }

function testimg(){
    var photo = document.getElementById('photo');

    document.getElementById('imgb64').value = photo.src;
}

function filter(filter){
    var video = document.getElementById("video");
    var photoresult = document.getElementById("photo");

    video.className = 'video';
    video.className += ' ';
    video.className += filter;

    photoresult.className = 'photo';
    photoresult.className += ' ';
    photoresult.className += filter;
}

function filter_img(filter){
    
    var element = document.getElementById("filterimg");
    var path = './asset/filter/';
    path += filter;
    path += '.png';

    element.src = path;
}