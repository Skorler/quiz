$(document).ready(function () {

    $("#selectAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $("#block").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_form"]').attr('action', "/admin/users/block").submit();
    });

    $("#unblock").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_form"]').attr('action', '/admin/users/unblock').submit();
    });

    $("#delete").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_form"]').attr('action', "/admin/users/delete").submit();
    });

    $("#activate").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_form"]').attr('action', "/admin/users/activate").submit();
    });
});