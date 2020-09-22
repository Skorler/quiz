$(document).ready(function () {

    $("#block").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_users"]').attr('action', "/admin/users/block").submit();
    });

    $("#unblock").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_users"]').attr('action', '/admin/users/unblock').submit();
    });

    $("#delete").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_users"]').attr('action', "/admin/users/delete").submit();
    });

    $("#activate").on("click", function (e) {
        e.preventDefault();
        $('form[name = "select_users"]').attr('action', "/admin/users/activate").submit();
    });
});