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
            console.log(res);
            if(res.length > 0) {
                $(".no-results").fadeOut();
            }
            else {
                $(".no-results").fadeIn();
            }
              for (var i = 0; i < res.length; i++) {
                  
                  result = JSON.parse(res[i].personal_info);
                  time = res[i];
                  getTemplate("item.html", function(render){
                    var html = render({ data: result, time: time });
                    $("#candidateList").append(html);
                  });
              }
        });
    }


    function viewCV() {
        var userId = $(this).data('id');

          getTemplate("profile.html", function(render){
            var html = render({ data: result, time: time });
            $("#opendayProfile").html(html);
          });
              
      

    }

    function rejectCandidate() {
        var conf = confirm("Are you sure you want to reject this candidate?");
        if(!conf) return false;
        var userId = $(this).data('id');
        var opendayId = $("#opendayId").val();

        $.post(apiUrl + '/openday/' + opendayId + '/reject', { user_id: userId }, function(res) {
            $("#candidate-" + userId).remove();
        });
    }

   









})($);
$(opendayCandidatesScreenManagement.init);
