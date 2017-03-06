var adManagement = (function($) {
    var baseTemplateUrl = 'template/ad-management/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    var map = null;
    var searchedResults = [];
    var place = null;
    var currencies = [];
    var selectedCurrency = null;
    var FORM_REQUIRED_FIELDS = [
        "bid_per_engagement",
        // "budget_per_day",
        "schedule",
        "currency_id"
    ];

    return {
        init: init
    };




    // Init
    function init() {
        initialTemplate();
        addEventHandlers();
        searchGeoCode();


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
      $("#mainPromoteManagement").on("click", "#startFreeMonthBtn", showForm);
      $("#mainPromoteManagement").on("click", "#promoteBtn", submit);


      // $("#mainPromoteManagement").on("change", "#addressName", setMap);
      $("#mainPromoteManagement").on("keyup", "#radius", changeRadius);
      $("#mainPromoteManagement").on("change", "#currency", selectCurrency);

      $("#mainPromoteManagement").on("change", "input[type=radio][name=schedule_type]", scheduleTypeSelect);
   
      $("#mainPromoteManagement").on("change", ".costField", calculateCost);

    }

    function getToBePromotedId() {
        return $("#tbp_id").val();
    }

    function getPageId() {
        if(getPromotionType() != "user") {
            return "f657ff25d22b7ba";
        }
        return null;
    }

    function getPromotionType() {
        return $("#promotion_type").val();
    }



    function getCurrentUserId() {
         var localStorageUser = localStorage.getItem('userId');
        if(localStorageUser) {
          return localStorageUser;
        }
        return $("#userId").val();
    }


    function initialTemplate() {
        getTemplate('initial.html', function(render) {
            var html = render();
           $("#mainPromoteManagement").html(html);
        });
    }

    function showForm() {
        formTemplate();
    }

    function formTemplate() {
        getTemplate('form.html', function(render) {
            var html = render();
           $("#mainPromoteManagement").html(html);
         $("#dateRange .date").datepicker({
                'format': 'm/d/yyyy',
                'autoclose': true
            });
         $('#dateRange .time').timepicker({timeFormat: 'g:ia', useSelect: true});
         $('#dateRange').datepair();

         $('.promote_post_schedule_ul').fadeOut();
         $('.ad_note_h4').fadeOut();

   

          var uluru = {lat: -25.363, lng: 131.044};
           map = new google.maps.Map(document.getElementById('maps'), {
              zoom: 4,
              center: uluru
            });

            var options = {
                url: function(phrase) {
                    return "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCkfUcSAl_ZPvpEUxobYJbumX260FEQTn0&address=" + phrase;
                },
                listLocation: 'results',
                requestDelay: 500,
                getValue: "formatted_address",
                list: {

                        onSelectItemEvent: function() {
                            drawSelectedLocation($("#addressName").getSelectedItemData());
                        }
                }
            };

            $("#addressName").easyAutocomplete(options);
            loadCurrency();
            $('#industry').tagEditor({ 
              delimiter: ',', /* space and comma */
              autocomplete: { 'source': apiUrl + '/resources/industry-keyword', delay: 1000 },
              placeholder: 'Type Industry and press enter',
           
              forceLowercase: false
             });
            $('#keyword').tagEditor({ 
              delimiter: ',', /* space and comma */
              autocomplete: { 'source': apiUrl + '/resources/keyword', delay: 1000 },
              placeholder: 'Type your keywords and press enter'
           
            });

       
        });
    }

    function arePointsNear(checkPoint, centerPoint, km) {
      var ky = 40000 / 360;
      var kx = Math.cos(Math.PI * centerPoint.lat / 180.0) * ky;
      var dx = Math.abs(centerPoint.lng - checkPoint.lng) * kx;
      var dy = Math.abs(centerPoint.lat - checkPoint.lat) * ky;
      return Math.sqrt(dx * dx + dy * dy) <= km;
    }

    function searchGeoCode() {

        var geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCkfUcSAl_ZPvpEUxobYJbumX260FEQTn0&address=Manila";

        $.get(geocodeUrl, function(data){
            searchedResults = data.results;
        })
    }


    function drawSelectedLocation(placeVar) {
   
        place = (placeVar) ? placeVar : place;
        var radius = $("#radius").val();
        var zoomSize = (radius > 9) ? 10 : 12;
        console.log(place);
        var coordinates = {lat: place.geometry.location.lat, lng: place.geometry.location.lng};
        var mapOptions = { zoom: 4, center: coordinates };
        map = new google.maps.Map(document.getElementById('maps'));
        map.setCenter(new google.maps.LatLng(place.geometry.location.lat, place.geometry.location.lng));
        
        map.setZoom(zoomSize);
        var cityCircle = new google.maps.Circle({
          strokeColor: '#E77400',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#E77400',
          fillOpacity: 0.35,
          map: map,
          center: new google.maps.LatLng(place.geometry.location.lat, place.geometry.location.lng),
          radius:  radius * 1000
        });
                        
    }

    function changeRadius (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            drawSelectedLocation(null);
        }
    }

    function setMap() {
        console.log($(this).val());
        // for (var i = searchedResults.length - 1; i >= 0; i--) {
        //     var googleLocations = searchedResults[i];

        // }
    }


    function loadCurrency() {
        $.get(apiUrl + '/resources/currency', function(results) {
            for (var i = results.data.length - 1; i >= 0; i--) {
                var data = results.data[i];
                currencies = results.data;
                renderCurrencyTemplate(data);
            }
        });
    }

    function renderCurrencyTemplate(data) {
        getTemplate('/partial/currency_option.html', function(render) {
            var html = render({data:data });
            $("#currency").append(html);
        });
    }

    function selectCurrency() {
        for (var i = currencies.length - 1; i >= 0; i--) {
            loadedCurrency = currencies[i];
            var selectedCurrencyId = $("#currency").val();
            if(selectedCurrencyId == loadedCurrency.currency_id) {
                selectedCurrency = loadedCurrency;
                $('.selectedCurrency').html(selectedCurrency.currency_code);
            }
        }
    }

    function scheduleTypeSelect() {
        if($(this).val() == "limited") {
             $('.promote_post_schedule_ul').fadeIn();
             $('.ad_note_h4').fadeIn();
            
        }
        else {
             $('.promote_post_schedule_ul').fadeOut();
             $('.ad_note_h4').fadeOut();
        }
    }

    function calculateCost() {

        var budgetPerDay = ($("#budgetPerDay").val()) ? $("#budgetPerDay").val() : 0;
        var startDate = ($("#startDate").val()) ? moment($("#startDate").val()) : moment();
        var endDate = ($("#startDate").val()) ? moment($("#endDate").val()) : moment();
    
        var diffInDays = endDate.diff(startDate, 'days'); 
        console.log(diffInDays);
        diffInDays = (diffInDays > 0) ? diffInDays : 1;
        $(".adDays").html(diffInDays);
        var cost = diffInDays * budgetPerDay;
        $(".adCost").html(cost);
    }



    function validateData(data) {
        var valid = true;
        console.log('here');
        console.log(FORM_REQUIRED_FIELDS);
        $.each(FORM_REQUIRED_FIELDS, function(i, field){
            console.log(field);
            if(data[field] == "" || data[field] == undefined || data[field] == 0) {
              $("#" + field + "-required").fadeIn();
              valid = false;
            }
            else {
               $("#" + field + "-required").fadeOut();
            }
        });

        if(isNaN(data['bid_per_engagement'])) {
          valid = false;
          $("#bid_per_engagement-must-be-decimal-required").fadeIn();
        }
        else {
          $("#bid_per_engagement-must-be-decimal-required").fadeOut();
        }

        if(isNaN(data['budget_per_day'])) {
          valid = false;
          $("#budget_per_day-must-be-decimal-required").fadeIn();
        }
        else {
          $("#budget_per_day-must-be-decimal-required").fadeOut();
        }

        if(data['schedule'] == "limited") {
            if(data['start_date'] == "" || data['start_date'] == undefined || data['start_date'] == 0) {
              $("#start-date-required").fadeIn();
              valid = false;
            }
            else {
               $("#start-date-required").fadeOut();
            }

             if(data['end_date'] == "" || data['end_date'] == undefined || data['start_date'] == 0) {
              $("#end-date-required").fadeIn();
              valid = false;
            }
            else {
               $("#end-date-required").fadeOut();
            }
        }
        $("#location-required").fadeOut();
        if(data['lat'] <= 0 || data['lng'] <= 0) {
             $("#location-required").fadeIn();
              valid = false;
            
            
        }
        else {
               $("#end-date-required").fadeOut();
        } 

       

        return valid;
    }

    function submit() {
        var data = {
            'budget_per_day' : $("#budgetPerDay").val(),
            'bid_per_engagement' : $("#bidPerClick").val(),
            'schedule' : $(".schedule_type:checked").val(),
            'start_date': $("#startDate").val(),
            'end_date': $("#endDate").val(),
            'start_time': $("#startTime").val(),
            'end_time': $("#endTime").val(),
            'radius': $("#radius").val(),
            'lat': (place) ? place.geometry.location.lat : null,
            'lng': (place) ? place.geometry.location.lng : null,
            'currency_id': $("#currency").val(),
            'industry': $("#industry").val(),
            'gender': $(".gender:checked").val(),
            'to_be_promoted_id': getToBePromotedId(),
            'keyword': $("#keyword").val(),
            'is_people_applied_included': $("#is_people_applied_included:checked").val(),
            'is_people_viewed_job_included': $("#is_people_viewed_job_included:checked").val(),
            'is_people_viewed_page_included': $("#is_people_viewed_page_included:checked").val(),
            'created_by_user_id': getCurrentUserId(),
            'page_id': getPageId(),
            'promotion_type': getPromotionType()
        };



        if(validateData(data)) { 

         

            $.ajax({
                type: "POST",
                url: apiUrl + "/promotion",
                data: data,
                
                success: function(data) {
                   $("#post-error").fadeOut();
                   window.location = "/promote-list.php";
                },
                error: function(data){
                  $("#post-error").fadeIn();

                }
            });
        }
    }

})($);
$(adManagement.init);
