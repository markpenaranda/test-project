var adManagement = (function($) {
    var baseTemplateUrl = 'template/ad-management/';
    var templates = [];
    var numberOfRooms = 0;
    var apiUrl = '/api/v1/public/index.php';
    var dataStore = [];
    var map = null;
    var searchedResults = [];
    var place = null;
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


      // $("#mainPromoteManagement").on("change", "#addressName", setMap);
      $("#mainPromoteManagement").on("keyup", "#radius", changeRadius);
   
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
        console.log(place);
        var coordinates = {lat: place.geometry.location.lat, lng: place.geometry.location.lng};
        var mapOptions = { zoom: 4, center: coordinates };
        map = new google.maps.Map(document.getElementById('maps'));
        map.setCenter(new google.maps.LatLng(place.geometry.location.lat, place.geometry.location.lng));
        map.setZoom(12);
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




})($);
$(adManagement.init);
