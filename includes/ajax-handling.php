<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Ajax callback for autocomplete search
add_action( 'wp_ajax_taxonomy_search_autocomplete', 'taxonomy_search_autocomplete_callback' );
add_action( 'wp_ajax_nopriv_taxonomy_search_autocomplete', 'taxonomy_search_autocomplete_callback' );

function taxonomy_search_autocomplete_callback() {
    // Verify the nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'taxonomy_search_autocomplete' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Get the search query
    $search_query = isset( $_POST['search_query'] ) ? sanitize_text_field( $_POST['search_query'] ) : '';
    // Get the taxonomy from the form data
    $taxonomy = isset( $_POST['taxonomy'] ) ? sanitize_text_field( $_POST['taxonomy'] ) : '';

    // Query arguments
    $args = array(
        'taxonomy'   => $taxonomy, // Use the passed taxonomy
        'hide_empty' => false,
        'number'     => 20,
        'search'     => $search_query,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );

    // Perform the term query
    $terms = get_terms( $args );

    // Prepare the results
    $results = array();
    if ( $terms && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $results[] = array(
                'name' => $term->name,
                'url'  => get_term_link( $term ),
            );
        }
    }

    // Return the results
    wp_send_json_success( $results );
}
