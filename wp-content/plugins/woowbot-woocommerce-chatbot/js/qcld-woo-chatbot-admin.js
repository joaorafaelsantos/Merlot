jQuery(function ($) {
$(document).ready(function () {
    [].slice.call(document.querySelectorAll('.woo-chatbot-tabs')).forEach(function (el) {
        new CBPFWTabs(el);
    });
});
});