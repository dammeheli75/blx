$(document).ready(function () {

    $('#reportTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    function startChange() {
        var startDate = start.value(),
            endDate = end.value();

        if (startDate) {
            startDate = new Date(startDate);
            startDate.setDate(startDate.getDate());
            end.min(startDate);
        } else if (endDate) {
            start.max(new Date(endDate));
        } else {
            endDate = new Date();
            start.max(endDate);
            end.min(endDate);
        }
    }

    function endChange() {
        var endDate = end.value(),
            startDate = start.value();

        if (endDate) {
            endDate = new Date(endDate);
            endDate.setDate(endDate.getDate());
            start.max(endDate);
        } else if (startDate) {
            end.min(new Date(startDate));
        } else {
            endDate = new Date();
            start.max(endDate);
            end.min(endDate);
        }
    }

    var start = $("#start-date-input").kendoDatePicker({
        change: startChange
    }).data("kendoDatePicker");

    var end = $("#end-date-input").kendoDatePicker({
        change: endChange
    }).data("kendoDatePicker");

    start.max(end.value());
    end.min(start.value());
});