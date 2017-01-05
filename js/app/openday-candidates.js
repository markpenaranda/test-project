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
    }

    function initialTemplate() {
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

   









})($);
$(opendayCandidatesScreenManagement.init);
