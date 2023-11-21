<?php
/**
 * Plugin Name: Taxonomy Search WooCommerce
 * Plugin URI: https://your-plugin-uri.com
 * Description: Add taxonomy search functionality with WooCommerce integration.
 * Version: 1.0.1
 * Author: Sirajum Mahdi
 * Author URI: https://sirajummahdi.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: taxonomy-search-woocommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include the main plugin class
require_once plugin_dir_path( __FILE__ ) . 'class-taxonomy-search-woocommerce.php';

// Include the shortcode files
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/shortcode-taxonomy-search.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/shortcode-taxonomy-search-all-terms.php';

// Include the updater files
require_once plugin_dir_path( __FILE__ ) . 'includes/extra/updater-helper.php';



// Initialize the plugin
new Taxonomy_Search_WooCommerce();
