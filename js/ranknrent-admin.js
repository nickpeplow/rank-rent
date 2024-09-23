jQuery(document).ready(function($){
    $('#upload_hero_image_button').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('#site_default_hero').val(image_url);
            $('#hero_image_preview').attr('src', image_url).show();
        });
    });
});
