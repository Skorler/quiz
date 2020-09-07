// setup an "add a tag" link
var addAnswerLink = $('<a href="#" class="add_answer_link">Add an answer</a>');
var $newLinkLi = $('<li></li>').append(addAnswerLink);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('ul.answers');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $(':checkbox').on('change', function() {
        $('input:checkbox').not(this).prop('checked', false);
    });


    addAnswerLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        e.stopImmediatePropagation();

        // add a new tag form (see code block below)
        addAnswerForm($collectionHolder, $newLinkLi);
    });


});

function addAnswerForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-tag">x</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}