<?php

/**
 * Enqueue scripts and styles.
 */
function theme_scripts() {


    wp_enqueue_style( 'vuewp-style', get_stylesheet_uri() );


//BootStrap:
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, '1.8.1', false );
    wp_enqueue_script( 'jquery' );

    wp_register_script( 'popper',  'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js', false, '1.13.0', true); //must be .umd.
    wp_enqueue_script( 'popper' ); //enables all pop-over links.

    wp_register_script( 'modernizr',  'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', false, '2.8.3', true );
    wp_enqueue_script( 'modernizr' );

}
add_action( 'wp_enqueue_scripts', 'theme_scripts', 10 );

