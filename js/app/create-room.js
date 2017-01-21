var createRoomScreenManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/create-room/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    var numberOfTimeRange = 0;

    var FORM_REQUIRED_FIELDS = [
        "event_name",
        "event_date",
        "time_interval_per_candidate"
    ];

    return {
        init: init,
        addForm: addForm
    };

    // Init
    function init() {
        initialTemplate();
        addEventHandlers();
        validate([], 1);
    }

    function getCurrentUserId() {
        return $("#userId").val();
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

      // job
      $("#interviewPostFormContainer").on('click', '.add-job-btn', addNewJob);

      $("#interviewPostFormContainer").on('click', '.remove-job-btn', removeJob);

      $("#interviewPostFormContainer").on('click', '.add-time-range', addTimeRange);

      $("#interviewPostFormContainer").on('click', '.remove-time-range', removeTimeRange);

      $("#interviewPostFormContainer").on('change', '.timerange-end', reinitializeOtherTimeRange);


      $("#mainCreateRoomRow").on('click', '.edit-room-btn', backToTop);

      $("#submit").on('click', submit);

      $("#preview").on('click', preview);

      $("#continue").on('click', proceedToCheckout);

      $("#cancel").on('click', backToTop);

      // $("#interviewPostFormContainer").on("keyup", "input", validate);

      $("#createdList").on('change', useOldEvent);
    }

    function initialTemplate() {
      addForm();
      addNewJob();
      addTimeRange();
      loadResponsibleUserOption();
      loadPastCreated();
    }

    function loadPastCreated() {

      $.get(apiUrl + '/openday/created?user_id='+ getCurrentUserId(), function(res) {
          console.log(res);
          for (var i = 0; i < res.length; i++) {
            
            var html = "<option value='"+ res[i].openday_id +"'>"+ res[i].event_name +"</option>"
            $("#createdList").append(html);
          }
      });
    }

    // function validate() {
    //         console.log($(this).val());
    //   var empty = false;
    //     $('input').each(function() {
    //         if ($(this).val() == '') {
    //             empty = true;
    //         }
    //     });

    //     if (empty) {
    //         $('#preview').attr('disabled', 'disabled'); 
    //         $('#preview').addClass('disabled'); 
    //     } else {
    //         $('#preview').removeAttr('disabled'); 
    //         $('#preview').removeClass('disabled'); 
    //     }
    // }

    function useOldEvent() {
      var opendayId = $(this).val()
      $.get(apiUrl + '/openday/' + opendayId, function(openday) {
        $("#eventName").val(openday.event_name); 
        $("#timeIntervalPerInterview").val(openday.time_interval_per_candidate);
        $("#introduction").val(openday.introduction);

      });

    }

    function addForm() {
      getTemplate('room-form.html', function(render){
          var html = render({room_number: numberOfRooms});
          $('#interviewPostFormContainer').append(html);
          $('.time-range-pair .time').timepicker({
            showDuration: true,
            timeFormat: 'g:ia'});
          $('.time-range-pair .time').timepicker(
            'option', {
              useSelect: true,

            });
          // $('.time-range-pair').datepair();
          $('.datepicker').datepicker();
          $('.ui-timepicker-select').addClass('col-lg-12');
          ++numberOfRooms;

       
      });
    }

    function addNewJob() {
      $.get(apiUrl + '/resources/filterjob', function(res) {
        getTemplate('partials/select-job.html', function(render){
          var html = render({jobs: res.data, room_number: numberOfRooms - 1});
          $(".add-job-btn").css("display", "none");
          $("a.remove-job-btn").css("display", "block");
            $('group#jobSelect').append(html);

            $("a.remove-job-btn").last().css("display", "none");
            $("a.add-job-btn").last().css("display", "block");
        });
      });

    }

    function removeJob() {
      $(this).parent().parent().remove();
    }

    function loadResponsibleUserOption() {
      var userId = getCurrentUserId();
      $.get(apiUrl + '/users/' + userId + '/workmates', function(res) {
          for(var x = 0; x < res.length; x++) {
              var user = res[x];
              var html = "<option class='in-charge-option-"+ (numberOfRooms - 1) +"' value='" + user.user_id + "'>"+ user.name +"</option>";
              $("#in-charge-" + (numberOfRooms - 1)).append(html);
          }
      });

    }


    function addTimeRange() {
      numberOfTimeRange += 1;
      getTemplate('partials/input-time-range.html', function (render) {
        var html = render({ room_number: numberOfRooms - 1, time_count: numberOfTimeRange});
        var lastEndTime = $('.end').last().val();

        $("#timeRangeList").append(html);

        $("a.add-time-range").css("display", "none");
        $("a.remove-time-range").css("display", "block");
        $("a.remove-time-range").last().css("display", "none");
        $("a.add-time-range").last().css("display", "block");

        timePickerInit(lastEndTime, numberOfRooms - 1, numberOfTimeRange);
        });
    }

    function timePickerInit(lastEndTime, room, timecount) {
      var disableRange = ['12am', lastEndTime];
      console.log('.time-range-pair-' + room  +'-'+  timecount +' .time');
      $('.time-range-pair-' + room  +'-'+  timecount +' .time').timepicker({
        showDuration: true,
        timeFormat: 'g:ia',
        disableTimeRanges: [disableRange]
      });
      $('.time-range-pair .time').timepicker(
        'option', {
          useSelect: true,

        });
      $('.time-range-pair').datepair();
      $('.ui-timepicker-select').addClass('col-lg-12');

      return $("#time-end-" + timecount).val();

    }

    function removeTimeRange() {
      $(this).parent().parent().parent().parent().remove();
    }

    function validate(data, roomNumber) 
    {   
        var valid = true;
        $.each(FORM_REQUIRED_FIELDS, function(i, field){
            if(data[field] == "" || data[field] == undefined || data[field] == 0) {
              $("#" + field + "-required-" + roomNumber).fadeIn();
              valid = false;
            }
            else {
               $("#" + field + "-required-" + roomNumber).fadeOut();
            }
        });

        return valid;
    }

    function reinitializeOtherTimeRange() {
      var room_triggered = $(this).data('room');
      var timecount_triggered = $(this).data('timecount');
      var timeEnd = $(this).val();
      var counter = timecount_triggered + 1;
      for(counter; counter <= numberOfTimeRange; counter++) {
        console.log(counter);
         $("#time-start-" + counter).val(timeEnd);
         $("#time-end-" + counter).val(timeEnd);
         timeEnd = timePickerInit(timeEnd, room_triggered, counter);
       
      }      
    }

    function preview() {
      // $(".room-form").fadeOut();
      dataStore = [];
      for (var i = 0; i < numberOfRooms; i++) {
        var name = $("input[name='event\\["+ i +"\\]\\[\\'name\\'\\]").val();
        var date = $("input[name='event\\["+ i +"\\]\\[\\'date\\'\\]").val();
        var interval = $("input[name='event\\["+ i +"\\]\\[\\'time_interval\\'\\]").val();
        var in_charge_user_id = $("select[name='event\\["+ i +"\\]\\[\\'in_charge_user_id\\'\\]").val();
        var in_charge_name = $(".in-charge-option-"+i+":selected").html();
        var job_type = $(".job-type-radio-"+ i + ":checked").val();
        var jobs = $("select[name^='jobs["+ i  +"]']").map(function(){  var item = JSON.parse($(this).val()); return item.id;}).get();
        var jobNames = $("select[name^='jobs["+ i  +"]']").map(function(){  var item = JSON.parse($(this).val()); return item.name;}).get();
        var timerange = $(".time-range-pair-" + i).map(function(){return {"start" : $(this).find(".start").val(), "end" : $(this).find(".end").val()} }).get();
        var introduction = $("textarea[name='event\\["+ i +"\\]\\[\\'introduction\\'\\]").val();
        var data = {
          "event_name": name,
          "event_date": date,
          "time_interval_per_candidate": interval,
          "jobs": jobs,
          "jobNames": jobNames,
          "job_type": job_type,
          "timerange": timerange,
          "introduction": introduction,
          "in_charge_user_id": in_charge_user_id,
          "in_charge_name": in_charge_name
        };

        if(!validate(data, i)) {
          return false;
        }

        dataStore.push(data);

        getTemplate('room-list.html', function(render){
            var html = render({data: data});
            $("#interviewPostFormContainer").fadeOut();
            $('#mainCreateRoomRow').prepend(html);

        });

        $("#preview").fadeOut();
        $("#continue").fadeIn();

      }
    }



    function proceedToCheckout() {
      $(".room-list").fadeOut();
      console.log(dataStore[0].timerange[0].start);
      var totalHours = 0;
      for(var x=0; x < dataStore.length; x++) {
        var data = dataStore[x];
        for(var y=0; y < data.timerange.length; y++) {
          var timerange = data.timerange[y];
          var diff = countDifference(timerange.start, timerange.end);
          totalHours += diff;
        }

      }

      $.get(apiUrl + '/openday/current-rate', function(rate) {
        var rate = parseInt(rate);
        var totalPrize = rate * totalHours;
        getTemplate('checkout-form.html', function(render){
            var html = render({
              total_hours: totalHours,
              rate: rate,
              total_prize: totalPrize
            });
            $("#interviewPostFormContainer").fadeOut();
            $('#mainCreateRoomRow').prepend(html);
            $("#continue").fadeOut();
            $("#submit").fadeIn();
        });

      })


    }

    function countDifference(start, end) {
        var totalHours = 0;
        var start = moment('2012-10-09 ' + start, "YYYY-MM-DD h:mma");
        var end = moment('2012-10-09 ' + end, "YYYY-MM-DD h:mma");

        var difference = end.diff(start, 'minutes');
        totalHours = (difference/60);

        return totalHours;
    }


    function backToTop() {
      $(".room-form").fadeIn();
        $("#interviewPostFormContainer").fadeIn();
      $(".room-list").remove();
      $(".checkout-form").remove();

      $("#preview").fadeIn();
      $("#continue").fadeOut();
      $("#submit").fadeOut();
    }

    function submit() {
      for (var i = 0; i < dataStore.length; i++) {
        var data = dataStore[i];
        data.created_by_user_id = getCurrentUserId();
        $.post(apiUrl + "/openday", data, function(res) {
          document.location = "create-success.php?openday=" + res;
        });
      }

    }






})($);
$(createRoomScreenManagement.init);
