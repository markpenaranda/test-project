var createRoomScreenManagement = (function ($) {
  var timer;
  var baseTemplateUrl = 'template/create-room/';
  var templates = [];
  var numberOfRooms = 0;
  var apiUrl = '/api/v1/public/index.php';
  var dataStore = [];
  var numberOfTimeRange = 0;
  var currentRoom = 0;
  var selectedRoom = 0;
  var newRoomTrigger = false;
  var jobTypeChangeTrigger = false;
  var minimumTimeIntervalPerCandidate = 0;
  
  var jobList = {
    "full_time" : [],
    "part_time" : [],
    "temporary" : []
  }

  var jobListId = {
    "full_time" : "1",
    "part_time" : "2",
    "temporary" : "3"
  }

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
    var localStorageUser = localStorage.getItem('userId');
    if (localStorageUser) {
      return localStorageUser;
    }

    return $("#userId").val();
  }

  // Get Template
  function getTemplate(templateName, callback) {
    if (!templates[templateName]) {
      $.get(baseTemplateUrl + templateName, function (resp) {
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
    $("#interviewPostFormContainer").on('change', '.timerange-end', toggleAddDeleteTimeRangeButton);


    $("#mainCreateRoomRow").on('click', '.edit-room-btn', backToTop);

    $("#submit").on('click', submit);

    $("#preview").on('click', preview);

    $("#continue").on('click', proceedToCheckout);

    $("#cancel").on('click', backToTop);

    // $("#interviewPostFormContainer").on("keyup", "input", validate);

    $("#createdList").on('change', useOldEvent);


    $("#interviewPostFormContainer").on('change', '.job-type-radio', jobTypeChange);

    $("#addRoom").on('click', addForm);

    $("#interviewPostFormContainer").on('click', ".remove-form-btn", removeForm);
  }

  function initialTemplate() {
    addForm();

    loadPastCreated();
  }

  function loadPastCreated() {

    $.get(apiUrl + '/openday/created?user_id=' + getCurrentUserId(), function (res) {
      // console.log(res);
      for (var i = 0; i < res.length; i++) {

        var html = "<option value='" + res[i].openday_id + "'>" + res[i].event_name + "</option>"
        $("#createdList").append(html);
      }
    });
  }


  function useOldEvent() {
    var opendayId = $(this).val()
    $.get(apiUrl + '/openday/' + opendayId, function (openday) {
      $("#eventName").val(openday.event_name);
      $("#timeIntervalPerInterview").val(openday.time_interval_per_candidate);
      $("#introduction").val(openday.introduction);

    });

  }

  function addForm() {
    getTemplate('room-form.html', function (render) {
      var html = render({
        room_number: numberOfRooms
      });
      $('#interviewPostFormContainer').append(html);
      $('.time-range-pair .time').timepicker({
        showDuration: true,
        timeFormat: 'g:ia'
      });
      $('.time-range-pair .time').timepicker(
        'option', {
          useSelect: true,

        });
      // $('.time-range-pair').datepair();
      $('.datepicker').datepicker();
      $('.ui-timepicker-select').addClass('col-lg-12');
      ++numberOfRooms;
      newRoomTrigger = true;
      addNewJob();
      addTimeRange();
      loadResponsibleUserOption();
      newRoomTrigger = false;
    });
  }

  function removeForm() {
    var roomNumber = $(this).data('room-number');
    console.log("#room-form-" + roomNumber);
    $("#room-form-" + roomNumber).remove();
    numberOfRooms -= 1;
  }

  function jobTypeChange() {
    // console.log("change");
    var roomNumber = $(this).data("room-number");
    selectedRoom = $(this).data("room-number");
    var currentRoom = numberOfRooms - 1;
    currentRoom = (currentRoom < 0) ? 0 : currentRoom;

    $('group#jobSelect-' + roomNumber).html('');
    jobTypeChangeTrigger = true;
    addNewJob();
    jobTypeChangeTrigger = false;

  }


  function addNewJob() {

    if (newRoomTrigger) {
      currentRoom = numberOfRooms - 1;
    } else {
      currentRoom = $(this).data("room-number");
    }

    if (jobTypeChangeTrigger) {
      currentRoom = selectedRoom;
    }

    currentRoom = (currentRoom < 0) ? 0 : currentRoom;

    console.log(currentRoom);
    // console.log(".job-type-radio-"+ currentRoom + ":checked");
    var employment_type =$(".job-type-radio-" + currentRoom + ":checked").val();
    var employment_type_id = jobListId[employment_type];
    
    if(jobList[employment_type].length == 0) {

      $.get(apiUrl + '/resources/filterjob?employment_type_id=' + employment_type_id, function (res) {
        jobList[employment_type] = res.data;
        getTemplate('partials/select-job.html', function (render) {
          var html = render({
            jobs: jobList[employment_type],
            room_number: currentRoom
          });
          // $(".add-job-btn").css("display", "none");
          $("a.remove-job-btn").css("display", "block");
          $('group#jobSelect-' + currentRoom).append(html);

          reinitializeJobOptionButtons(currentRoom);

        });
      });
    }
    else {
       getTemplate('partials/select-job.html', function (render) {
          var html = render({
            jobs: jobList[employment_type],
            room_number: currentRoom
          });
          // $(".add-job-btn").css("display", "none");
          $("a.remove-job-btn").css("display", "block");
          $('group#jobSelect-' + currentRoom).append(html);

          reinitializeJobOptionButtons(currentRoom);

        });
    }

  }

  function removeJob() {
    $(this).parent().parent().remove();
    var roomNumber = $(this).data("room-number");
    reinitializeJobOptionButtons(roomNumber);
  }

  function reinitializeJobOptionButtons(roomNumber) {
    $(".remove-job-btn-" + roomNumber).css("display", "block");
    $(".add-job-btn-" + roomNumber).css("display", "block");
    var countJobLink = $(".remove-job-btn-" + roomNumber).length;
    if (countJobLink == 1) {
      $(".remove-job-btn-" + roomNumber).first().css("display", "none");
    }
  }

  function loadResponsibleUserOption() {
    var userId = getCurrentUserId();
    $.get(apiUrl + '/users/' + userId + '/workmates', function (res) {
      for (var x = 0; x < res.length; x++) {
        var user = res[x];
        var html = "<option class='in-charge-option-" + (numberOfRooms - 1) + "' value='" + user.user_id + "'>" + user.name + "</option>";
        $("#in-charge-" + (numberOfRooms - 1)).append(html);
      }
    });

  }


  function addTimeRange() {
    numberOfTimeRange += 1;

    if (newRoomTrigger) {
      var currentRoom = numberOfRooms - 1;
    } else {
      var currentRoom = $(this).data("room-number");
    }

    // console.log(currentRoom);
    currentRoom = (currentRoom < 0) ? 0 : currentRoom;

    getTemplate('partials/input-time-range.html', function (render) {
      var html = render({
        room_number: currentRoom,
        time_count: numberOfTimeRange
      });
      var lastEndTime = $('#timeRangeList-' + currentRoom + ' .end').last().val();
      // console.log("#timeRangeList-" + currentRoom);
      $("#timeRangeList-" + currentRoom).append(html);

      $("a.add-time-range-" + currentRoom).css("display", "none");
      $("a.remove-time-range-" + currentRoom).css("display", "block");
      $("a.remove-time-range-" + currentRoom).last().css("display", "block");
      $("a.add-time-range-" + currentRoom).last().css("display", "none");

      if (numberOfTimeRange == 1) {
        $("a.remove-time-range").last().css("display", "none");
      }

      timePickerInit(lastEndTime, currentRoom, numberOfTimeRange);
    });
  }

  function timePickerInit(lastEndTime, room, timecount) {
    var disableRange = ['12am', lastEndTime];
    // console.log('.time-range-pair-' + room  +'-'+  timecount +' .time');
    $('.time-range-pair-' + room + '-' + timecount + ' .time').timepicker({
      showDuration: true,
      timeFormat: 'g:ia',
      disableTimeRanges: [disableRange]
    });
    $('.time-range-pair .time').timepicker(
      'option', {
        useSelect: true,

      });
    $('.time-range-pair').datepair();
    $('.ui-timepicker-select').addClass('col-lg-12 col-xs-12 col-sm-12 col-md-12 jg-select');

    return $("#time-end-" + timecount).val();

  }

  function removeTimeRange() {
    $(this).parent().parent().parent().parent().remove();
    $("a.add-time-range").last().css("display", "block");
    $("a.remove-time-range").last().css("display", "block");
  }

  function toggleAddDeleteTimeRangeButton() {
    // console.log($(this).data('timecount'));
    var timecount = $(this).data('timecount');
    var roomNumber = $(this).data('room');
    // console.log(".add-"+roomNumber+"-"+timecount);
    $(".add-" + roomNumber + "-" + timecount).css("display", "block");
    $(".remove-" + roomNumber + "-" + timecount).css("display", "none");

  }

  function validate(data, roomNumber) {
    var valid = true;
    $.each(FORM_REQUIRED_FIELDS, function (i, field) {
      if (data[field] == "" || data[field] == undefined || data[field] == 0) {
        $("#" + field + "-required-" + roomNumber).fadeIn();
        valid = false;
      } else {
        $("#" + field + "-required-" + roomNumber).fadeOut();
      }
    });
    console.log(data);
    var rangeValid = true;
    var timeIntervalValid = true;

    $.each(data['timerange'], function (i, range) {
      console.log(range);
      var start = moment('2012-10-09 ' + range.start, "YYYY-MM-DD h:mma");
      var end = moment('2012-10-09 ' + range.end, "YYYY-MM-DD h:mma");
      var difference = end.diff(start, 'minutes');

      if (difference < data['time_interval_per_candidate']) {
        timeIntervalValid = false;
      }

      if (range.start == range.end) {
        rangeValid = false;
      }
    });

    if (!rangeValid) {
      valid = false;
      $("#time-range-zero-minute-" + roomNumber).fadeIn();
    } else {
      $("#time-range-zero-minute-" + roomNumber).fadeOut();
    }

    if (!timeIntervalValid) {
      valid = false;
      $("#time-interval-greater-" + roomNumber).fadeIn();
    } else {
      $("#time-interval-greater-" + roomNumber).fadeOut();
    }


    var jobValid = true;
    $.each(data['jobs'], function (i, job) {
      if (countOccurance(job, data['jobs']) > 1) {
        jobValid = false;
      }
    });

    if (!jobValid) {
      valid = false;
      $("#linked-job-post-required-" + roomNumber).fadeIn();
    } else {
      $("#linked-job-post-required-" + roomNumber).fadeOut();
    }

    return valid;
  }

  function countOccurance($needle, $haystack) {
    var instance = 0;
    for (var i = $haystack.length - 1; i >= 0; i--) {
      var item = $haystack[i];
      if (item == $needle) {
        ++instance;
      }
    }

    return instance;
  }

  function reinitializeOtherTimeRange() {
    var room_triggered = $(this).data('room');
    var timecount_triggered = $(this).data('timecount');
    var timeEnd = $(this).val();
    var counter = timecount_triggered + 1;
    for (counter; counter <= numberOfTimeRange; counter++) {
      // console.log(counter);
      $("#time-start-" + counter).val(timeEnd);
      $("#time-end-" + counter).val(timeEnd);
      timeEnd = timePickerInit(timeEnd, room_triggered, counter);

    }
  }

  function preview() {
    // $(".room-form").fadeOut();

    dataStore = [];

    var validRooms = true;

    for (var i = 0; i < numberOfRooms; i++) {
      var validForm = true;
      var name = $("input[name='event\\[" + i + "\\]\\[\\'name\\'\\]").val();
      var date = $("input[name='event\\[" + i + "\\]\\[\\'date\\'\\]").val();
      var interval = $("input[name='event\\[" + i + "\\]\\[\\'time_interval\\'\\]").val();
      var in_charge_user_id = $("select[name='event\\[" + i + "\\]\\[\\'in_charge_user_id\\'\\]").val();
      var in_charge_name = $(".in-charge-option-" + i + ":selected").html();
      var job_type = $(".job-type-radio-" + i + ":checked").val();
      var jobs = $("select[name^='jobs[" + i + "]']").map(function () {
        var item = JSON.parse($(this).val());
        return item.id;
      }).get();
      var jobNames = $("select[name^='jobs[" + i + "]']").map(function () {
        var item = JSON.parse($(this).val());
        return item.name;
      }).get();
      var timerange = $(".time-range-pair-" + i).map(function () {
        return {
          "start": $(this).find(".start").val(),
          "end": $(this).find(".end").val()
        }
      }).get();
      var introduction = $("textarea[name='event\\[" + i + "\\]\\[\\'introduction\\'\\]").val();

      var data = {
        "event_name": name,
        "event_date": date,
        "time_interval_per_candidate": interval,
        "jobs": jobs,
        "jobNames": jobNames,
        "employment_type_id": job_type,
        "timerange": timerange,
        "introduction": introduction,
        "in_charge_user_id": in_charge_user_id,
        "in_charge_name": in_charge_name
      };

      if (!validate(data, i)) {
        validForm = false;
        validRooms = false;
      }

      if (validForm) {
        dataStore.push(data);
      }


    }
    if (validRooms) {
      for (var i = 0; i < dataStore.length; i++) {
        var data = dataStore[i];
        renderRoomList(data);
      }
      $("#interviewPostFormContainer").fadeOut();
      $("#preview").fadeOut();
      $("#addRoom").fadeOut();
      $("#continue").fadeIn();
    }

  }

  function renderRoomList(data) {
    getTemplate('room-list.html', function (render) {
      var html = render({
        data: data
      });

      $('#mainCreateRoomRow').prepend(html);

    });
  }



  function proceedToCheckout() {
    $(".room-list").fadeOut();
    // console.log(dataStore[0].timerange[0].start);
    var totalHours = 0;
    for (var x = 0; x < dataStore.length; x++) {
      var data = dataStore[x];
      for (var y = 0; y < data.timerange.length; y++) {
        var timerange = data.timerange[y];
        var diff = countDifference(timerange.start, timerange.end);
        totalHours += diff;
      }

    }

    $.get(apiUrl + '/openday/current-rate', function (rate) {
      var rate = parseInt(rate);
      var totalPrize = rate * totalHours;
      getTemplate('checkout-form.html', function (render) {
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
    totalHours = (difference / 60);

    return totalHours;
  }


  function backToTop() {
    $(".room-form").fadeIn();
    $("#interviewPostFormContainer").fadeIn();
    $(".room-list").remove();
    $(".checkout-form").remove();

    $("#preview").fadeIn();
    $("#addRoom").fadeIn();
    $("#continue").fadeOut();
    $("#submit").fadeOut();
  }

  function submit() {
    var savedOpenday = 0;
    for (var i = 0; i < dataStore.length; i++) {
      // console.log(i);
      var data = dataStore[i];
      data.created_by_user_id = getCurrentUserId();
      $.post(apiUrl + "/openday", data, function (res) {
        var lastDataStore = dataStore.length;
        savedOpenday += 1;
        if (savedOpenday == lastDataStore) {

          document.location = "create-success.php?openday=" + res;
        } else {
          window.open("create-success.php?openday=" + res);
        }
      });
    }

  }








})($);
$(createRoomScreenManagement.init);