$(document).ready(function() {

    function getCurrentUserId() {
       var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
      return $("#userId").val();
    }

     var peer = new Peer('openday-' + getCurrentUserId(),  {host: 'openday.jobsglobal.com', secure:true, port:9000, key: 'peerjs'});
      window.peer = peer;

    // function connect() {

    // }

    // connect();

    // peer.on('error', function () {
    //     console.log('connection-error');
    //     peer.destroy();
    //     connect();
    // })

    peer.on('call', function(call) {
      window.call = call;
      console.log(getCurrentUserId());
      // var navGetUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
      navigator.getUserMedia({video: true, audio: true}, function(stream) {
         var localVideo = document.getElementById('localVideo');
        localVideo.srcObject = stream;
        call.answer(stream); // Answer the call with an A/V stream.
        call.on('close', function(){
            alert("You're interviewer has been disconnected from the chat. Kindly wait to be invited back again in the chatroom.");
            location.reload();
        });
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

    // $(window).bind('beforeunload', function(){
    //   console.log("Bye");
    //   var conf = confirm("Are you sure you want to leave this page? NOTE: You will be disconnected from this interview.");
    //   if(conf){ peer.destroy(); }
    // });


});

//



// peer.on('disconnect', function() {
//   alert('Disconnected');
// })
