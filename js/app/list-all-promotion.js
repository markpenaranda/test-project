var listAllPromotion = (function($) {
    var baseTemplateUrl = 'template/list-all-promotion/';
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
        loadPage();

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


    function loadOpenday() {
        $.get(apiUrl + '/promotion/openday/all', function(results) {
            for(var x = 0; x < results.length; x++) {
               var data = results[x];
               renderOpenday(data);
            }
        });

    }

    function renderOpenday(data) {
      $.get(apiUrl + "/promotion/openday/" + data.promote_id + "/compute-amount", function(consumedAmount) {
        var html = "<tr><td>" + data.event_name + "</td><td>" +  consumedAmount + "</td></tr>"
        $("#opendayList").append(html);

      });
    }


   function loadJobs() {
        $.get(apiUrl + '/promotion/job/all', function(results) {
            for(var x = 0; x < results.length; x++) {
               var data = results[x];
               renderJobs(data);
            }
        });

    }

    function renderJobs(data) {
     $.get(apiUrl + "/promotion/job/" + data.promote_id + "/compute-amount", function(consumedAmount) {
        var html = "<tr><td>" + data.job_title + "</td><td>" +  consumedAmount + "</td></tr>"
        $("#jobList").append(html);

      });
    }

    function loadCandidate() {
        $.get(apiUrl + '/promotion/user/all', function(results) {
            for(var x = 0; x < results.length; x++) {
               var data = results[x];
               renderCandidate(data);
            }
        });

    }

    function renderCandidate(data) {
       $.get(apiUrl + "/promotion/user/" + data.promote_id + "/compute-amount", function(consumedAmount) {
        var html = "<tr><td>" + JSON.parse(data.personal_info).first_name + " " +  JSON.parse(data.personal_info).last_name + "</td><td>" +  consumedAmount + "</td></tr>";
        $("#candidateList").append(html);

      });
    }

    function loadPage() {
        $.get(apiUrl + '/promotion/page/all', function(results) {
            for(var x = 0; x < results.length; x++) {
               var data = results[x];
               renderPage(data);
            }
        });

    }

    function renderPage(data) {
       $.get(apiUrl + "/promotion/page/" + data.promote_id + "/compute-amount", function(consumedAmount) {
        var html = "<tr><td>" + data.page_name +"</td><td>" +  consumedAmount + "</td></tr>";
        $("#companyList").append(html);

      });
    }




    

   





})($);
$(listAllPromotion.init);
