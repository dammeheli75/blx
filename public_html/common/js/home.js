$(document).ready(function () {
    $('.carousel').carousel({
        interval: 5000
    });
});

function initialize() {
    var collaboratorSellector = $("tbody tr");
    var locations = [], markers = [], infos = [];
    collaboratorSellector.each(function (index, item) {
        var longitude = $(item).attr("data-longitude");
        var latitude = $(item).attr("data-latitude");
        var title = $(item).attr("data-title");
        var info = $(item).attr("data-info");
        var position = new google.maps.LatLng(longitude, latitude);
        locations.push(position);
        markers.push(new google.maps.Marker({
            position: position,
            animation: google.maps.Animation.DROP,
            title: title
        }));
        infos.push(new google.maps.InfoWindow({
            content: info
        }));
    });

    var mapOptions = {
        zoom: 18,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    if (locations.length > 0) {
        mapOptions.center = locations[0];
    }
    // Enable the visual refresh
    google.maps.visualRefresh = true;
    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

    for (var i = 0; i < markers.length; i++) {
        (function () {
            var marker = markers[i];
            var info = infos[i];
            marker.setMap(map);
            google.maps.event.addListener(marker, 'click', function () {
                info.open(map, marker);
            });
        })();
    }

    collaboratorSellector.each(function (index, item) {
        $(item).click(function () {
            map.panTo(locations[index]);
        });
    });

    $("#findNearestLocation").click(function () {
        var browserSupportFlag = Boolean();
        // Try W3C Geolocation (Preferred)
        if (navigator.geolocation) {
            browserSupportFlag = true;
            navigator.geolocation.getCurrentPosition(function (position) {
                var geoLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                // Do more
                var service = new google.maps.DistanceMatrixService();
                service.getDistanceMatrix(
                    {
                        origins: [geoLocation],
                        destinations: locations,
                        travelMode: google.maps.TravelMode.DRIVING,
                        unitSystem: google.maps.UnitSystem.METRIC,
                        durationInTraffic: true,
                        avoidHighways: false,
                        avoidTolls: false
                    }, function (response, status) {
                        // See Parsing the Results for
                        // the basics of a callback function.
                        var durations = [], i, minDurationIndex = null;
                        for (i = 0; i < response.rows[0].elements.length; i++) {
                            durations.push(response.rows[0].elements[i].duration.value);
                        }
                        var minDuration = Math.min.apply(Math, durations);
                        for (i = 0; i < durations.length; i++) {
                            if (durations[i] == minDuration) {
                                minDurationIndex = i;
                                break;
                            }
                        }
                        if (minDurationIndex != null) {
                            collaboratorSellector.each(function (index, item) {
                                if (index == minDurationIndex) {
                                    $(item).addClass("nearestLocation");
                                    map.panTo(locations[minDurationIndex]);
                                }
                            });
                        }
                    });
            }, function () {
                handleNoGeolocation(browserSupportFlag);
            });
        }
        // Browser doesn't support Geolocation
        else {
            browserSupportFlag = false;
            handleNoGeolocation(browserSupportFlag);
        }

        //
        function handleNoGeolocation(errorFlag) {
            if (errorFlag == true) {
                alert("Không thể xác định vị trí hiện tại của bạn.");
            } else {
                alert("Trình duyệt của bạn không hỗ trợ định vị hoặc bạn chưa/không cho phép chúng tôi định vị bạn.");
            }
            map.panTo(locations[0]);
        }
    });
}

function loadScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDYGj0U9_vBLmQ6NhzN7R_T2vBS-Up_UvQ&sensor=true&callback=initialize&v=3";
    document.body.appendChild(script);
}

window.onload = loadScript;