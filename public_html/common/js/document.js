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

    $("a[data-toggle=popover]").popover({
        placement: 'top'
    }).click(function (e) {
            e.preventDefault();
        });

    $('#threadTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});
