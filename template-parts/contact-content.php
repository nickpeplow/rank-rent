<?php
// Get the current post
$post = get_post();

// Output the post title


// Output the post content
echo apply_filters('the_content', $post->post_content);
?>

<div class="contact-form-container">
    <h2>Contact Us</h2>
    <?php echo do_shortcode('[contact]'); ?>
</div>