var widgetManagement = (function($) {
    var baseTemplateUrl = 'template/promote-list/';
    var templates = [];
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    

    return {
        init: init
    };




    // Init
    function init() {

        addEventHandlers();

        loadOpenday();
        loadJobs();
        loadCandidate();

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

    // PromoteBtn
    function renderPromoteButton(idToBePromoted, promotionType, callback)
    {
      $.get(apiUrl + "/promotion/" + promotionType + "/" + idToBePromoted, function(result){
        var promoted = (result) ? true: false;
         getTemplate ("promote_btn.html", function(render){
            var html = render({promoted : promoted, id: idToBePromoted, type: promotionType});

            callback(html);
          });
      });
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


    function loadOpenday() {
        $.get(apiUrl + '/openday/created?user_id=' + getCurrentUserId(), function(results) {
            for(var x = 0; x < results.length; x++) {
               var data = results[x];
               renderOpenday(data);
            }
        });

    }

    function renderOpenday(data) {
      getTemplate("list.html", function(render){
        var id = data.openday_id;
        var html = render({name: data.event_name, id: data.openday_id});
        
        renderPromoteButton(id, "openday", function(promoteHtml){
          var $html = $(html);
          $html.find("#buttonSection-" + id).append(promoteHtml);
          $("#opendayList").append($html);
        });
      })
    }


   function loadJobs() {
        $.get(apiUrl + '/resources/filterjob?employment_type_id=1' , function(results) {
            for(var x = 0; x < results.data.length; x++) {
               var data = results.data[x];
               renderJobs(data);
            }
        });

    }

    function renderJobs(data) {
      getTemplate("list.html", function(render){
        var id = data.job_post_id;
        var html = render({name: data.job_title, id: data.job_post_id});
        
        renderPromoteButton(id, "job", function(promoteHtml){
          var $html = $(html);
          $html.find("#buttonSection-" + id).append(promoteHtml);
          $("#jobList").append($html);
        });
      })
    }

    function loadCandidate() {
        $.get(apiUrl + '/users', function(results) {
            for(var x = 0; x < results.length; x++) {
               var data = results[x];
               renderCandidate(data);
            }
        });

    }

    function renderCandidate(data) {
      getTemplate("list.html", function(render){
        var id = data.user_id;
        var html = render({name: data.name, id: data.user_id});
        
        renderPromoteButton(id, "user", function(promoteHtml){
          var $html = $(html);
          $html.find("#buttonSection-" + id).append(promoteHtml);
          $("#candidateList").append($html);
        });
      })
    }




    

   





})($);
$(widgetManagement.init);
