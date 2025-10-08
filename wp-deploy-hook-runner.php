<?php
/**
 * Plugin Name: WP Deploy Hook Runner
 * Description: Secure webhook + WP‑CLI endpoint to run post‑deploy maintenance tasks (flush rewrite rules, purge caches, clear transients) from CI/CD.
 * Version: 1.0.0
 * Author: Best Website
 * Author URI: https://bestwebsite.com/
 * License: GPL-2.0+
 * Text Domain: wp-deploy-hook-runner
 */

if (!defined('ABSPATH')) exit;

define('WDHR_VERSION', '1.0.0');
define('WDHR_PATH', plugin_dir_path(__FILE__));

require_once WDHR_PATH . 'includes/class-wdhr-core.php';
require_once WDHR_PATH . 'includes/class-wdhr-admin.php';
require_once WDHR_PATH . 'includes/class-wdhr-rest.php';

if (defined('WP_CLI') && WP_CLI) {
    require_once WDHR_PATH . 'includes/class-wdhr-cli.php';
}
