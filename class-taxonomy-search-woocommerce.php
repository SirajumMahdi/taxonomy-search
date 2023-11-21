<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define the plugin class
class Taxonomy_Search_WooCommerce {

    public function __construct() {
        // Register activation hook
        register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

        // Enqueue assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // Include the AJAX handling file
        require_once plugin_dir_path( __FILE__ ) . 'includes/ajax-handling.php';

        // Ajax callback for autocomplete search
        add_action( 'wp_ajax_taxonomy_search_autocomplete', array( $this, 'taxonomy_search_autocomplete_callback' ) );
        add_action( 'wp_ajax_nopriv_taxonomy_search_autocomplete', array( $this, 'taxonomy_search_autocomplete_callback' ) );
    }

    // Display search results
    public function display_search_results( $taxonomy, $terms_per_page, $search_query ) {
        global $wp; // Add this line to access the $wp global variable
        // Current page number
        $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

        // Calculate the offset
        $offset = ( $paged - 1 ) * $terms_per_page;

        // Query arguments
        $args = array(
            'taxonomy'    => $taxonomy,
            'hide_empty'  => false,
            'number'      => $terms_per_page,
            'offset'      => $offset,
            'search'      => $search_query,
            'orderby'     => 'name',
            'order'       => 'ASC',
        );

        // Perform the term query
        $terms = get_terms( $args );

        // Count the total number of terms found
        $total_terms = wp_count_terms( $taxonomy, array( 'search' => $search_query ) );

        // Display the search results or all terms
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            if ( $total_terms > 0 ) {
                // echo '<p>Number of terms found: ' . $total_terms . '</p>';
                echo '<br />';
                echo '<div class="tax-lists">';
                foreach ( $terms as $term ) {
                    $term_name     = $term->name;
                    $term_url      = get_term_link( $term );
                    $terms_meta_author = get_term_meta( $term->term_id, 'ewa_author_image', true );
                    $terms_meta_publisher = get_term_meta( $term->term_id, 'ewp_publisher_image', true );
                    $image_author  = wp_get_attachment_image( $terms_meta_author, 'thumbnail' );
                    $image_publisher = wp_get_attachment_image( $terms_meta_publisher, 'thumbnail' );

                    // Display the term name and URL
                    echo '<div class="tax-item">';
                    if ( $taxonomy === "ewa-author" ) {
                        echo '<div class="term-img img-author">';
                        if ( ! empty( $image_author ) ) {
                            echo '<span><a href="' . esc_url( $term_url ) . '">' . $image_author . '</a></span>';
                        } else {
                            echo '<span><img width="150" height="150" src="https://linnaas.com/wp-content/uploads/2023/03/user.png"></span>';
                        }
                        echo '</div>';
                    } elseif ( $taxonomy === "ewp-publisher" ) {
                        echo '<div class="term-img img-publisher">';
                        if ( ! empty( $image_publisher ) ) {
                            echo '<span><a href="' . esc_url( $term_url ) . '">' . $image_publisher . '</a></span>';
                        } else {
                            echo '<span><img width="150" height="150" src="https://linnaas.com/wp-content/uploads/2023/06/Demo.jpg"></span>';
                        }
                        echo '</div>';
                    } else {
                        // do nothing
                    }
                    echo '<div class="new-test-term-name">';
                    echo '<h4><a href="' . esc_url( $term_url ) . '">' . $term_name . '</a></h4>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';

                // Pagination
                $base_url = home_url( '/taxonomy-search-results' ); // Change this to your search results page URL
                $total_pages = ceil( $total_terms / $terms_per_page );

                if ( $total_pages > 1 ) {
                    echo '<div class="pagination">';
                    echo paginate_links(
                        array(
                            'base'      => esc_url( add_query_arg( 'paged', '%#%', home_url( $wp->request ) ) ), // Updated to use home_url and add_query_arg
                            'format'    => '?paged=%#%',
                            'current'   => $paged,
                            'total'     => $total_pages,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'add_args'  => ! empty( $search_query ) ? array( 'term_search' => urlencode( $search_query ) ) : array(),
                        )
                    );
                    echo '</div>';
                }
            } else {
                echo 'No terms found.';
            }
        } else {
            echo 'No terms found.';
        }
    }

    // Enqueue assets
    public function enqueue_assets() {
        // Enqueue CSS file
        wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.0.2.0' );

        // Enqueue JS file
        wp_enqueue_script( 'main-js', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array( 'jquery' ), '1.0.2.0', true );

        // Localize script with AJAX URL
        wp_localize_script(
            'main-js',
            'tax_search_ajax',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
}
