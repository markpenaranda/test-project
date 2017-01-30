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
    var selectedOpenday = null;
    var coverLetters = null;
    var selectedTime = {
        time_start: null,
        time_end: null,
        id: null,
        candidate_number: null
      }
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

      // $("#search").on('keyup', searchEvent);

      $("#resultsUl").on('click', '.view-details', viewDetails);

      $("#resultDetails").on('click', '.jg-sched-available', selectSchedule);

      $("#resultDetails").on('click', '#joinSubmit', join);
      // $("#resultDetails").on('click', '#closeJoinModal', closeJoinModal);

      $('#resultDetails').on('hidden.bs.modal', '#joinModal', closeJoinModal);

    
      $("#resultDetails").on('click', '#joinFormBtn', openJoinForm);
      $("#resultDetails").on('change', '#oldCoverLetterSelect', updateCoverLetterField);
      $("#resultDetails").on('click', ".join-form-cancel", closeJoinForm);

      $("body").on('click', '#back', back);

      $(window).on('resize', function(){
        if($(window).width() > 768) {
           $("#resultsUl").removeClass("animated bounceOutLeft hide_me").addClass("animated bounceInLeft");
          $("#resultDetails").removeClass("animated bounceOutRight hide_me").addClass("animated bounceInRight");
        
        }

         if($(window).width() < 768) {
           $("#resultsUl").removeClass("animated bounceOutLeft hide_me").addClass("animated bounceInLeft");
           $("#resultDetails").removeClass("animated bounceInRight").addClass("animated bounceOutRight hide_me");
            $("#back").addClass("hide_me");
         }
      });
      $('#search').tagEditor({ 
          delimiter: ',', /* space and comma */
          autocomplete: { 'source': apiUrl + '/resources/keyword', delay: 1000 },
          placeholder: 'Type your keywords and press enter',
          // forceLowercase: false,
          onChange: searchEvent
      });

      $("#search").on('change', manualSearch);

    
    }



 
    function initialTemplate() {
      loadAccountInfo();
      loadCoverLetter();
      initialOpenday();
    }

    function loadCoverLetter() {
      $.get(apiUrl + '/openday/cover-letters?user_id=' + getCurrentUserId(), function(cover_letter) {
        coverLetters = cover_letter;

        console.log(coverLetters);
      });
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
      $.get(apiUrl + '/openday', function(res) {
        $("#resultsContainer").fadeIn();
        
        $("#resultsSize").html(res.count_result);
        $("#totalResults").html(res.total);
        loadResults(res.results);
         $(".loading-results").fadeOut();
      });

    }

    function manualSearch(event) {
       var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            searchEvent($(this));
        }
       if($(this).val().length == 0) {
        initialOpenday();
        }
    }

    function searchEvent(input) {
      $(".loading-results").fadeIn();
      var q = input.val();
      if(q.length == 0) {
        initialOpenday();
      }
      if (q.length >= 3 ) {
          if (searchRequest != null)
              searchRequest.abort();
          var searchRequest = $.get(apiUrl + "/openday/search?q=" + q, function(res){
            $(".loading-results").fadeOut();
            $("#resultsContainer").fadeIn();


              $("#resultsSize").html(res.count_result);
              $("#totalResults").html(res.total);
              loadResults(res.results);
              $(".jg-load-on-scroll ").fadeOut();
          });
      }
    }

    function loadResults(results) {
        $("#resultsUl").html('');

        for (var i = 0; i < results.length; i++) {
        
            loadResultItem(results[i]);
         
          
        }
    }

    function loadResultItem(result) {
       var event_date = moment(result.event_date).format("MMMM D YYYY");
      getTemplate("results.html", function(render){
             
            var html = render({ data: result, event_date: event_date });
            $("#resultsUl").append(html);
          });
    }

    function viewDetails() {
      var id = $(this).data('id');
      selectedOpendayId = $(this).data('id');

      if ( $(window).width() < 768 ) {
        $("#resultsUl").removeClass("animated bounceInLeft").addClass("animated bounceOutLeft hide_me");
        $("#back").removeClass("hide_me");
      
      }
      loadDetails(selectedOpendayId);
    }

    function back() {
       $("#resultDetails").removeClass("animated bounceInRight").addClass("animated bounceOutRight hide_me"); 
       $("#resultsUl").removeClass("animated bounceOutLeft hide_me").addClass("animated bounceInLeft");
       $(this).addClass("hide_me");
    }

    function loadDetails(id) {

      $.get(apiUrl + "/openday/" + id + "?user_id=" + getCurrentUserId(), function(res){
        console.log(res);
        selectedOpenday = res;
        var created = moment(res.date_created).fromNow();
        var event_date = moment(res.event_date).format("MMMM D YYYY");
         var start_time = moment("2013-02-08 " + res.start_time).format("HH:mm");
         var end_time = moment("2013-02-08 " + res.end_time).format("HH:mm");
        // console.log(created);

        getTemplate("details.html", function(render){
          var html = render({ user: accountInfo,
                              data: res, created_at: created, 
                              event_date: event_date, 
                              start_time:  start_time,
                              end_time: end_time,
                              applied: res.applied,
                              schedule: res.schedule
                          });
          $("#resultDetails").html(html);
            if ( $(window).width() < 768 ) {
             $("#resultDetails").removeClass("animated bounceOutRight hide_me").addClass("animated bounceInRight");
        
            }
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
      selectedTime.time_start = moment("1992-09-03 " + selectedSchedule.data("start")).format("hh:mmA");
      selectedTime.time_end = moment("1992-09-03 " + selectedSchedule.data("end")).format("hh:mmA");
      selectedTime.id = selectedSchedule.data("id");
      selectedTime.candidate_number = selectedSchedule.data("candidate_number");
    }


    function openJoinForm() {
       getTemplate("join-form.html", function(render){
          var html = render({
            user: accountInfo,
            selectedSchedule: selectedTime,
            openday: selectedOpenday,
            coverLetters: coverLetters
          });
          $("#resultDetails").html(html);
        });
    }

    function closeJoinForm() {
      loadDetails(selectedOpenday.openday_id);
    }

    function join() { 
      $(this).attr("disabled", true);
      $(this).find(".loading").fadeIn();
      var userId = $("#userId").val();
      var timeBreakdownId = selectedTime.id;
      var opendayId = selectedOpendayId;
      var data = {
        user_id : userId,
        openday_time_breakdown_id: timeBreakdownId,
        cover_letter: $("#coverLetter").val(),
        cover_letter_title: $("#coverLetterTitle").val(),
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
         loadDetails(selectedOpendayId);
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
      loadDetails(selectedOpendayId);


    }


    function getCurrentUserId() {
      return $("#userId").val();
    }

    function updateCoverLetterField() {
      var cl = $(this).val();
      var clt = $(this).find('option:selected').data('title');
      $("#coverLetter").val(cl);
      $("#coverLetterTitle").val(clt);
    }





})($);
$(discoverJobsManagement.init);
