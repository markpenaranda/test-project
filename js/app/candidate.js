var candidateScreenManagement = (function($) {
    var timer;
    var templates = [],
        baseTemplateUrl = 'template/candidate/';
    var onInterview = false;
    var apiUrl = '/api/v1/public/index.php';
    var openday = null;

    return {
        init: init
    };

    // Init
    function init() {
        loadOpendayDetail();
        socketIOEventHandlers();
        addEventHandlers();
        renderNecessaryTemplate();
    }

    // Event Handler
    function addEventHandlers() {

        // Join Button
        $("#waitingDiv").on('click','#joinInterview', activateCandidateInterview);

        // Chat Functions
        $('.send').on('click', sendChat);
        $('#message').keypress(sendChatOnEnter);
    }


    // Socket IO

    function socketIOEventHandlers() {

        // Notifications
        window.socket.on("notifier-" + $("#userId").val(), function(data){

        if(data.category == "candidate") {
             if(data.tag == "invite") {
                activateJoinUnderscoreTemplate();
             }
             if(data.tag == "reject") {
                activateRejectUnderscoreTemplate();
             }

             if(data.tag == "end") {
                onInterview = false;
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


    function loadOpendayDetail() {
        $.get(apiUrl + '/openday/' + getCurrentRoom(), function(opendayRes){
            openday = opendayRes;
            getTemplate('openday-detail.html',function(render) {
                var html = render({data: openday});
                $("#opendayDetails").html(html);
                $(".companyName").html(openday.page_name);
            });
        });
    }

    function getCurrentUser() {
        var userId = $('#userId').val();
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
          var userId = $("#userId").val();

          $.get(apiUrl + '/openday/' + opendayId + '/schedule?user_id=' + userId, function(schedule) {
            var scheduleStatus = parseInt(schedule.status);
            var isScheduled = (schedule.is_scheduled === '1');
            // Waiting and Scheduled
            if(scheduleStatus == 1 && isScheduled) {
                activateScheduledUnderscoreTemplate(schedule);
            }

            // Waiting not Scheduled
             if(scheduleStatus == 1 && !isScheduled) {
                activateWaitingListUnderscoreTemplate(schedule);
            }

            // Reject

            if(scheduleStatus == 3) {
                activateRejectUnderscoreTemplate(schedule);
            }

            // End

            if(scheduleStatus == 2) {
                activateEndUnderscoreTemplate(schedule);
            }

            // Interviewing
            if(scheduleStatus == 0) {
                activateJoinUnderscoreTemplate(schedule);
            }

          });
     
    }


    function activateCandidateInterview() {
        onInterview = true;
        $.get(window.liveServerUrl + "/room/" + getCurrentRoom() + "/accept", {user_id: getCurrentUser(), candidate_no: getCandidateNo()}, function(){
            $("#opendayDetails").fadeOut();
            $("#waitingDiv").fadeOut();
            $("#candidate-interview").fadeIn();
        });


    }


    function activateWaitingListUnderscoreTemplate(schedule) {
       
        getTemplate('waiting.html', function(render) {
                        var renderedhtml = render({data:schedule});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
    }

    function activateScheduledUnderscoreTemplate(schedule) {
       
        getTemplate('scheduled.html', function(render) {
                        var renderedhtml = render({data:schedule});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
    }

    function activateJoinUnderscoreTemplate(schedule) {
       
 
        getTemplate('join.html', function(render) {
                        var renderedhtml = render({schedule: schedule, openday: openday});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
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
                       var renderedhtml = render({openday: openday, company_name : company_name});
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

})($);
$(candidateScreenManagement.init);
