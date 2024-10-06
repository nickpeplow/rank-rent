<?php
// Send 404 status header
status_header(404);
get_header();
?>

<div class="container text-center my-5">
    <h1>404 - Page Not Found</h1>
    <p>Sorry, the page you are looking for does not exist.</p>
    <a href="<?php echo home_url(); ?>" class="btn btn-primary text-white">Go to Homepage</a>
</div>

<?php
get_footer();
?>
