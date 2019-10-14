<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php

$menu = wp_get_nav_menu_object('menu-1');
$colors = array( get_field('primary_color', $menu), get_field('background_color', $menu), get_field('header_text_color', $menu) );
$primary = $colors[1];
list($r, $g, $b) = sscanf($primary, "#%02x%02x%02x");
echo '<style>:root {
--primary-color:'. $colors[1] . ';
--secondary-color:'. $colors[0] . ';
--txt-color:'. $colors[2] . ';
--primary-color-rgba:linear-gradient(to top, rgba(' . $r . ',' . $g .',' . $b . ', 1),rgba(' . $r . ',' . $g .',' . $b . ', .97) 40%,rgba(' . $r . ',' . $g .',' . $b . ', 0.75) 60%,rgba(' . $r . ',' . $g .',' . $b . ', 0) 100%);
}
</style>';
?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#fdc9e8">
    <meta name="msapplication-TileColor" content="#fdc9e8">
    <meta name="theme-color" content="#fDC9E8">

    <?php wp_head(); ?>
</head>
<body>

    <header class="site-header psf c12">



      <nav class="lg x Dcolor-r navbar-toggleable-md <?php if(is_front_page()){echo "front-page-nav";} ?>">
      <!-- fixed-top -->

        <? if (is_home() || is_front_page() ) {
          ?><h1>
          <?php
        } else {
          ?>
          <h2>
          <?
        } ?>
        <a class="navbar-brand alignleft align-left" href="<?php echo esc_url( home_url('/') ); ?>">
          <span sm="dn"><?php echo get_bloginfo('name'); ?></span>
          <span sm="dib" class="dn">SG</span>
        </a>

<? if (is_home() || is_front_page() ) {
          ?></h1>
          <?php
        } else {
          ?>
          </h2>
          <?
        } ?>

      <div>
        <p class="fs2"><?php echo get_bloginfo('description'); ?></p>
      </div>
          <?php
            wp_nav_menu( array(
              'theme_location'  => 'primary',
              'container'       => false,
              'menu_class'      => '',
              'fallback_cb'     => '__return_false',
              'items_wrap'      => '<ul class="x tdn">%3$s</ul>',
              'depth'           => 2
            ) );
          ?>

        </div>
      </nav>
    </header>
    <? if (is_home() || is_front_page() ) { ?>
    <div class="z4 Dcolor-r loader">
    <div class="psa c10 ttu cxy">
      <h2><? echo get_bloginfo('name'); ?></h2>
      <h6 class="fs2 "><? echo get_bloginfo('description'); ?></h6>
    </div>
  </div>
  <script>
         document.querySelector('.loader').style.height = window.innerHeight + 'px';
</script>
    <?
    }
      ?>
    <noscript>
        <div id="content">
            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) {
                    echo '<h1>' . single_post_title( '', false ) . '</h1>';
                }

                while ( have_posts() ) : the_post();

                    if ( is_singular() ) {
                        the_title( '<h1>', '</h1>' );
                    } else {
                        the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
                    }

                    the_content();

                endwhile;

            endif;
            ?>
        </div>
    </noscript>
    
    <div id="app">
    </div>
   
    <?php wp_footer(); ?>

</body>
</html>
