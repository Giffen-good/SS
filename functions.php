<?php

include_once('includes/setup.php');
include_once('includes/enqueues.php');
include_once('includes/localize.php');
include_once('includes/vue-router.php');
include_once('includes/rest-api.php');

define('UPLOAD', 'wp-content/uploads');

function  contacts_endpoint( $request_data ) {
    $args = array(
        'post_type' => 'page',
        'posts_per_page'=>-1,
        'numberposts'=>-1
    );

    $posts = get_posts($args);
    foreach ($posts as $key => $post) {
        $posts[$key]->acf = get_fields($post->ID);
    }
    return  $posts;
    }

    add_action( 'rest_api_init', function () {
        register_rest_route( 'contactpage/v1', '/post/', array(
        'methods' => 'GET',
        'callback' => 'contacts_endpoint'
    ));
});
