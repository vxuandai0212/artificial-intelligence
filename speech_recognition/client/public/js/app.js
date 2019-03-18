//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec;
var rec1;
var rec2;
var rec3; 							//Recorder.js object
var rec4;
var input;						//MediaStreamAudioSourceNode we'll be recording
var input1;
var trans = ''
var _recording;
// 10s each record
var duration = 10000
// next start after previous recorded 8s
var start_after = 8000

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var startButton = document.getElementById("startButton");
var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
startButton.addEventListener("click", stream_record);
// recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
// pauseButton.addEventListener("click", pauseRecording);

function stream_record() {
	document.getElementById("message").innerHTML = ''
	recordingsList.innerHTML = ''
	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia() 
	*/
	startButton.disabled = true;
	startButton.innerHTML="Recording";
	// recordButton.disabled = true;
	stopButton.disabled = false;
	// pauseButton.disabled = false

	/*
    	We're using the standard promise based getUserMedia() 
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format 
		document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

		/*  assign to gumStream for later use  */
		gumStream = stream;
		
		/* use the stream */
		input1 = audioContext.createMediaStreamSource(stream);

		/* 
			Create the Recorder object and configure to record mono sound (1 channel)
			Recording 2 channels  will double the file size
		*/
		
		// turn 0
		rec1 = new Recorder(input1,{numChannels:1})
		//start the recording process
		rec1.record()
		setTimeout(() => {
			console.log('first stream stop')
			//tell the recorder to stop the recording
			rec1.stop();
			//create the wav blob and pass it on to createDownloadLink
			rec1.exportWAV(createDownloadLink);
		}, 18000)
		// interval = 16s when duration each recorder = 10s, each after each 8s
		_recording = setInterval(startRecorder, 16000)
	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function startRecording() {
	console.log("recordButton clicked");

	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia() 
	*/

	recordButton.disabled = true;
	stopButton.disabled = false;
	pauseButton.disabled = false

	/*
    	We're using the standard promise based getUserMedia() 
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format 
		document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

		/*  assign to gumStream for later use  */
		gumStream = stream;
		
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);

		/* 
			Create the Recorder object and configure to record mono sound (1 channel)
			Recording 2 channels  will double the file size
		*/
		rec = new Recorder(input,{numChannels:1})

		//start the recording process
		rec.record()

		console.log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function pauseRecording(){
	console.log("pauseButton clicked rec.recording=",rec.recording );
	if (rec.recording){
		//pause
		rec.stop();
		pauseButton.innerHTML="Resume";
	}else{
		//resume
		rec.record()
		pauseButton.innerHTML="Pause";

	}
}

function stopRecording() {
	console.log("stopButton clicked");

	//disable the stop button, enable the record too allow for new recordings
	stopButton.disabled = true;
	startButton.innerHTML="Start";
	startButton.disabled = false;
	// recordButton.disabled = false;
	// pauseButton.disabled = true;

	// //reset button just in case the recording is stopped while paused
	// pauseButton.innerHTML="Pause";
	
	// //tell the recorder to stop the recording
	// rec.stop();

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();
	clearInterval(_recording);
	//create the wav blob and pass it on to createDownloadLink
	// rec.exportWAV(createDownloadLink);
}

function stopFirstRecording() {
	console.log('first stream stop')
	//tell the recorder to stop the recording
	rec1.stop();

	//create the wav blob and pass it on to createDownloadLink
	rec1.exportWAV(createDownloadLink);
}

function stopSecondRecording() {
	//tell the recorder to stop the recording
	rec2.stop();

	//create the wav blob and pass it on to createDownloadLink
	rec2.exportWAV(createDownloadLink);
}


function createDownloadLink(blob) {
	var url = URL.createObjectURL(blob);
	var fd = new FormData();
	var filename = Math.floor(Math.random() * 100) + '.wav'
	fd.append('fname', filename);
	fd.append('data', blob);
	var full_trans = localStorage.getItem("full_trans")
	if (full_trans) {
		fd.append('full_trans', full_trans)
	} else {
		fd.append('full_trans', '')
	}
	axios.post('http://192.168.52.179:5000/predict', fd)
	.then(function (response) {
		var result = response.data.full_trans
		localStorage.setItem('full_trans', result);
		document.getElementById("message").innerHTML = result
	})
	.catch(function (error) {
		console.log(error);
	});
	var au = document.createElement('audio');
	var li = document.createElement('li');
	var link = document.createElement('a');

	//name of .wav file to use during upload and download (without extendion)
	var filename = new Date().toISOString();

	//add controls to the <audio> element
	au.controls = true;
	au.src = url;

	//save to disk link
	link.href = url;
	link.download = filename+".wav"; //download forces the browser to donwload the file using the  filename
	link.innerHTML = "Save to disk";

	//add the new audio element to li
	li.appendChild(au);
	
	//add the filename to the li
	li.appendChild(document.createTextNode(filename+".wav "))

	//add the save to disk link to li
	li.appendChild(link);
	
	//upload link
	var upload = document.createElement('a');
	upload.href="#";
	upload.innerHTML = "Upload";
	upload.addEventListener("click", function(event){
		  var xhr=new XMLHttpRequest();
		  xhr.onload=function(e) {
		      if(this.readyState === 4) {
		          console.log("Server returned: ",e.target.responseText);
		      }
		  };
		  var fd=new FormData();
		  fd.append("audio_data",blob, filename);
		  console.log(fd)
		  xhr.open("POST","upload.php",true);
		  xhr.send(fd);
	})
	li.appendChild(document.createTextNode (" "))//add a space in between
	li.appendChild(upload)//add the upload link to li

	//add the li element to the ol
	recordingsList.appendChild(li);
}

(window.document.onload = function(){ 
	localStorage.removeItem('full_trans')
})()

function startRecorder() {
	rec2 = new Recorder(input1, {numChannels:1})
	rec2.record()
	setTimeout(() => {
		rec2.stop()
		rec2.exportWAV(createDownloadLink)
	}, duration)

	setTimeout(() => {
		rec1 = new Recorder(input1, {numChannels:1})
		rec1.record()
	}, start_after)
	setTimeout(() => {
		rec1.stop()
		rec1.exportWAV(createDownloadLink)
	}, (start_after + duration))
}