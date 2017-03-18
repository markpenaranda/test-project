$(document).ready(function() {

    function getCurrentUserId() {
       var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
      return $("#userId").val();
    }



    // function connect() {

    // }

    // connect();

    // peer.on('error', function () {
    //     console.log('connection-error');
    //     peer.destroy();
    //     connect();
    // })

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
