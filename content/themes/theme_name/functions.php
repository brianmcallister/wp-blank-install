<?php

# Register custom post types.
$custom_post_types = array(
  'social_media_links' => 'Social Media'
);
  
foreach ($custom_post_types as $id => $title) {
  register_post_type($id, array(
    'label' => $title,
    'description' => $title,
    'public' => true,
    'has_archive' => true,
    'rewrite' => false,
    'query_var' => false
  ));
}

# Register nav menus.
register_nav_menus(array(
  'global_nav_primary' => 'Primary global navigation.',
  'global_nav_secondary' => 'Secondary global navigation.'
));