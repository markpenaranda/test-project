var widgetManagement = (function($) {
    var baseTemplateUrl = 'template/widget/';
    var templates = [];
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    

    return {
        init: init
    };




    // Init
    function init() {
        loadSuggestedCandidates();
        loadSuggestedOpenday();
        loadSuggestedJobPost();
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
        


    }



    function getCurrentUserId() {
         var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
        return $("#userId").val();
    }

     function loadSuggestedCandidates() {
      $.get(apiUrl + '/promotion/user?user_id=' + getCurrentUserId(), function(results){
        for (var i = 0; i < results.length; i++) {
          var data = results[i];
          renderSuggestedCandidates(data);
        }
      });

    }

    function renderSuggestedCandidates(data) {
      getTemplate('suggested_candidate.html', function(render){
        var html = render({data: data});
        $("#suggestedCandidatesList").append(html);
      });
    }


    function loadSuggestedJobPost() {
      $.get(apiUrl + '/promotion/job?user_id=' + getCurrentUserId(), function(results){
        for (var i = 0; i < results.length; i++) {
          var data = results[i];
          renderSuggestedJobPost(data);
        }
      });

    }

    function renderSuggestedJobPost(data) {
      getTemplate('suggested_job.html', function(render){
        var html = render({data: data});
        $("#suggestedJobList").append(html);
      });
    }

    function loadSuggestedOpenday() {
      $.get(apiUrl + '/promotion/openday?user_id=' + getCurrentUserId(), function(results){
        for (var i = 0; i < results.length; i++) {
          var data = results[i];
          renderSuggestedOpenday(data);
        }
      });

    }

    function renderSuggestedOpenday(data) {
      getTemplate('suggested_openday.html', function(render){
        var html = render({data: data});
        $("#suggestedOpendayList").append(html);
      });
    }


   





})($);
$(widgetManagement.init);
