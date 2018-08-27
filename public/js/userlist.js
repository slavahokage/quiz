$(document).on('click', '.form-check-input', function(e) {
    if(this.checked){
        $.ajax({
            url: '/admin/ajaxUserList',
            type: "POST",
            dataType: "json",
            data: {
                "status": 1,
                "id": this.id
            },
        });
    }else {
        $.ajax({
            url: '/admin/ajaxUserList',
            type: "POST",
            dataType: "json",
            data: {
                "status": 0,
                "id": this.id
            },
        });
    }
});