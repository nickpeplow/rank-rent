<section class="py-5 py-md-6">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-6 position-relative">
        <div class="sticky-top" style="top: 20px;">
          <?php
          $homepage_why = rnr_replace(get_post_meta(get_the_ID(), 'homepage_why', true));
          $why_image = $homepage_why['why_image'] ?? null;
          $image_url = $why_image['url'] ?? 'https://via.placeholder.com/600x600';
          $image_alt = $why_image['alt'] ?? 'Why Choose Us Image';
          $image_width = $why_image['width'] ?? 600;
          $image_height = $why_image['height'] ?? 600;
          ?>
          <img class="img-fluid" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" width="<?php echo esc_attr($image_width); ?>" height="<?php echo esc_attr($image_height); ?>">
        </div>
      </div>
      <div class="col-lg-6">
        <?php
        $why_heading = $homepage_why['why_heading'] ?? 'Default Why Choose Us Heading';
        $why_subheading = $homepage_why['why_subheading'] ?? 'Default Why Choose Us Subheading';
        ?>
        <h2 class="mb-3 pb-2 border-bottom border-bottom-primary border-thick"><?php echo esc_html($why_heading); ?></h2>
        <p class="lead mb-4"><?php echo esc_html($why_subheading); ?></p>
        <div class="row gy-4">
          <?php for ($i = 1; $i <= 6; $i++) : 
            $heading = $homepage_why["why_{$i}_heading"] ?? "Default Heading {$i}";
            $content = $homepage_why["why_{$i}_content"] ?? "Default Content {$i}";
          ?>
            <div class="col-sm-6">
              <h3 class="h5 text-primary-color mb-2"><?php echo esc_html($heading); ?></h3>
              <p class="mb-0"><?php echo esc_html($content); ?></p>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
</section>