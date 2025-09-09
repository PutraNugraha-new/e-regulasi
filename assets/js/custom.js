/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

$(function () {
    $('.sidebar-menu a[href^="' + base_url + location.pathname.split("/")[2] + '"]').parent().addClass('active');

});

let optionsHoldOn = {
    theme: "sk-cube-grid",
    message: 'Loading',
    textColor: "white"
};