$(document).on('click', '.add_tag_link ', function(e) {
    var arrayOfQuestions = [];
    var checkArray = $.ajax({
        url: '/admin/autocomplete',
        type: "POST",
        dataType: "json",
        data: {
            "questions": "questions"
        },
        success: function (data) {
            data.forEach(function(item, i, arr) {
                arrayOfQuestions.push(item)
            });
        }
    });
    $.when(checkArray).done(function () {
        $( function() {
            $(".form-control").autocomplete({
                source: arrayOfQuestions
            });
        } );
    });
});