var myOpendayScreenManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/my-openday/';
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
      $('#activeSelect').on('click', loadActiveOpenday);
      $('#endSelect').on('click', loadEndOpenday);
   
    }

    function initialTemplate() {
      loadActiveOpenday();
    }

    function loadActiveOpenday() {
        var userId = $("#userId").val();
        var status = 1;
        $("#active_openday>ul").html('');

        $.get(apiUrl + '/openday/my?user_id=' + userId +'&status=0', function(results){
           for (var i = 0; i < results.length; i++) {

                  var result = results[i];
                  getTemplate("item.html", function(render){
                    var html = render({ data: result, status: 'Live' });
                    $("#active_openday>ul").append(html);
                  });
            }
        });

        $.get(apiUrl + '/openday/my?user_id=' + userId +'&status=' + status, function(results){
           for (var i = 0; i < results.length; i++) {

                  var result = results[i];
                  getTemplate("item.html", function(render){
                    var html = render({ data: result,  status: 'Waiting'  });
                    $("#active_openday>ul").append(html);
                  });
            }
        });
    }


    function loadEndOpenday(){
      var userId = $("#userId").val();
        var status = 2;
        $("#end_openday>ul").html('')

        $.get(apiUrl + '/openday/my?user_id=' + userId +'&status=' + status, function(results){
           for (var i = 0; i < results.length; i++) {
                  var result = results[i];

                  getTemplate("item.html", function(render){
                    var html = render({ data: result });
                    $("#end_openday>ul").append(html);
                  });
            }
        });

    }









})($);
$(myOpendayScreenManagement.init);
