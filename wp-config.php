<?php
# W3 Total Cache.
# define('WP_CACHE', getenv('ENVIRONMENT') === 'production' ? true : false);

# Misc.
define('DISALLOW_FILE_EDIT', true);

# Site URLs
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp/');

# Local configuration file.
if (file_exists(dirname(__FILE__) . '/local-config.php')) {
  include(dirname(__FILE__) . '/local-config.php');
}

# Local revision file.
if (file_exists(dirname(__FILE__) . '/REVISION')) {
  $rev = file_get_contents(dirname(__FILE__) . '/REVISION');
  define('REVISION', $rev);
} else {
  define('REVISION', '');
}

# Custom content directory.
define('WP_CONTENT_DIR', dirname(__FILE__) . '/content');
define('WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content');

# MySQL settings.
define('DB_NAME', DB_NAME);
define('DB_USER', DB_USER);
define('DB_PASSWORD', DB_PASS);
define('DB_HOST', DB_HOST);

# Other database settings.
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

# Prefix.
$table_prefix  = 'wp_';

# Language.
define('WPLANG', '');

# Debug.
define('WP_DEBUG', ENV === 'production' ? false : true);

# Go.
require_once(ABSPATH . 'wp-settings.php');