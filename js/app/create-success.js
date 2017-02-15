var createSuccessManagement = (function($) {
    var timer;
    var baseTemplateUrl = 'template/create-success/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];

    return {
        init: init
    };

    // Init
    function init() {
        initialTemplate();
        addEventHandlers();
    }

    function getCurrentUserId() {
          var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
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

    }

    function initialTemplate() {
        loadSuggestedCandidate();
        loadOpenday();
    }

    function getOpendayId() {
        return $("#opendayId").val();
    }

    function loadOpenday() {
        $.get(apiUrl + '/openday/' + getOpendayId(), function(res){ 
            $("#opendayTitle").html(res.event_name);
        });
    }

    function loadSuggestedCandidate() {
        $.get(apiUrl + '/openday/' + getOpendayId() + '/suggested', function(res) {
            for(var x = 0; x < res.length; x++) {
                var candidate = res[x];
                renderSuggestedCandidate(candidate);
                
            }
        });
    }

    function renderSuggestedCandidate(candidate) {
        getTemplate("results.html", function(render){
                    var data = JSON.parse(candidate.personal_info);
                    console.log(data);
                    var html = render({data : data});
                    $('#resultsUl').append(html);
                });
    }





   





})($);
$(createSuccessManagement.init);
