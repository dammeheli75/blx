$(document).ready(function () {
    $('input[type="checkbox"]').change(function () {
        if (this.checked) {
            $(this).val('on');
        } else {
            $(this).val('off');
        }
    });
});