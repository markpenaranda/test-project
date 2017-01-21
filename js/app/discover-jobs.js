var discoverJobsManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/discover-jobs/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    var selectedSchedule = null;
    var selectedOpendayId = null;
    var accountInfo = null;
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

      $("#search").on('keyup', searchEvent);

      $("#resultsUl").on('click', '.view-details', viewDetails);

      $("#resultDetails").on('click', '.jg-sched-available', selectSchedule);

      $("#resultDetails").on('click', '#joinSubmit', join);
      // $("#resultDetails").on('click', '#closeJoinModal', closeJoinModal);

      $('#resultDetails').on('hidden.bs.modal', '#joinModal', closeJoinModal);

    }

    function initialTemplate() {
      loadAccountInfo();
      initialOpenday();
    }


    function loadAccountInfo() {
      $.get(apiUrl + "/users/" + getCurrentUserId(), function(user) {
          accountInfo = user;
          getTemplate("account-info.html", function(render){
            var html = render({ data: user });
            $("#accountInfo").html(html);
          });
      });
    }

    function initialOpenday() {
      $.get(apiUrl + '/openday', function(events) {
        $("#resultsContainer").fadeIn();
        loadResults(events);
      });

    }

    function searchEvent() {
      $(".loading-results").fadeIn();
      var q = $(this).val();
      if (q.length >= 3 ) {
          if (searchRequest != null)
              searchRequest.abort();
          var searchRequest = $.get(apiUrl + "/openday/search?q=" + q, function(res){
            $(".loading-results").fadeOut();
            $("#resultsContainer").fadeIn();


              $("#numberOfResults").html(res.length);
              loadResults(res);
              $(".jg-load-on-scroll ").fadeOut();
          });
      }
    }

    function loadResults(results) {
        $("#resultsUl").html('');

        for (var i = 0; i < results.length; i++) {
          var result = results[i];
          var event_date = moment(results[i].event_date).format("MMMM D YYYY");
          getTemplate("results.html", function(render){
            var html = render({ data: result, event_date: event_date });
            $("#resultsUl").append(html);
          });
        }
    }

    function viewDetails() {
      var id = $(this).data('id');
      selectedOpendayId = $(this).data('id');
      loadDetails(selectedOpendayId);
    }

    function loadDetails(id) {

      $.get(apiUrl + "/openday/" + id, function(res){
        console.log(res);
        var created = moment(res.date_created).fromNow();
        var event_date = moment(res.event_date).format("MMMM D YYYY");
         var start_time = moment("2013-02-08 " + res.start_time).format("hh:mmA");
         var end_time = moment("2013-02-08 " + res.end_time).format("hh:mmA");
        // console.log(created);
        getTemplate("details.html", function(render){
          var html = render({ user: accountInfo,
                              data: res, created_at: created, 
                              event_date: event_date, 
                              start_time:  start_time,
                              end_time: end_time
                          });
          $("#resultDetails").html(html);
        });
      });
    }


    function selectSchedule() {
      console.log("here");
      $(".jg-sched-available").removeClass("selected");

      $(this).addClass("selected");

      selectedSchedule = $(".jg-available-sched-list>li.selected");

      $(".jg-btn-join").removeClass("jg-btn-grey");
      $(".jg-btn-join").addClass("btn-success");
      $(".jg-btn-join").addClass("jg-btn");

      $("#timeBreakdownStart").html(moment("1992-09-03 " + selectedSchedule.data("start")).format("hh:mmA"));
      $("#timeBreakdownEnd").html(moment("1992-09-03 " + selectedSchedule.data("end")).format("hh:mmA"));


    }


    function join() { 
      $(this).attr("disabled", true);
      $(this).find(".loading").fadeIn();
      var userId = $("#userId").val();
      var timeBreakdownId = selectedSchedule.data("id");
      var opendayId = selectedOpendayId;
      var data = {
        user_id : userId,
        openday_time_breakdown_id: timeBreakdownId,
        cover_letter: $("#coverLetter").val(),
        time_start: selectedSchedule.data('start'),
        time_end: selectedSchedule.data('end')
      }

  


      $.ajax({
        url: apiUrl + '/openday/' + opendayId + '/join',
        data: data,
        type: 'post',
        success: function(res){
          $(this).find(".loading").fadeOut();
          $("#joinSuccess").fadeIn();
          $("#joinForm").fadeOut();
          $("#joinSubmit").fadeOut();
         
        },
        error:  function (error) {
          $("#joinError").fadeIn();
            $("#joinForm").fadeOut();
             $("#joinSubmit").fadeOut();
        }
        });

    }

    function closeJoinModal() {
      console.log('hre');
       $.get(apiUrl + "/openday/" + selectedOpendayId, function(res){

        getTemplate("details.html", function(render){
          var html = render({ data: res });
          $("#resultDetails").html(html);

        });
      });


    }


    function getCurrentUserId() {
      return $("#userId").val();
    }






})($);
$(discoverJobsManagement.init);
