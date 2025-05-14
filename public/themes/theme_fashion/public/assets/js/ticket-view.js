"use strict";
$(document).ready(function () {
    $(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);
});
