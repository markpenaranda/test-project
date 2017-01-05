var discoverJobsManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/discover-jobs/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php/';
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

      $("#search").on('keyup', searchEvent);

      $("#resultsUl").on('click', '.view-details', viewDetails);

      $("#resultDetails").on('click', '.jg-sched-available', selectSchedule);

      $("#resultDetails").on('click', '#joinSubmit', join);
      // $("#resultDetails").on('click', '#closeJoinModal', closeJoinModal);

      $('#resultDetails').on('hidden.bs.modal', '#joinModal', closeJoinModal);

    }

    function initialTemplate() {

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
          });
      }
    }

    function loadResults(results) {
        $("#resultsUl").html('');

        for (var i = 0; i < results.length; i++) {
          var result = results[i];

          getTemplate("results.html", function(render){
            var html = render({ data: result });
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

        getTemplate("details.html", function(render){
          var html = render({ data: res });
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

      $("#timeBreakdownStart").html(selectedSchedule.data("start"));
      $("#timeBreakdownEnd").html(selectedSchedule.data("end"));

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

      $.post(apiUrl + '/openday/' + opendayId + '/join', data, function(res){
          $(this).find(".loading").fadeOut();
          $("#joinSuccess").fadeIn();
          $("#joinForm").fadeOut();
         
         
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


    function getCurrentUser() {

    }






})($);
$(discoverJobsManagement.init);
