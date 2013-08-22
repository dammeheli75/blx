$(document).ready(function () {

    $('.carousel').carousel({
        interval: 5000
    });

    var fillingPlaceTab = $('#fillingPlaceTab');

    fillingPlaceTab.find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    var map;

    var vietDungLocation = new google.maps.LatLng(21.005321, 105.847150);
    var manhDuyLocation = new google.maps.LatLng(21.006347, 105.846906);
    var camKhangLocation = new google.maps.LatLng(21.004975, 105.847043);

    map = new google.maps.Map(document.getElementById('map-canvas'), {
        zoom: 18,
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
        position: manhDuyLocation,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });

    new google.maps.Marker({
        position: camKhangLocation,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP
    });

    fillingPlaceTab.find('a').on('shown', function (e) {
        var tabLocation = $(e.target).attr('href');
        var location;

        switch (tabLocation) {
            case '#collaborator-viet-dung':
                location = vietDungLocation;
                break;
            case '#collaborator-manh-duy':
                location = manhDuyLocation;
                break;
            case '#collaborator-cam-khang':
                location = camKhangLocation;
                break;
            default:
                break;
        }

        map.panTo(location);
    });
});