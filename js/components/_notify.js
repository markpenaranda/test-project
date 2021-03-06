document.addEventListener('DOMContentLoaded', function () {
  if (navigator.webkitNotifications) {
  console.log("Notifications are supported!");
}
else {
  console.log("Notifications are not supported for this Browser/OS version yet.");
}

  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try Chromium.'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
});

function browserNotifyMe($message, link) {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification('Open Day Live Interview', {
      icon: 'http://icons.iconarchive.com/icons/webalys/kameleon.pics/512/Video-Camera-2-icon.png',
      body: $message,
  });

    notification.onclick = function () {
      window.open(link);      
    };

  }

}

function isInArray(value, array) {
  return array.indexOf(value) > -1;
}

function getCurrentUser() {

    var userId = $('#userId').val();
    
    var localStorageUser = localStorage.getItem('userId');
    if(localStorageUser) {
      return localStorageUser;
    }

    return userId;
}

window.socket = io(window.liveServerUrl);


socket.on("notifier-" + getCurrentUser(), function(data){

	if(data.category == "candidate") {
		 browserNotifyMe(data.message, data.link);
		 // window.location.reload();
	}

  if(data.category == "update") {
    $.notify(data.message);
  }



});

socket.on("room-" + $("#roomId").val(), function(data){

  if(data.category == "candidate") {
    if(data.tag != "close") {
     browserNotifyMe(data.message, data.link);
    }
     // window.location.reload();


  }


});

// For Live Checking
socket.emit("live-connect", { user_id: getCurrentUser() });
var current_user = [];

window.socket.on("user-update", function(data){
    var new_data = []
    window.online_user = [];
      for (var i = data.length - 1; i >= 0; i--) {
        var user = data[i];
        new_data.push(user.userId);
        window.online_user.push(user.userId);
      }
    
      // present in new *This will activated as online
      var new_active = [];
      for (var i = new_data.length - 1; i >= 0; i--) {
        var nd = new_data[i];
        if(!isInArray(nd, current_user)) {
          $(".live-marker-" + nd).addClass("online"); 
        }
        
      }

      // turned offline *this will activated as offline
      var new_off = [];
      for (var i = current_user.length - 1; i >= 0; i--) {
        var cu = current_user[i];
        if (!isInArray(cu, new_data)) {
           new_off.push(cu); 
           $(".live-marker-" + cu).removeClass("online");  
        }
        
      }

      current_user = new_data;

    
});


// For Browser Notify




