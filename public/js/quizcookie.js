$(document).on('click', '.btn', function (e) {
    var expires = "";
    var date = new Date();
    date.setTime(date.getTime() + (360 * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
    document.cookie = 'quiz' + "=" + this.id + expires + "; ";
});