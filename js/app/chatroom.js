var candidateScreenManagement = (function($) {
    var timer;
    var templates = [],
        baseTemplateUrl = 'template/chatroom/';
    var currentApplicant = 0;
    var pendingApplicant = 0;

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

        // Extend Hours Compute Total Price
         $("#extend-hours-input").on('keyup', computeTotalPrice);
    }
    

    // Socket IO

    function socketIOEventHandlers() {

        // Notifications
        window.socket.on("room-" + getCurrentRoom(), function(data){

         console.log('here');
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
      addCandidate(2);

    }

    /* Add Candidate in the Queue 
       param applicantId - user id of the applicant/candidate
    */

    function addCandidate(applicantId) {
      /* Do AJAX Stuff here to get the user details and candidate detail */
       var user = { id: applicantId, name: 'Jane Doe' };
       var candidate_no = 1;
       getTemplate('queue_item.html', function(render) {
            var renderedhtml = render({user: user, room_id: getCurrentRoom(), candidate_no: candidate_no});
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
        var message = "Your interview is now ready";
        if (!location.origin) {
           location.origin = location.protocol + "//" + location.host;
        }
        var link = location.origin + "/candidate";

        /* Before Running code below do an ajax to update the server that the candidate has been invited */
        $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "invite", message: message, link: link}, function (data){
            $("#inviteModal").modal("show");
            pendingApplicant = userId;
        });

    }


    function rejectCandidate() {
        var userId = $(this).data('user');
        var roomId = $(this).data('room');
        var message = "Sorry your application has been rejected";
        if (!location.origin) {
           location.origin = location.protocol + "//" + location.host;
        }
        var link = location.origin + "/candidate";
        $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "reject", message: message, link: link}, function (data){
            removeCandidate(userId);
            // TODO: Add AJAX here to record that the candidate has been rejected in the DB.

        });
    }



    function computeTotalPrice() {
      var rate = $("#rate").val();
      var hours = $(this).val();
      var totalPrice = hours * rate;
      totalPrice = (totalPrice).formatMoney(2, '.', ',');
      $("#totalPrice").html(totalPrice);


    }



 

})($);
$(candidateScreenManagement.init);