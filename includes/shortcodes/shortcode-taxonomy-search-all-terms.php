<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register the 'taxonomy_search_all_terms' shortcode
add_shortcode( 'taxonomy_search_all_terms', 'taxonomy_search_all_terms_shortcode' );

function taxonomy_search_all_terms_shortcode( $atts ) {
    ob_start();

    // Shortcode attributes
    $atts = shortcode_atts( array(
        'taxonomy'       => 'product_cat', // Replace with your taxonomy name
        'terms_per_page' => 10,
        'action_url'     => '', // New attribute for the form action URL
        'placeholder'    => 'Search Term Names',
    ), $atts );

    // Retrieve the search query from the URL parameter
    $search_query = isset( $_GET['term_search'] ) ? sanitize_text_field( $_GET['term_search'] ) : '';
    // Set the default action URL to the current page URL if not provided in the attribute
    $action_url = empty( $atts['action_url'] ) ? esc_url( home_url( $wp->request ) ) : esc_url( $atts['action_url'] );

    // Display the search form with nonce field
    echo '<div class="taxonomy-search-form">';
    echo '<form method="get" data-taxonomy="' . esc_attr( $atts['taxonomy'] ) . '" action="' . $action_url . '">';
    wp_nonce_field( 'taxonomy_search_autocomplete', 'taxonomy_search_autocomplete_nonce' );
    echo '<input type="search" id="term_search" name="term_search" placeholder="' . $atts['placeholder'] . '" value="' . esc_attr( $search_query ) . '" />';
    echo '<button type="submit" class="taxonomy-search-submit"><span> </span></button>';
    echo '</form>';
    // Autocomplete results container
    echo '<div class="autocomplete-results taxonomy-search-autocomplete" id="taxonomy-search-autocomplete"></div>';
    echo '</div>';

    
    if ( class_exists( 'Taxonomy_Search_WooCommerce' ) ) {
        $plugin_instance = new Taxonomy_Search_WooCommerce();

        if ( ! empty( $search_query ) ) {
            // Display searched terms only
            $plugin_instance->display_search_results( $atts['taxonomy'], $atts['terms_per_page'], $search_query );
        } else {
            // Display all terms
            $plugin_instance->display_search_results( $atts['taxonomy'], $atts['terms_per_page'], '' );
        }
    } else {
        echo 'Taxonomy_Search_WooCommerce class not found. Please check the plugin activation and file paths.';
    }


    return ob_get_clean();
}
