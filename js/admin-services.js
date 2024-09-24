jQuery(document).ready(function($) {
    $('.generate-content').on('click', function() {
        console.log('Generate content button clicked');
        var button = $(this);
        var serviceId = button.data('service-id');
        var editorId = 'service_content_' + serviceId;
        var spinner = button.next('.spinner');

        console.log('Service ID:', serviceId);

        spinner.addClass('is-active');
        button.prop('disabled', true);

        $.ajax({
            url: ranknrentAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'generate_service_content',
                nonce: ranknrentAjax.nonce,
                service_id: serviceId
            },
            success: function(response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId)) {
                        tinyMCE.get(editorId).setContent(response.data);
                    } else {
                        $('#' + editorId).val(response.data);
                    }
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                alert('An error occurred. Please try again.');
            },
            complete: function() {
                spinner.removeClass('is-active');
                button.prop('disabled', false);
            }
        });
    });
});
