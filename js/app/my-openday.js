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
        addSocketEvents();
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

    function addSocketEvents() {
      window.socket.on("notifier-" + $("#userId").val(), function(data){

        if(data.category == "candidate") {
             loadActiveOpenday();

        }

        });
    }

    function initialTemplate() {
      loadActiveOpenday();
    }

    function loadActiveOpenday() {
        var userId = getCurrentUserId();
        var status = 1;
        $("#active_openday>ul").html('');

        $.get(apiUrl + '/openday/my?user_id=' + userId +'&status=active', function(results){
           for (var i = 0; i < results.length; i++) {

                  var result = results[i];
                  renderOpenday(result,"#active_openday>ul", "active");
                 
            }
        });

    }


    function renderOpenday(openday, selector, schedule_status) {
         getTemplate("item.html", function(render){
                    var html = render({ data: openday, attendance_status : schedule_status});
                    $(selector).append(html);
                  });
    }


    function loadEndOpenday(){
      var userId = getCurrentUserId();
        var status = 2;
        $("#end_openday>ul").html('')

        $.get(apiUrl + '/openday/my?user_id=' + userId +'&status=end', function(results){
           for (var i = 0; i < results.length; i++) {
                  var result = results[i];
                  renderOpenday(result, "#end_openday>ul", "end");
            }
        });

    }



    function getCurrentUserId() {
         var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
        return $("#userId").val();
    }





})($);
$(myOpendayScreenManagement.init);
