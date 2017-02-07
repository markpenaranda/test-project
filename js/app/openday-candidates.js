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

        $("body").on('click', '#back', back);

         $(window).on('resize', windowResize);

         $("#candidateSchedule").on('change', loadScheduledCandidates);

    }

    function windowResize() {
        if($(window).width() > 768) {
           $("#candidatesResultList").removeClass("animated bounceOutLeft xs-hide-me").addClass("animated bounceInLeft");
          $("#opendayProfile").removeClass("animated bounceOutRight xs-hide-me").addClass("animated bounceInRight");
        
        }

         if($(window).width() < 768) {
           $("#candidatesResultList").removeClass("animated bounceOutLeft xs-hide-me").addClass("animated bounceInLeft");
           $("#opendayProfile").removeClass("animated bounceInRight").addClass("animated bounceOutRight xs-hide-me");
            $("#back").addClass("xs-hide-me");
         }
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
        $("#opendayProfile").html("<p class='click-to-view-info centered-info' ><i class='fa fa-search fa-fw'></i>Click 'View CV' to review candidates.</p>");
        
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
        var is_scheduled = $("#candidateSchedule").val()
         $("#candidateList").html("");
        $.get(apiUrl + '/openday/' + opendayId + '/candidates?is_scheduled=' + is_scheduled, function(res){
            
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
            if($(window).width() < 768) {
            $("#candidatesResultList").removeClass("animated bounceInLeft").addClass("animated bounceOutLeft xs-hide-me");
            $("#opendayProfile").removeClass("animated bounceOutRight xs-hide-me").addClass("animated bounceInRight");
            $("#back").removeClass("xs-hide-me");
          }
          });
            
        })
              
      

    }

    function back() {
       $("#opendayProfile").removeClass("animated bounceInRight").addClass("animated bounceOutRight xs-hide-me"); 
       $("#candidatesResultList").removeClass("animated bounceOutLeft xs-hide-me").addClass("animated bounceInLeft");
       $(this).addClass("xs-hide-me");
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
