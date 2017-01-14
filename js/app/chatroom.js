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

    return {
        init: init
    };

    // Init
    function init() {
        loadCandidateQueue();
        addEventHandlers();
        renderNecessaryTemplate();
        socketIOEventHandlers();

    }

    // Event Handler
    function addEventHandlers() {

        // Invite Candidate
        $("#queue-item").on('click', ".inviteButton",inviteCandidate);

        // Reject Candidate
         $("#queue-item").on('click',".rejectButton", rejectCandidate);

         // End Interview
         $("#queue-item").on('click',".endButton", endCandidate);

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

    }


    // Socket IO

    function socketIOEventHandlers() {

        // Notifications
        window.socket.on("room-" + getCurrentRoom(), function(data){
         updateListNumber();
         if(data.tag == "accept" && data.user_id == pendingApplicant) {
                $(".waitingButton").fadeOut();
                $(".startInterviewButton").fadeIn();
         }

         if(data.tag == "add") {
            addCandidate(data.user_id);

         }



        });
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
        // TODO: Do ajax stuff here and activate the necessary template

    }


    // Getters and Setters

    function getCurrentUser() {
        var userId = $('#userId').val();
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
       $("#queue-item").html('');
      var is_scheduled = $("#scheduleFilter").val();
      $.get(apiUrl + '/openday/' + opendayId + '/candidates?is_scheduled=' + is_scheduled, function(res){
        for (var i = 0; i < res.length; i++) {
          var candidate = res[i]
          addCandidate(candidate);
        }
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
      console.log($("#queue-" + applicantId));
      $("#queue-" + applicantId).remove();
    }



    function inviteCandidate() {
        var userId = $(this).data('user');
        var roomId = $(this).data('room');
        console.log(userId);
        console.log(roomId);
        var message = "Your interview is now ready";
        if (!location.origin) {
           location.origin = location.protocol + "//" + location.host;
        }
        var link = location.origin + "/candidate.php";

        /* Before Running code below do an ajax to update the server that the candidate has been invited */
        $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "invite", message: message, link: link}, function (data){
            $("#inviteModal").modal("show");
            pendingApplicant = userId;
        });

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
        var link = location.origin + "/candidate.php";
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
        var link = location.origin + "/candidate.php";
        $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "end", message: message, link: link}, function (data){
            removeCandidate(userId);
            currentApplicant = 0;
            call.close();
            $(".send").prop("disabled", true);
            $(".messages").html('');
            clearInterval(lapseTimerInterval);

            pauseTimer();
            // TODO: Add AJAX here to record that the candidate has ended in the DB.

        });
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
      // Update Elements
      $(".invite-button-" + currentApplicant).prop( "disabled", true );
      $(".invite-button-" + currentApplicant).html("Interviewing");

      $(".reject-button-" + currentApplicant).hide();

      $(".end-button-" + currentApplicant).show();

      // TODO: add ajax to get the currentApplicant user details and as well as the candidate number.
      var user = { id: currentApplicant, name: 'John Doe' };
      getTemplate('live_candidate_details.html', function(render) {
           var renderedhtml = render({user: user, room_id: getCurrentRoom(), candidate_no: '001'});
           $("#liveCandidateDetails").append(renderedhtml);
       });

      // WebRTC
    	navigator.getUserMedia({video: true, audio: true}, function(stream) {
    	  var localVideo = document.getElementById('localVideo');
    	  localVideo.srcObject = stream;

    	  call = window.peer.call('openday-' + currentApplicant, stream);
    	  call.on('stream', function(remoteStream) {
    	      var remoteVideo = document.getElementById('remoteVideo');
    	  	  remoteVideo.srcObject = remoteStream;
    	      $("#remoteVideo").css("width", "100%");
    		  });
    	   }, function(err) {
    		  console.log('Failed to get local stream' ,err);
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
      var time = jQuery('#initialTime').val();
      var displayTotalUsedTime = $('#totalUsedTime');
      displayTotalUsedTime.removeClass('pause');
      if(timerStarted === false) {
        totalTimer(time, displayTotalUsedTime, pauseTimer);
        timerStarted = true;
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
        $("#checkInWithSchedule").html(20);
        $("#notCheckInWithSchedule").html(20);
        $("#waitingListCount").html(5);
    }



})($);
$(candidateScreenManagement.init);
