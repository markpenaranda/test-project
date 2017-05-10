var NotificationManagement = (function($){
  var apiUrl = "/api/v1/public/index.php";
  var current_user = [];
  var socket = io(window.liveServerUrl);
  window.socket = socket;
  return {
    init: init
  };

  function init() {
    addNotificationStyling();
    socketHandlers();
    countNotification();
    eventHandlers();
  }

  function socketHandlers () {
    console.log("Listening: " + "notifier-" + getCurrentUserId());

    socket.on("notifier-" + getCurrentUserId(), function(data){
      
      if(data.category == "candidate") {
         browserNotifyMe(data.message, data.link);
         // window.location.reload();
      }

      if(data.category == "flash") {
        $.notify({message: data.message, title: data.tag}, {style:"openday",
          position: "bottom right"});
        
      }

      if(data.category == "persistent") {
        $.notify({message: data.message, title: data.tag}, {style:"openday",
          position: "bottom right"});
        countNotification();
      }

    });

    socket.on("room-" + $("#roomId").val(), function(data){

      if(data.category == "candidate") {
        if(data.tag != "close") {
         browserNotifyMe(data.message, data.link);
        }
  
      }

    });

    socket.emit("live-connect", { user_id: getCurrentUserId() });

    socket.on("user-update", function(data){
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

  }

  function eventHandlers() {
    $(".notification-button").on("click", clickNotificationIcon);

    $(".notification-list").on("click", ".load-more-a", nextPage());
 
 
  }

  function clickNotificationIcon() {
    $(".notification-list").html('');
    $.get(apiUrl + "/notifications?user_id=" + getCurrentUserId(), function(res){
      for (var i = res.data.length - 1; i >= 0; i--) {
        var notification = res.data[i];

        var html = "<li><a href='"+ notification.link +"'>"
                +"<b>" + notification.title + "</b><br>" + notification.message +"</a></li>";
        $(".notification-list").prepend(html);
      }
      

      // $(".notification-list").append('<li class="notification-load-more"><center><a class="load-more-a">Load More..</a></center></li>');
      setAsRead();
    });
  }

  function nextPage(e) {
    
     var finalListHtml = "";
     $.get(apiUrl + "/notifications?user_id=" + getCurrentUserId(), function(res){
      for (var i = res.data.length - 1; i >= 0; i--) {
        var notification = res.data[i];

        var html = "<li><a href='"+ notification.link +"'>"
                +"<b>" + notification.title + "</b><br>" + notification.message +"</a></li>";
        finalListHtml = html + finalListHtml;
      }
      
      $(".notification-list").append(finalListHtml);

      $(".notification-list").append('<li class="notification-load-more"><center>< class="load-more-a" href="#">Load More..</a></center></li>');
     
    });

  }

  function countNotification() {
    $.get(apiUrl + "/notifications/count?user_id=" + getCurrentUserId(), function(data) {
      if(data.success) {
        updateBadge(data.total);
        updatePageTitle(data.total);
      }
    })
  }

  function updateBadge(count) {
    $(".badge--notification").html(count);
    if(count > 0) {
      $(".badge--notification").css("visibility", "visible");
    }
    else {
      $(".badge--notification").css("visibility", "hidden");
    }
  }

  function updatePageTitle(count) {
    var originalPageTitle = $("data-title").html();
    if(count > 0) {
      document.title = "(" + count + ") " + originalPageTitle;
    }
    else {
      document.title = originalPageTitle;
    }
  }

  function setAsRead() {
    $.get(apiUrl + "/notifications/read?user_id=" + getCurrentUserId(), function(data) {
      if(data.success) {
        countNotification();
      }
    })
  }

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

  function addNotificationStyling() {
    $.notify.addStyle('openday', {
      html: "<div><b data-notify-text='title'></b><br><span data-notify-text='message'/></div>",
      classes: {
        base: {
          "max-width" : "300px",
          "border" : "1px solid #e56a00",
          "border-radius" : "5px",
          "padding": "10px",
          "background-color": "#ecb98c",
          "font-size" : "12px"
        },
        superblue: {
          "color": "white",
          "background-color": "blue"
        }
      }
    });
  }
  /**
   *
   * Private Functions
   *
   */
  

   function getCurrentUserId() {
      var localStorageUser = localStorage.getItem('userId');
      if(localStorageUser) {
        return localStorageUser;
      }
      return $("#userId").val();
   }

   function isInArray(value, array) {
      return array.indexOf(value) > -1;
   }


})($);

$(NotificationManagement.init);