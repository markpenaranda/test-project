var candidateScreenManagement = (function($) {
    var timer;
    var templates = [],
        baseTemplateUrl = 'template/candidate/';

    return {
        init: init
    };

    // Init
    function init() {
        renderNecessaryTemplate();
        socketIOEventHandlers();
        addEventHandlers();
    }

    // Event Handler
    function addEventHandlers() {

        // Join Button
        $("#waitingDiv").on('click','#joinInterview', activateCandidateInterview);
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
        // Do ajax stuff here and activate the necessary template
        activateScheduledUnderscoreTemplate();
    }

    function activateCandidateInterview() {
        $.get(window.liveServerUrl + "/room/" + getCurrentRoom() + "/accept", {user_id: getCurrentUser(), candidate_no: getCandidateNo()}, function(){
            $("#room-details").fadeOut();
            $("#waitingDiv").fadeOut();
            $("#candidate-interview").fadeIn();
        });


    }



    function activateScheduledUnderscoreTemplate() {
        // Do ajax stuff here
        getTemplate('scheduled.html', function(render) {
                        var renderedhtml = render();
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
    }

    function activateJoinUnderscoreTemplate() {
        // Do ajax stuff here
        var company_name = "XYZ Company";
        getTemplate('join.html', function(render) {
                        var renderedhtml = render({company_name : company_name});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
    }

     function activateRejectUnderscoreTemplate() {
        // Do ajax stuff here
        var company_name = "XYZ Company";
        getTemplate('reject.html', function(render) {
                        var renderedhtml = render({company_name : company_name});
                        $("#candidate-interview").fadeOut();
                        $("#waitingDiv").html(renderedhtml);
        });
    }

    function activateEndUnderscoreTemplate() {
       // Do ajax stuff here
       var company_name = "XYZ Company";
       getTemplate('end.html', function(render) {
                       var renderedhtml = render({company_name : company_name});
                       $("#waitingDiv").html(renderedhtml);
                       $("#room-details").fadeIn();
                       $("#waitingDiv").fadeIn();
                       $("#candidate-interview").fadeOut();
       });
   }

})($);
$(candidateScreenManagement.init);
