var createRoomScreenManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/create-room/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = 'http://api-openday.herokuapp.com/';
    var dataStore = [];

    return {
        init: init,
        addForm: addForm
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

      // job
      $("#interviewPostFormContainer").on('click', '.add-job-btn', addNewJob);

      $("#interviewPostFormContainer").on('click', '.remove-job-btn', removeJob);

      $("#interviewPostFormContainer").on('click', '.add-time-range', addTimeRange);

      $("#interviewPostFormContainer").on('click', '.remove-time-range', removeTimeRange);

      $("#submit").on('click', submit);

      $("#preview").on('click', preview);

      $("#continue").on('click', proceedToCheckout);

      $("#cancel").on('click', backToTop);

    }

    function initialTemplate() {
      addForm();
      addNewJob();
      addTimeRange();
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
          $('.time-range-pair').datepair();
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


    function addTimeRange() {
      getTemplate('partials/input-time-range.html', function (render) {
        var html = render({ room_number: numberOfRooms - 1});
        var lastEndTime = $('.end').last().val();

        $("#timeRangeList").append(html);

        $("a.add-time-range").css("display", "none");
        $("a.remove-time-range").css("display", "block");
        $("a.remove-time-range").last().css("display", "none");
        $("a.add-time-range").last().css("display", "block");

        timePickerInit(lastEndTime);
        });
    }

    function timePickerInit(lastEndTime) {
      var disableRange = ['12am', lastEndTime];
      $('.time-range-pair .time').timepicker({
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

    }

    function removeTimeRange() {
      $(this).parent().parent().parent().parent().remove();
    }


    function preview() {
      // $(".room-form").fadeOut();
      for (var i = 0; i < numberOfRooms; i++) {
        var name = $("input[name='event\\["+ i +"\\]\\[\\'name\\'\\]").val();
        var date = $("input[name='event\\["+ i +"\\]\\[\\'date\\'\\]").val();
        var interval = $("input[name='event\\["+ i +"\\]\\[\\'time_interval\\'\\]").val();

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
          "introduction": introduction
        };

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

      getTemplate('checkout-form.html', function(render){
          var html = render();
          $("#interviewPostFormContainer").fadeOut();
          $('#mainCreateRoomRow').prepend(html);
          $("#continue").fadeOut();
          $("#submit").fadeIn();
      });


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
        $.post(apiUrl + "/openday", data, function(res) {

        });
      }

    }






})($);
$(createRoomScreenManagement.init);
