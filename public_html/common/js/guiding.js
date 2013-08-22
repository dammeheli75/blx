$(document).ready(function () {

    $('section [href^=#]').click(function (e) {
        e.preventDefault()
    });

    setTimeout(function () {
        $('.docs-sidenav').affix({
            offset: {
                top: function () {
                    return $(window).width() <= 980 ? 290 : 210
                }, bottom: 100
            }
        })
    }, 100);

    $('#venueTab').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    var map;

    var lvtLocation = new google.maps.LatLng(21.001990, 105.802950);
    var tvdLocation = new google.maps.LatLng(20.998605, 105.820202);

    map = new google.maps.Map(document.getElementById('map-canvas'), {
        zoom: 16,
        center: lvtLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    new google.maps.Marker({
        position: lvtLocation,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });

    new google.maps.Marker({
        position: tvdLocation,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });

    $('#venue').find('.nav-tabs li a').on('show', function (e) {
        var tabLocation = $(e.target).attr('href');
        var location;

        switch (tabLocation) {
            case '#so-27-le-van-thiem':
                location = lvtLocation;
                break;
            case '#so-101-to-vinh-dien':
                location = tvdLocation;
                break;
            default:
                break;
        }

        map.panTo(location);
    });
});