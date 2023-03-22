$(document).ready(function() {
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        var message = $('input[name=message]').val();
        var form_data = jQuery(this).serializeArray();
        var ajax_url = atob(atob(form_data.find(input => input.name == 'base').value));
        $.ajax({
            url: ajax_url,
            method: 'POST',
            data: form_data,
            success: function(response) {
                $('#chat-log').append('<p class="sen-mesaj"> ' + message + '<img decoding="async" src="/wp-content/plugins/openai-chat/images/you.png"></p>');
                $('#chat-log').append('<p class="bot-mesaj"> <img decoding="async" src="/wp-content/plugins/openai-chat/images/bot.png"> ' + response + '</p>');
                $('input[name=message]').val('');
            }
        });
    });
});