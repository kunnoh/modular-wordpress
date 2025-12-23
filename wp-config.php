<?php
if (!function_exists('getenv_docker')) {
    function getenv_docker($env, $default) {
        if ($fileEnv = getenv($env . '_FILE')) {
            return rtrim(file_get_contents($fileEnv), "\r\n");
        }
        else if (($val = getenv($env)) !== false) {
            return $val;
        }
        else {
            return $default;
        }
    }
}
define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );
define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );
define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );
define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );
define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         'bbc4a155b751dc64e7b6b1a01abbd35b4bef2a77') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '57ddd3fc7fcdb023c262efa00602ac832b31a575') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '2bf02fd4d1b8302b2c91d5d1082786964260fd8f') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '3dca94a82ff21c02348e5e88f4c62bb6d857a960') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        'a0d8d4ff9f0e8ad7b36bae269458c0bf30c8f7be') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'da8fe8f4c6702dc08c8c59c2ecf812109a48c2f0') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   '1f3e4376df777b3bcb11cdb1716b6ef7e5a36ed9') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       '6c0f75652c1112a0fd08264ce772fe80e3592d2f') );
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');
define( 'FS_METHOD', 'direct' );
define( 'WP_DEBUG', getenv_docker('WORDPRESS_DEBUG', false) );
define( 'WP_DEBUG_DISPLAY', getenv_docker('WORDPRESS_DEBUG_DISPLAY', false) );
define( 'WP_DEBUG_LOG', getenv_docker('WORDPRESS_DEBUG_LOG', false) );
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}
if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
    eval($configExtra);
}
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
