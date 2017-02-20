var candidateScreenManagement = (function($) {
    var timer;
    var templates = [],
        baseTemplateUrl = 'template/chatroom/';
    var currentApplicant = 0;
    var pendingApplicant = 0;
    var timerStarted = false;
    var lapseTimerInterval;
    var apiUrl = '/api/v1/public/index.php';
    var call;
    var online_user = [];
    var openday;
    var liveOpenday = [];
    var peer;

    return {
        init: init
    };

    // Init
    function init() {
        addEventHandlers();
        loadLiveOpenday(function(results) {
          var opendayId = $("#roomId").val();
          console.log(opendayId);
          if(opendayId == "") {
            console.log(results[0].openday_id) 
            $("#roomId").val(results[0].openday_id);
            window.reinitiateSocket(results[0].openday_id);
          }
            loadCandidateQueue();
            renderNecessaryTemplate();
            socketIOEventHandlers();
            connectPeerJs();
            peerJs();
        });
       


    }

    // Event Handler
    function addEventHandlers() {

        // Invite Candidate
        $("#queue-item").on('click', ".inviteButton",inviteCandidate);

        // Reject Candidate
         $("#queue-item").on('click',".rejectButton", rejectCandidate);

         // End Interview
         $("#queue-item").on('click',".endButton", endCandidate);

         // View Profile
         $("#queue-item").on('click',".viewButton", viewProfile);


        // Extend Hours Compute Total Price
         $("#extend-hours-input").on('keyup', computeTotalPrice);

         // Start Interview Button
         $('.startInterviewButton').on('click', startVideoCall);

         // Start Lapse timer
         $(".startInterviewButton").on('click', startLapseTime);
         $(".startInterviewButton").on('click', startTotalUsedTime);

         // Chat Functions
         $('.send').on('click', sendChat);
         $('#message').keypress(sendChatOnEnter);

         $("#scheduleFilter").on('change', loadCandidateQueue);


         // Stop Adding Queue 
         $(".stop_queue_btn").on('click', stopAddingQueue);


         $(".extend_more_btn").on('click', showExtendMore);
         $(".close_extend_more_btn").on('click', closeExtendMore);

         $("#numberOfHours").on('keyup', calculateAmount);

         $("#liveOpendaySelect").on('change', changeOpenday);

         $(".checkout_btn").on("click", extendHours);


    }


    // PeerJS

    function connectPeerJs () {
       peer = new Peer('openday-' + getCurrentUser(),  { host: 'openday.jobsglobal.com', secure:true, port:9000,  key: 'peerjs'});
    }

    function peerJs() {
      peer.on('call', function(call) {
                console.log(getCurrentUserId());
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

      peer.on('error', function () {
        console.log('connection-error');
        peer.destroy();
        connectPeerJs();
      });
    }

    // Socket IO

    function socketIOEventHandlers() {

        // Notifications
        window.socket.on("room-" + getCurrentRoom(), function(data){
      
         if(data.tag == "accept" && data.user_id == pendingApplicant) {
                $(".waitingButton").fadeOut();
                $(".startInterviewButton").fadeIn();
         }

         if(data.tag == "add") {
            $.get(apiUrl + "/openday/" + getCurrentRoom() + "/schedule?user_id=" + data.user_id, function(candidate){
              addCandidate(candidate);
              updateListNumber();
            });

         }



        });

        window.socket.on("user-update", function(data){ 

            online_user = [];
            for (var i = data.length - 1; i >= 0; i--) {
              var user = data[i];
              online_user.push(user.userId);
            }
            updateListNumber();
        });
    }

    function updateOnlineMarker() {

      for (var i = online_user.length - 1; i >= 0; i--) {
        var nd = online_user[i];
          $(".live-marker-" + nd).addClass("online"); 
          console.log("inserted");
      }

    }

    // Get Template
    function getTemplate(templateName, callback) {
        if (!templates[templateName]) {
            $.get(baseTemplateUrl + templateName, function(resp) {
                compiled = _.template(resp);
                templates[templateName] = compiled;
                if (_.isFunction(callback)) {
                    callback(compiled);
                }
            }, 'html');
        } else {
            callback(templates[templateName]);
        }
    }

    function renderNecessaryTemplate () {
        var opendayId = $("#roomId").val();
        loadOpenday(opendayId);
    }

    function loadLiveOpenday(callback) {
      $.get(apiUrl + '/openday/live', function(results){
        liveOpenday = results;
        for (var i = 0; i < results.length; i++) {
          var op = results[i];
          var html = "<option value='"+ op.openday_id +"'>" + op.event_name + "</option>";
          $("#liveOpendaySelect").append(html);
        }
        callback(results);
      });

    }

    function changeOpenday() {

          $("#roomId").val($(this).val());
          loadCandidateQueue();
          renderNecessaryTemplate();
          socketIOEventHandlers();
          updateOnlineMarker(); 

    }

    // Getters and Setters

    function loadOpenday(opendayId) {
      $.get(apiUrl + '/openday/' + opendayId, function(res) {
        if(res.stopped_adding_queue == "1") {
          $(".stop_queue_btn").addClass("disabled");
        }

        openday = res;
        startTotalUsedTime();
      });
    }

    function getCurrentUser() {
        var userId = $('#userId').val();
     var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
        return userId;
    }


    function getCandidateNo() {
        // ajax to get candidate no
        return 2;
    }

    function getCurrentRoom() {
        /* Modify to an ajax call if necessary or you can edit input#roomId in chatroom.php */
        var roomId = $("#roomId").val();
        return roomId;
    }


    // Queue Functions

    function loadCandidateQueue() {
      /* Do an ajax to get the sorted active candidates from the DB */
      var opendayId = getCurrentRoom();
       $("#queue-item").html("");
      var is_scheduled = $("#scheduleFilter").val();

      $.get(apiUrl + '/openday/' + opendayId + '/candidates?with_ended=1&is_scheduled=' + is_scheduled, function(res){
       
        for (var i = 0; i < res.length; i++) {
       
          var candidate = res[i]
          addCandidate(candidate);
        }
        updateOnlineMarker();
      });
    }

    /* Add Candidate in the Queue
       param applicantId - user id of the applicant/candidate
    */

    function addCandidate(candidate) {
       var user = JSON.parse(candidate.personal_info);
       var candidate_no = 1;
       getTemplate('queue_item.html', function(render) {
            

            var renderedhtml = render({data : candidate, user_id: candidate.user_id, user: user, room_id: getCurrentRoom(), candidate_no: candidate_no});
            $("#queue-item").append(renderedhtml);
        });
    }

    /* Remove Candidate in the Queue List
       param applicantId - user id of the applicant/candidate
    */
    function removeCandidate(applicantId) {
      /* Do AJAX Stuff here to remove the candidate from the DB. */
      // console.log($("#queue-" + applicantId));
      $("#queue-" + applicantId).remove();
    }



    function inviteCandidate() {
        var userId = $(this).data('user');
        var roomId = $(this).data('room');
       
        var message = "Your interview is now ready";
        if (!location.origin) {
           location.origin = location.protocol + "//" + location.host;
        }
        var link = location.origin + "/interview.php?openday=" + roomId;

        $.post(apiUrl + '/openday/' + roomId + '/set-interviewing', { user_id: userId }, function(res){
            $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "invite", message: message, link: link}, function (data){
                $("#inviteModal").modal("show");
                pendingApplicant = userId;
            });

        })

    }


    function rejectCandidate() {
        var cf = confirm("Are you sure you want to reject this candidate?");
        if(!cf) { return false; }
        var userId = $(this).data('user');
        var roomId = $(this).data('room');
        var message = "Sorry your application has been rejected";
        if (!location.origin) {
           location.origin = location.protocol + "//" + location.host;
        }
        var link = location.origin + "/interview.php?openday=" + roomId;
        $.post(apiUrl + '/openday/' + roomId + '/reject', { user_id: userId }, function(res) {
           $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "reject", message: message, link: link}, function (data){
              removeCandidate(userId);
              $("#candidate-" + userId).remove();
            });
        });
        
    }

    function endCandidate() {
        var cf = confirm("Are you sure you want to end this interview?");
        if(!cf) { return false; }
        var userId = $(this).data('user');
        var roomId = $(this).data('room');
        var message = "Your interview has ended";
        if (!location.origin) {
           location.origin = location.protocol + "//" + location.host;
        }
        var link = location.origin + "/interview.php?openday=" + roomId;
        $.post(apiUrl + '/openday/' + roomId + '/end', { user_id: userId }, function(res){
          
          $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "end", message: message, link: link}, function (data){
              removeCandidate(userId);
              currentApplicant = 0;
              call.close();
              $(".send").prop("disabled", true);
              $(".messages").html('');
              clearInterval(lapseTimerInterval);
              var endInterviewTime = moment(moment.utc(res.date_interviewed_end).toDate()).local().format("MM/D hh:mmA");
              $("#candidate-" + userId + "-end-span").html(endInterviewTime);
              $("#candidate-"+ userId + "-button-group").fadeOut();
              $("#candidate-"+ userId + "-end").fadeIn();
              pauseTimer();
              $("#liveOpendaySelect").prop('disabled', false);
              // TODO: Add AJAX here to record that the candidate has ended in the DB.

          });
        })
    }



    function computeTotalPrice() {
      var rate = $("#rate").val();
      var hours = $(this).val();
      var totalPrice = hours * rate;
      totalPrice = (totalPrice).formatMoney(2, '.', ',');
      $("#totalPrice").html(totalPrice);


    }

    function startVideoCall() {
      currentApplicant = pendingApplicant;
      console.log(currentApplicant);
      // Update Elements
      $(".invite-button-" + currentApplicant).prop( "disabled", true );
      $(".invite-button-" + currentApplicant).addClass( "disabled");
      $(".invite-button-" + currentApplicant).html("Interviewing");

      $("#reject-" + currentApplicant).hide();

      $("#end-" + currentApplicant).show();

      $.get(apiUrl + '/openday/' + getCurrentRoom() + '/schedule?user_id=' + currentApplicant, function(res){
        var user = { id: res.user_id, name: res.name, candidate_no:res.candidate_number  };
        $("#spanCandidateNumber").html("Candidate" + res.candidate_number + ": ");
        $("#spanCandidateName").html(res.name);
        $("#liveOpendaySelect").prop('disabled', 'disabled');
        getTemplate('live_candidate_details.html', function(render) {
             var renderedhtml = render({user: user, room_id: getCurrentRoom()});
             $("#liveCandidateDetails").append(renderedhtml);
      
         });

       // WebRTC
    	 navigator.getUserMedia({video: true, audio: true}, function(stream) {
    	  var localVideo = document.getElementById('localVideo');
    	  localVideo.srcObject = stream;
       
        console.log(currentApplicant);
    	  call = peer.call('openday-' + currentApplicant, stream);
        console.log(call); 
    	  call.on('stream', function(remoteStream) {
    	      var remoteVideo = document.getElementById('remoteVideo');
    	  	  remoteVideo.srcObject = remoteStream;
    	      $("#remoteVideo").css("width", "100%");
    		  });
    	   }, function(err) {
    		  console.log('Failed to get local stream' ,err);
    	  });
      });



    }




    function sendChat() {
      var message = $('#message').val();

      if(currentApplicant) {
        sendMessage(message);
        $("#message").val('');
      }
    }

    function sendChatOnEnter(event) {
      var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13'){
          sendChat();
      }
    }

    function startLapseTime() {
      var time = 60 * 60 * 0;
      var display = $('#lapseTime');
      if(timerStarted) {
        clearInterval(lapseTimerInterval);
      }
      lapseTimerInterval = lapseTimer(time, display);
    }

    function startTotalUsedTime() {
        var end = moment(openday.event_date + " " + openday.end_time);
         var start = moment(openday.event_date + " " + openday.start_time);

         var totalDuration = end - start;
         var tD = moment.utc(moment.duration(totalDuration).asMilliseconds()).format("HH[H]mm[M]");
         $("#totalDuration").html(tD);

        myTimer();
        var timer = setInterval(myTimer, 5000);

        function myTimer() {
            var now = moment();
            var then = moment(moment.utc(openday.event_date + " " + openday.start_time).toDate()).local();

             var duration = now - then;
             var time = moment.utc(moment.duration(duration).asMilliseconds()).format("HH [H] mm [M]");
             $("#totalUsedTime").html(time);
        }
    }

    function pauseTimer() {
        var displayTotalUsedTime = $('#totalUsedTime');
        displayTotalUsedTime.addClass('pause');
    }

    function updateListNumber() {
        /* TODO: ajax to manipulate the ff.
          1. check in with scheduled
          2. total not yet check in with schedule
          3. waiting list
        **/
        var roomId = $("#roomId").val();
        $.get(apiUrl + '/openday/' + roomId + '/candidates-id', function(result){
     
          var scheduled = result.scheduled;
          var checkInScheduled = intersect(scheduled, window.online_user);
          var notCheckInScheduled = scheduled.length - checkInScheduled.length;
          $("#checkInWithSchedule").html(checkInScheduled.length);
          $("#notCheckInWithSchedule").html(notCheckInScheduled);
          $("#waitingListCount").html(result.notScheduled.length);
        });
    }

    function intersect(a, b) {
        var d = {};
        var results = [];
        for (var i = 0; i < b.length; i++) {
            d[b[i]] = true;
        }
        for (var j = 0; j < a.length; j++) {
            if (d[a[j]]) 
                results.push(a[j]);
        }
        return results;
    }


    function stopAddingQueue() {
      var conf = confirm("Are you sure you want to stop?");
      if(!conf) { return false; }
      var opendayId = $("#roomId").val();
      $.post(apiUrl + '/openday/' + opendayId + '/stop', function(res) {
          $(".stop_queue_btn").prop('disabled', true);
          $(".stop_queue_btn").addClass('disabled');
      });
    }


    function viewProfile() {
      var userId = $(this).data('id');

      $.get(apiUrl + '/users/' + userId, function(user){
        getTemplate('profile.html', function(render){
          var html = render({ data: user });
          $("#profileView").html(html);
        });
      });
    }

    function showExtendMore() {
      $("#extendMore").fadeIn();
      $("#profileView").fadeOut();
    }

    function closeExtendMore() {
       $("#extendMore").fadeOut();
      $("#profileView").fadeIn();

    }

    function extendHours() {
      var numberOfHours = $("#numberOfHours").val();
      var opendayId = getCurrentRoom();

      $.post(apiUrl + '/openday/' + opendayId + '/extend', {numberOfHours: numberOfHours }, function(){
        closeExtendMore();
        loadOpenday();
      });
    }

    function calculateAmount() {
      var hours = parseInt($(this).val());
      var amount = 100 * hours;
      var amount = (amount > 0) ? amount : 0;
      $(".total-amount").html("$" + amount);

    }


})($);
$(candidateScreenManagement.init);


