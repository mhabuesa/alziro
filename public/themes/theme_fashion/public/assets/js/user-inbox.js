"use strict";

$(document).ready(function () {

    function scrollToBottom() {
        $(".scroll_msg").stop().animate({scrollTop: $(".scroll_msg")[0].scrollHeight}, 1000);
    }

    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".chat_list").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("#msgSendBtn").on('click', function (e) {
        e.preventDefault();

        const myForm = $('#myForm');
        const msgInputValue = myForm.find('#msgInputValue').val();
        const hiddenValue = myForm.find('#hidden_value').val();
        const sellerValue = myForm.find('#seller_value').val();
        const hiddenValueDM = myForm.find('#hidden_value_dm').val();

        const data = {
            message: msgInputValue,
            shop_id: hiddenValue,
            seller_id: sellerValue,
            delivery_man_id: hiddenValueDM,
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        const now_text = $('.messages-storage').data('textnow');

        $.ajax({
            type: "post",
            url: $('.messages-storage').data('messagesroute'),
            data: data,
            success: function (response) {
                if (response.message) {
                    $(".msg_history").append(`<li class="outgoing" id="outgoing_msg">
                                                <div class="msg-area">
                                                    <div class="msg">
                                                        ${response.message}
                                                    </div>
                                                    <small class="time_date">${now_text}</small>
                                                </div>
                                            </li>`);
                }
                scrollToBottom();
                myForm.find('#msgInputValue').val('');

            },
            error: function (error) {
                toastr.warning(error.responseJSON);
            }
        });

    });

    scrollToBottom();
});
