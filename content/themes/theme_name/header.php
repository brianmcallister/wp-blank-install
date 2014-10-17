<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, minimal-ui">
  
  <?php
  $template_dir = get_template_directory_uri();
  $js_path = "$template_dir/assets/js";
  $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false ? 'http' : 'https';
  $url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  
  if (is_single()) {
    $title = get_the_title();
    $img = get_post_image($post);
  } else {
    $title = get_bloginfo('name');
    $img = '';
  }
  ?>
  
  <meta property="og:title" content="<?php echo $title; ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo $url; ?>">
  <meta property="og:image" content="<?php echo $img; ?>">
  
  <title><?php bloginfo('name'); ?></title>
  
  <?php if (ENV !== 'development'): ?>
    <?php $styles = glob(TEMPLATEPATH . '/assets/css/*.css'); ?>
    
    <?php foreach ($styles as $style): ?>
      <link rel="stylesheet" href="<?php echo $template_dir ?>/assets/css/<?php echo basename($style); ?>" type="text/css">
    <?php endforeach; ?>
    
    <?php $js_path = "$js_path/" . REVISION; ?>
    <script async data-main="<?php echo $js_path; ?>/main" src="<?php echo $js_path; ?>/libs/require.js"></script>
  <?php else: ?>
    <link rel="stylesheet" href="<?php echo $template_dir; ?>/assets/css/style.css" type="text/css">
    
    <script async data-main="<?php echo $template_dir; ?>/assets/js/main" src="<?php echo $template_dir; ?>/assets/js/libs/require.js"></script>
    <script async src="<?php echo $template_dir; ?>/assets/js/templates.js"></script>
  <?php endif; ?>
  
  <script>
    window.app = window.app || {}
    
    <?php if (ENV === 'production'): ?>
      window._gaq = window._gaq || [];
      window._gaq.push(['_setAccount', '']); //your code here
      window._gaq.push(['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript'; 
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
      })();
    <?php endif; ?>
  </script>
  
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header class="global-header">
    <div class="container">
      <a class="global-logo svg-background-image logo" href="/"><?php echo bloginfo('description'); ?></a>
    
      <?php
      $locations = get_nav_menu_locations();
      $primary_menu = wp_get_nav_menu_object($locations['global_nav_primary']);
      $secondary_menu = wp_get_nav_menu_object($locations['global_nav_secondary']);
      $primary_items = wp_get_nav_menu_items($primary_menu->term_id);
      $secondary_items = wp_get_nav_menu_items($secondary_menu->term_id);
      ?>
    
      <div class="global-navigation">
        <nav class="primary">
          <ul>
            <?php foreach ($primary_items as $item): ?>
              <li class="item"><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>
    
        <nav class="secondary">
          <ul>
            <?php foreach ($secondary_items as $item): ?>
              <li class="item"><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>
    </div>
  </header>