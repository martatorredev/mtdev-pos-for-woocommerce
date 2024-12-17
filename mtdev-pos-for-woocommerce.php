<?php
/**
 * Plugin Name: MTDev POS for WooCommerce
 * Plugin URI:  https://github.com/martatorredev/mtdev-pos-for-woocommerce
 * Description: A lightweight POS (Point of Sale) plugin for WooCommerce. MVP version for managing physical sales.
 * Version:     1.0.0
 * Author:      Marta Torre
 * Author URI:  https://martatorre.dev
 * Text Domain: mtdev-pos-for-woocommerce
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Initialize the plugin
function mtdev_pos_init() {
    add_action( 'admin_menu', 'mtdev_pos_register_menu' );
    add_action( 'admin_enqueue_scripts', 'mtdev_pos_enqueue_assets' );
}
add_action( 'plugins_loaded', 'mtdev_pos_init' );

// Register POS admin menu
function mtdev_pos_register_menu() {
    add_menu_page(
        __( 'POS Terminal', 'mtdev-pos-for-woocommerce' ),
        __( 'POS Terminal', 'mtdev-pos-for-woocommerce' ),
        'manage_woocommerce',
        'mtdev-pos-terminal',
        'mtdev_pos_render_interface',
        'dashicons-cart',
        6
    );
}

// Enqueue assets
function mtdev_pos_enqueue_assets( $hook ) {
    if ( $hook !== 'toplevel_page_mtdev-pos-terminal' ) {
        return;
    }

    wp_enqueue_style( 'mtdev-pos-styles', plugin_dir_url( __FILE__ ) . 'assets/css/pos-style.css' );
    wp_enqueue_script( 'mtdev-pos-script', plugin_dir_url( __FILE__ ) . 'assets/js/pos-script.js', array( 'jquery' ), false, true );
    wp_localize_script( 'mtdev-pos-script', 'mtdevPosData', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'mtdev_pos_nonce' ),
    ));
}

// Render POS interface
function mtdev_pos_render_interface() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'MTDev
