var $collectionHolder;


var $addTagLink = $('<a href="#" class="add_tag_link btn btn-secondary">Add a answer</a>');
var $newLinkLi = $('<div class="blockquote text-center my-3"></div>').append($addTagLink);

jQuery(document).ready(function () {

    $collectionHolder = $('ul.tags');

    $collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });


    $collectionHolder.append($newLinkLi);


    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function (e) {

        e.preventDefault();


        addTagForm($collectionHolder, $newLinkLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {

    var prototype = $collectionHolder.data('prototype');


    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);


    $collectionHolder.data('index', index + 1);

    var newFormLi = $('<li></li>').append(newForm);


    $newLinkLi.before(newFormLi);

    addTagFormDeleteLink(newFormLi);
}


function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#" class="btn btn-secondary my-3">Delete this answer</a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {

        e.preventDefault();

        $tagFormLi.remove();
    });
}

