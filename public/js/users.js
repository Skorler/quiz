$(document).ready(function(){

    $("#selectAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $("#block").on("click", function(e){
        e.preventDefault();
        $('form[name = "delete_form"]').prop('selectedUsers', "/table/block").submit();
    });

    $("#unblock").on("click", function(e){
        e.preventDefault();
        $('form[name = "delete_form"]').attr('selectedUsers', '/table/unblock').submit();
    });

    $("#delete").on("click", function(e){
        e.preventDefault();
        $('form[name = "delete_form"]').attr('selectedUsers', "/table/delete").submit();
    });
});