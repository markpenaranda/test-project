var opendayCandidatesScreenManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/openday-candidates/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    var selectedSchedule = null;
    var selectedOpendayId = null;
    return {
        init: init
    };

    // Init
    function init() {
        initialTemplate();
        addEventHandlers();
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


    // Event Handler
    function addEventHandlers() {
        $("#candidateList").on('click', '.view_btn', viewCV);
        $("#candidateList").on('click', '.reject_btn', rejectCandidate);

        $("#opendayList").on('change', viewOpenday);
    }

    function initialTemplate() {
      loadOpendayList();
      loadScheduledCandidates();
    }

    function getCurrentUserId() {
        return $("#userId").val();
    }

    function loadOpendayList() {
        $.get(apiUrl + '/openday/created?user_id=' + getCurrentUserId(), function(results) {
            for(var x = 0; x < results.length; x++) {
                var event = results[x];
                var html  = "<option value='" + event.openday_id  +"'>"+ event.event_name +"</option>";
                $("#opendayList").append(html);
            }
        });
    }

    function viewOpenday() {
        var opendayId = $(this).val();
        $("#opendayId").val(opendayId);
        loadOpendayDetails();
        loadScheduledCandidates();
        
    }

    function loadOpendayDetails() {

        var opendayId = $("#opendayId").val();

        $.get(apiUrl + '/openday/' + opendayId, function(res){
              getTemplate("openday-details.html", function(render){
                  var html = render({ data: res });
                  $("#opendayDetails").html(html);
                });
        });
    }


    function loadScheduledCandidates() {
        var opendayId = $("#opendayId").val();
        
         $("#candidateList").html("");
        $.get(apiUrl + '/openday/' + opendayId + '/candidates?is_scheduled=1', function(res){
            
            if(res.length > 0) {
                $(".no-results").fadeOut();
            }
            else {
                $(".no-results").fadeIn();
            }
              for (var i = 0; i < res.length; i++) {
                  
                 personal_info = JSON.parse(res[i].personal_info);
                 schedule = res[i];
                 time = schedule;
                 loadCandidateItem(schedule, personal_info);
              }
        });
    }

    function loadCandidateItem(schedule, personal_info) {

          getTemplate("item.html", function(render){
            var html = render({ data: personal_info, time: schedule });
            $("#candidateList").append(html);
          });
    }


    function viewCV() {
        var userId = $(this).data('id');
        $.get(apiUrl + '/users/' + userId, function(result) {
          getTemplate("profile.html", function(render){
            var html = render({ data: result, time: time });
            $("#opendayProfile").html(html);
          });
            
        })
              
      

    }

    function rejectCandidate() {
        var conf = confirm("Are you sure you want to reject this candidate?");
        if(!conf) return false;
        var userId = $(this).data('id');
        var opendayId = $("#opendayId").val();
         var message = "Sorry your application has been rejected";
         var link = location.origin + "/interview.php?openday=" + opendayId;
        $.post(apiUrl + '/openday/' + opendayId + '/reject', { user_id: userId }, function(res) {
            $("#candidate-" + userId).remove();
            $.get(window.liveServerUrl + "/notifier/" + userId, {category: "candidate", tag: "reject", message: message, link: link}, function (data){
           
              $("#candidate-" + userId).remove();
            });
        });
    }

   









})($);
$(opendayCandidatesScreenManagement.init);
