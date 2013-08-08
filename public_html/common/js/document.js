$(document).ready(function () {

    $('section [href^=#]').click(function (e) {
        e.preventDefault()
    });

    setTimeout(function () {
        $('.docs-sidenav').affix({
            offset: {
                top: function () {
                    return $(window).width() <= 980 ? 290 : 210
                }, bottom: 0
            }
        })
    }, 100);


    $('#threadTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Signs
    var signViewModel = kendo.observable({
        signGroups: []
    });

    kendo.bind($("#road-sign"), signViewModel);

    jQuery.getJSON( baseUrl + "common/json/signs.json", function (response) {
        signViewModel.set("signGroups", response);
        $("a[data-toggle=popover]").popover({
            placement: 'top'
        }).click(function (e) {
                e.preventDefault();
            });
    });
});
