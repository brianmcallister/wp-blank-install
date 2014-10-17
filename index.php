<?php
# WordPress view bootstrapper. Basically the same as the default WordPress 
# index.php file.
define('WP_USE_THEMES', true);
require(dirname(__FILE__) . '/wp/wp-blog-header.php');

# Force users to login in any environment other than 'development' and 
# 'production'.
if (ENV !== 'development' && ENV !== 'production') {
  if (!is_user_logged_in()) {
    auth_redirect();
  }
}