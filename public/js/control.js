$(document).ready(function () {
    $('#included_questions').DataTable({
        "scrollY": "30vh",
        "scrollCollapse": true,
        "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]]
    });
    $('#not_included_questions').DataTable({
        "scrollY": "30vh",
        "scrollCollapse": true,
        "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]]
    });
    $('.dataTables_length').addClass('bs-select');
});