$(document).ready(function () {
    $('#helpTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })
});