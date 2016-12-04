$(document).ready(function(){

// 

 var peer = new Peer('openday-' + $("#userId").val(),  {host: 'mjpvc.herokuapp.com', secure:true, port:443, key: 'peerjs'});

$('.startInterviewButton').on('click', function() {
	// var navGetUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
	navigator.getUserMedia({video: true, audio: true}, function(stream) {
	  var localVideo = document.getElementById('localVideo');
	  localVideo.srcObject = stream;
  	  
	  var call = peer.call('openday-' + $("#applicantId").val(), stream);
	  call.on('stream', function(remoteStream) {
	      var remoteVideo = document.getElementById('remoteVideo');
	  	  remoteVideo.srcObject = remoteStream;
	      $("#remoteVideo").css("width", "100%");
		  });
	   }, function(err) {
		  console.log('Failed to get local stream' ,err);
	  });
});



peer.on('call', function(call) {
	// var navGetUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
  navigator.getUserMedia({video: true, audio: true}, function(stream) {
  	 var localVideo = document.getElementById('localVideo');
	  localVideo.srcObject = stream;
    call.answer(stream); // Answer the call with an A/V stream.
    call.on('stream', function(remoteStream) {
      	var remoteVideo = document.getElementById('remoteVideo');
	  	remoteVideo.srcObject = remoteStream;
       $("#remoteVideo").css("height", "100%");
       $("#remoteVideo").css("margin-left", "auto");
       $("#remoteVideo").css("margin-right", "auto");
       $("#remoteVideo").css("top", "50%");
       $("#remoteVideo").css("left", "50%");
       $("#remoteVideo").css("transform", "translate(-50%, -50%)");

         var display = $('#lapseTime');


       lapseTimer(0, display);
    });
  }, function(err) {
    console.log('Failed to get local stream' ,err);
  });
});


});