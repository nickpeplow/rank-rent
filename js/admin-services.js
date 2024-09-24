jQuery(document).ready(function($) {
    $('.generate-content').on('click', function() {
        var button = $(this);
        var spinner = button.next('.spinner');
        var postId = button.data('post-id');
        var editor = tinyMCE.get('content_' + postId);

        spinner.addClass('is-active');

        $.ajax({
            url: ranknrentAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'generate_service_content',
                post_id: postId,
                nonce: ranknrentAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (editor) {
                        editor.setContent(response.data);
                    } else {
                        $('#content_' + postId).val(response.data);
                    }
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while generating content.');
            },
            complete: function() {
                spinner.removeClass('is-active');
            }
        });
    });
});
