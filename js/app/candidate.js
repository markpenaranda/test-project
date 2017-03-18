var candidateScreenManagement = (function($) {
    var timer;
    var templates = [],
        baseTemplateUrl = 'template/candidate/';
    var onInterview = false;
    var apiUrl = '/api/v1/public/index.php';
    var openday = null;
    var scheduleStatus = 0;
    return {
        init: init
    };

    // Init
    function init() {
        console.log("trigger here");
        loadOpendayDetail(function(){
            socketIOEventHandlers();
            addEventHandlers();
            renderNecessaryTemplate();

        });
    }

    // Event Handler
    function addEventHandlers() {

        // Join Button
        $("#waitingDiv").on('click','#joinInterview', activateCandidateInterview);

        // Chat Functions
        $('.send').on('click', sendChat);
        $('#message').keypress(sendChatOnEnter);

        $(".fullscreen-toggle").on('click', fullscreen);
        $(".not-fullscreen-toggle" ).on('click',notFullscreen);




        $(window).on("unload", function (e) {
          var opendayId = $("#roomId").val();
          var userId = getCurrentUser();

          $.get(apiUrl + '/openday/' + opendayId + '/schedule?user_id=' + userId, function(schedule) {
            scheduleStatus = parseInt(schedule.status);
            if(!scheduleStatus) {
              $.post(apiUrl + "/openday/" + getCurrentRoom() + "/set-waiting?user_id=" + getCurrentUser(), function() {
                alert("You're interviewer has been disconnected from the chat. Kindly wait to be invited back again in the chatroom.");
                window.call.close();


              });

            }

          });
        });

    }

    // fullscreen
    function fullscreen () {
      $(".fullscreen-toggle").addClass("hide");
      $(".not-fullscreen-toggle").removeClass("hide");
      $("#remoteVideo").removeClass("remote-vc-not-fullscreen").addClass("remote-vc-fullscreen");
      $("#localVideo").removeClass("local-vc-not-fullscreen").addClass("local-vc-fullscreen");
    }


    function notFullscreen() {
      $(".fullscreen-toggle").removeClass("hide");
      $(".not-fullscreen-toggle").addClass("hide");

      $("#remoteVideo").removeClass("remote-vc-fullscreen").addClass("remote-vc-not-fullscreen");
      $("#localVideo").removeClass("local-vc-fullscreen").addClass("local-vc-not-fullscreen");

    }


    // PeerJS



    // Socket IO

    function socketIOEventHandlers() {

        // Notifications
        window.socket.on("notifier-" + getCurrentUser(), function(data){

        if(data.category == "candidate") {
             if(data.tag == "invite") {
                renderNecessaryTemplate();
             }
             if(data.tag == "reject") {
               scheduleStatus = 3;
                activateRejectUnderscoreTemplate();
             }

             if(data.tag == "end") {
                onInterview = false;
                scheduleStatus = 2;
                activateEndUnderscoreTemplate();
             }

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


    function loadOpendayDetail(callback) {
        $.get(apiUrl + '/openday/' + getCurrentRoom(), function(opendayRes){
            console.log(opendayRes);
            openday = opendayRes;
            getTemplate('openday-detail.html',function(render) {
                var html = render({data: openday, jobName: openday.jobs});
                $("#opendayDetails").html(html);
                $(".companyName").html(openday.page_name);
                callback();
            });



        });
    }

    function updateCurrentlyInterviewedCandidate() {
      $.get(apiUrl + '/openday/' + getCurrentRoom() + '/currently-interviewed', function (res) {
        var candidateNumber = res['candidate_number'];
        if(candidateNumber) {
        console.log(candidateNumber);
          $(".ongoing-interview-number").html(candidateNumber);
          $("#waitingDiv > .ongoing-interview-number-section").fadeIn();
        }

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
        var roomId = $("#roomId").val();
        return roomId;
    }


    function renderNecessaryTemplate () {
          var opendayId = $("#roomId").val();
          var userId = getCurrentUser();

          $.get(apiUrl + '/openday/' + opendayId + '/schedule?user_id=' + userId, function(schedule) {
            scheduleStatus = parseInt(schedule.status);
            var isScheduled = (schedule.is_scheduled === '1');
            console.log("trigger inside openday load");
            console.log(schedule);


            var currentTime = getCurrentUTCTime();
            var eventSchedule = moment(schedule.event_date + " " + schedule.schedule_time_end, "YYYY-M-D H:mm:ss");
            // Missed
            // if(eventSchedule.isBefore(currentTime) && schedule.is_attended == 0) {

            //     console.log(eventSchedule.isBefore(currentTime));
            //     console.log(schedule.is_attended);
            //     activateMissedTemplate(schedule);
            // }

            // else {
                // Waiting and Scheduled
                if(scheduleStatus == 1 && isScheduled) {
                    console.log("waiting");
                    activateScheduledUnderscoreTemplate(schedule);
                }

                // Waiting not Scheduled
                 if(scheduleStatus == 1 && !isScheduled) {
                    console.log("waiting not sched");
                    activateWaitingListUnderscoreTemplate(schedule);
                }

                // Reject

                if(scheduleStatus == 3) {
                    console.log("reject");

                    activateRejectUnderscoreTemplate(schedule);
                }

                // End

                if(scheduleStatus == 2 && schedule.is_attended == 1) {
                    console.log("end");

                    activateEndUnderscoreTemplate(schedule);
                }

                // Interviewing
                if(scheduleStatus == 0) {
                    console.log("interviewing");

                    activateJoinUnderscoreTemplate(schedule);
                }

            // }


           updateCurrentlyInterviewedCandidate();

          });

    }


    function activateCandidateInterview() {
        onInterview = true;
        $.get(window.liveServerUrl + "/room/" + getCurrentRoom() + "/accept", {user_id: getCurrentUser(), candidate_no: getCandidateNo()}, function(){
            $("#opendayDetails").fadeOut();
            $("#waitingDiv").fadeOut();
            $("#candidate-interview").fadeIn();
            $('.messages').html('');
        });


    }


    function activateWaitingListUnderscoreTemplate(schedule) {
      $.get(apiUrl + '/openday/' + getCurrentRoom() + '/waiting-mode', function (res) {
        var currentlyInterviewed = res['currently_interviewed']['candidate_number'];
        var waitingListCount = res['total_waiting_list'];
        getTemplate('waiting.html', function(render) {
                        var renderedhtml = render({data:schedule, openday: openday, waitingListCount: waitingListCount,current_interview: currentlyInterviewed});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
      });
    }

    function activateScheduledUnderscoreTemplate(schedule) {
       $.get(apiUrl + '/openday/' + getCurrentRoom() + '/waiting-mode', function (res) {
        var currentlyInterviewed = res['currently_interviewed']['candidate_number'];
        var waitingListCount = res['total_waiting_list'];
        getTemplate('scheduled.html', function(render) {
                        var renderedhtml = render({data:schedule, openday: openday, waitingListCount: waitingListCount,current_interview: currentlyInterviewed});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });

      });
    }

    function activateJoinUnderscoreTemplate(schedule) {

         $.get(apiUrl + '/openday/' + getCurrentRoom() + '/waiting-mode', function (res) {
             var waitingListCount = res['total_waiting_list'];
            getTemplate('join.html', function(render) {
                            var renderedhtml = render({schedule: schedule, openday: openday, waitingListCount: waitingListCount});
                            $("#candidate-interview").fadeOut();
                            $("#waitingDiv").html(renderedhtml);
            });
          });
    }

     function activateRejectUnderscoreTemplate(schedule) {

        getTemplate('reject.html', function(render) {
                        var renderedhtml = render({openday : openday});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
    }

    function activateEndUnderscoreTemplate(schedule) {


       getTemplate('end.html', function(render) {
                       var renderedhtml = render({openday: openday});
                       $("#waitingDiv").html(renderedhtml);
                       $("#opendayDetails").fadeIn();
                       $("#waitingDiv").fadeIn();
                       $("#candidate-interview").fadeOut();
       });
   }

   function activateMissedTemplate(schedule) {
       getTemplate('missed.html', function(render) {
                       var renderedhtml = render({openday: openday});
                       $("#waitingDiv").html(renderedhtml);
                       $("#opendayDetails").fadeIn();
                       $("#waitingDiv").fadeIn();
                       $("#candidate-interview").fadeOut();
       });
   }

   function sendChat() {
     var message = $('#message').val();
     if(onInterview) {
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


   function getCurrentUTCTime() {
      return moment().utc();
   }

})($);
$(candidateScreenManagement.init);


//
