var gumStream; 						//stream from getUserMedia()
var rec;
var rec1;
var rec2;
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
stopButton.addEventListener("click", stopRecording);

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

function stopRecording() {
	console.log("stopButton clicked");

	//disable the stop button, enable the record too allow for new recordings
	stopButton.disabled = true;
	startButton.innerHTML="Start";
	startButton.disabled = false;

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();
	clearInterval(_recording);
	//create the wav blob and pass it on to createDownloadLink
	// rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
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
}

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