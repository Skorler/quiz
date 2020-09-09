$(document).ready(function () {

    $("#selectAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
});