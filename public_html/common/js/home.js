$(document).ready(function () {

    $('.carousel').carousel({
        interval: 5000
    });

    $('#fillingPlaceTab').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    var map;

    var vietDungLocation = new google.maps.LatLng(21.006345, 105.846190);
    var viSongLocation = new google.maps.LatLng(21.036862, 105.774883);

    map = new google.maps.Map(document.getElementById('map-canvas'), {
        zoom: 15,
        center: vietDungLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    new google.maps.Marker({
        position: vietDungLocation,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });

    new google.maps.Marker({
        position: viSongLocation,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });

    $('#fillingPlaceTab').find('a').on('shown', function (e) {
        var tabLocation = $(e.target).attr('href');
        var location;

        switch (tabLocation) {
            case '#collaborator-viet-dung':
                location = vietDungLocation;
                break;
            case '#collaborator-vi-song':
                location = viSongLocation;
                break;
            default:
                break;
        }

        map.panTo(location);
    });
});