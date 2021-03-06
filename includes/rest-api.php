<?php

// Add the Number of queries to the header, b/c I'm a nerd.
add_filter( 'rest_pre_serve_request', function( $value ) {
    header( 'X-Query-Count: '.intval( get_num_queries() ) );
    return $value;
});

// Cache REST queries. Insta-load!
add_filter( 'rest_pre_serve_request', function( $result ) {
	header( 'Cache-Control: public, max-age=1200' ); // 20 minutes
	return $result;
} );


// WP-API Post Responses:
add_action( "rest_api_init", function () {

    // CUSTOM POST TYPES
    $post_types_args = array(
       'public'   => true,
       '_builtin' => false
    );

    $post_types = get_post_types( $post_types_args, 'names', 'and' );
    $posts_post_types = array_merge( array( "post", "posts" ), $post_types);
    $pages_posts_post_types = array_merge(array( "page", "pages", "post", "posts" ), $post_types);

    // Add date_rendered
    register_rest_field( $posts_post_types, "date_rendered", array(
        "get_callback" => function( $post ) {
            return get_the_date('', $post->id);
        },
        "schema" => array(
            "description" => __( "Pretty Date." ),
            "type"        => "string"
        ),
    ) );


    // Add date_link_path
    register_rest_field( $posts_post_types, "date_archive", array(
        "get_callback" => function( $post ) {
            $year = get_the_date('Y', $post->id);;
            $month = get_the_date('m', $post->id);;

            $url=get_month_link( $year, $month);

            return array( 'path'=> str_replace(  home_url() , "", $url ) , 'link'=>$url, 'year'=>$year, 'month'=>$month );
        },
        "schema" => array(
            "description" => __( "Date Path for VueRouter" ),
            "type"        => "string"
        ),
    ) );

    // Add permalink:
    register_rest_field( $pages_posts_post_types, "permalink_path", array(
        "get_callback" => function( $post ) {
            return str_replace(  home_url() , "", get_the_permalink($post->id) );
        },
        "schema" => array(
            "description" => __( "Permalink Path" ),
            "type"        => "string"
        ),
    ) );

    // Add permalink to terms:
    register_rest_field( array('category','term'), "permalink_path", array(
        "get_callback" => function( $term ) {
            return str_replace(  home_url() , "", $term['link'] );
        },
        "schema" => array(
            "description" => __( "Permalink Path" ),
            "type"        => "string"
        ),
    ) );

    // Add Author Info
    register_rest_field( $posts_post_types, "author_object", array(
        "get_callback" => function( $post ) {
            $user_id = $post['author'];
            $all_meta_for_user =  array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );
            $author_posts_url = get_author_posts_url($post['author']);
            return array(
                'id'=>$post['author'],
                'slug'=>get_the_author_meta('user_login', $post['author']),
                'first_name'=>$all_meta_for_user['first_name'],
                'last_name'=>$all_meta_for_user['last_name'],
                'nickname'=>$all_meta_for_user['nickname'],
                'description'=>$all_meta_for_user['description'],
                'link_to_posts'=>$author_posts_url,
                'permalink_path'=>str_replace(  home_url() , "", $author_posts_url )
            );
        },
        "schema" => array(
            "description" => __( "Author Data." ),
            "type"        => "object"
        ),
    ) );


    // Add category info to post-details, not just the category-ids
    register_rest_field( $posts_post_types, "categories_list", array(
        "get_callback" => function( $post ) {
            $cats=array();
            foreach((get_the_category($post['id'])) as $category) {
                $cat_link = get_category_link($category->term_id);
                $cats[] = array_merge( (array)$category, array('link'=>$cat_link, 'permalink_path'=> str_replace(  home_url() , "", ($cat_link) ) )  );
            }
            return $cats;
        },
        "schema" => array(
            "description" => __( "Categories Detail" ),
            "type"        => "object"
        ),
    ) );


    // Add featured_image url to post-details, not just the media-id
    register_rest_field(  $pages_posts_post_types, "featured_image", array(
        "get_callback" => function( $post ) {
            return (object) array(
                "html"=>get_the_post_thumbnail( $post['id'], 'large' ),
                "url"=>wp_get_attachment_image_src( get_post_thumbnail_id( $post['id']), 'full', false  ),
                "url_large"=>wp_get_attachment_image_src( get_post_thumbnail_id( $post['id']), 'large'  , false),
                "url_square"=>wp_get_attachment_image_src( get_post_thumbnail_id( $post['id']), 'square' , false ),

              );
        },
        "schema" => array(
            "description" => __( "Featured Image HTML." ),
            "type"        => "object"
        ),
    ) );


}, 3 );


/**
* Add REST API support to *ALL* already registered post types. Cause thing to break 
*/
add_action( 'init', 'wp_rest_custom_post_type', 25 );
function wp_rest_custom_post_type() {
  global $wp_post_types;

  foreach($wp_post_types as $post_type_name =>$post_type){
    if( isset( $wp_post_types[ $post_type_name ] ) ) {
      $wp_post_types[$post_type_name]->show_in_rest = true;
      $wp_post_types[$post_type_name]->rest_base = $post_type_name;
      $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
    }
  }

}

/**
* Add REST API support to *ALL* already registered taxonomies.
*/
add_action( 'init', 'wp_rest_custom_taxonomy', 25 );
function wp_rest_custom_taxonomy() {
  global $wp_taxonomies;

  foreach($wp_taxonomies as $taxonomy_name =>$taxonomy){
    if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
      $wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
      $wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
      $wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
    }
  }

}


/**
 * ADD REST API Support for advanced custom fields
 */
function  markers_endpoint( $request_data ) {
    $args = array(
        'post_type' => 'post',
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
        register_rest_route( 'markers/v1', '/post/', array(
        'methods' => 'GET',
        'callback' => 'markers_endpoint'
    ));
});

/**
 * ADD REST API Support for advanced custom fields
 */
function console_log( $args) {
    echo '<script>console.log('. json_encode($args) .')</script>';
}
function  colors_endpoint( $request_data ) {
   $menu = wp_get_nav_menu_object('menu-1');
   return array(get_field('primary_color', $menu), get_field('background_color', $menu), get_bloginfo('name'), get_bloginfo('description') );
}

    add_action( 'rest_api_init', function () {
        register_rest_route( 'globals/v1', '/colors/', array(
        'methods' => 'GET',
        'callback' => 'colors_endpoint'
    ));
});
