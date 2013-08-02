$(document).ready(function () {

    $('#fillingPlaceTab a').click(function (e) {
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

    $('#fillingPlaceTab a').on('shown', function (e) {
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

    //    Profile Type Select
    $('input[value=' + $('input[name=profile-type]:checked', '#online-filing-form').val() + ']').parent().addClass('profile-type-selected');
    $('input[name=profile-type]', '#online-filing-form').change(function () {
        $('input[value=' + $('input[name=profile-type]:checked', '#online-filing-form').val() + ']').parent().addClass('profile-type-selected');
        $('input[value!=' + $('input[name=profile-type]:checked', '#online-filing-form').val() + ']').parent().removeClass('profile-type-selected')
    });

//  Online filing error modal
//$('#online-filing-error-modal').modal('show');

    var errorViewModel = new kendo.observable({
        'errors': []
    });

    kendo.bind($("#online-filing-error-modal"), errorViewModel);

//    Online Filing Form Submit
    $("#online-filing-form").on("submit", function () {

        var form = $("#online-filing-form");

        form.find('[type="submit"]').addClass('disabled').addClass('loading');

        j.post('form.php', form.serialize(), function (response, textStatus) {
            if (textStatus == 'success') {
                if (response.success && response.errors.length == 0) {
                    $('#online-filing-success-modal').modal('show');
                    $('#online-filing-success-modal').on('hide', function () {
                        // Redirect to "Lịch thi" page
                        window.location.href = "home.html";
                    });
                } else if (!response.success || response.errors.length > 0) {

                    for (var i = 0; i < response.errors.length; i++) {
                        //console.log($('input[name="' + response.errors[i].at + '"]').parentsUntil('div.control-group'));
                        $('input[name="' + response.errors[i].at + '"]').parentsUntil('.control-group').addClass('error');
                    }

                    errorViewModel.set('errors', response.errors);
                    $('#online-filing-error-modal').modal('show');
                    form.find('[type="submit"]').removeClass('disabled').removeClass('loading');
                } else {

                }
            }
        });

        return false;
    });
});