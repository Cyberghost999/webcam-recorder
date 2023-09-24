<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webcam Recorder</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<center>
  <h1>WEBcam Recorder</h1>
<p><video id="video" autoplay width=320/><p>
<p><button onclick="startFunction()">Start recording</button></p>
<p><button onclick="download()">Stop video</button></p>
</center>
  
<span id="txtHint"></span>

<script> 
    const constraints = { "video": { width: { max: 320 } }, "audio" : true };

var theStream;
var theRecorder;
var recordedChunks = [];

function startFunction() {
  navigator.mediaDevices.getUserMedia(constraints)
      .then(gotMedia)
      .catch(e => { console.error('getUserMedia() failed: ' + e); });
}

function gotMedia(stream) {
  theStream = stream;
  var video = document.querySelector('video');
  video.srcObject = stream;
  try {
    recorder = new MediaRecorder(stream, {mimeType : "video/webm"});
  } catch (e) {
    console.error('Exception while creating MediaRecorder: ' + e);
    return;
  }
  
  theRecorder = recorder;
  recorder.ondataavailable = 
      (event) => { recordedChunks.push(event.data); };
  recorder.start(100);
}

function download() {
  theRecorder.stop();
  theStream.getTracks().forEach(track => { track.stop();});

  var blob = new Blob(recordedChunks, {type: "video/webm"});
  var url =  URL.createObjectURL(blob);
  var a = document.createElement("a");
  var loc = Math.floor(Math.random() * 1000);
  document.body.appendChild(a);
  a.style = "display: none";
  a.href = url;
  a.download = loc;
  a.click();
  var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "data.php?loc=" + loc, true);
    xmlhttp.send();
    document.write(xmlhttp.send());
  // setTimeout() here is needed for Firefox.
  setTimeout(function() { URL.revokeObjectURL(url); }, 100); 
}

function edit(id) {
  var title = document.getElementById(id).value
  var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "edit.php?id=" + id + "&t=" + title, true);
    xmlhttp.send(); 
    document.write(xmlhttp.send());
  // setTimeout() here is needed for Firefox.
  setTimeout(function() { URL.revokeObjectURL(url); }, 100); 
}
</script>
</body>
</html>
