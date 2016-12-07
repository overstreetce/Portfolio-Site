<?php
/* Welcome to vbtk :)
This is the core vbtk file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

*/

/*********************
LAUNCH vbtk
Let's fire off all the functions
and tools. I put it up here so it's
right up top and clean.
*********************/

// we're firing all out initial functions at the start
add_action('after_setup_theme','vbtk_start', 16);

function vbtk_start() {

    // launching operation cleanup
    add_action('init', 'vbtk_head_cleanup');
    // remove WP version from RSS
    add_filter('the_generator', 'vbtk_rss_version');
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', 'vbtk_remove_wp_widget_recent_comments_style', 1 );
    // clean up comment styles in the head
    add_action('wp_head', 'vbtk_remove_recent_comments_style', 1);
    // clean up gallery output in wp
    add_filter('gallery_style', 'vbtk_gallery_style');

    // enqueue base scripts and styles
    add_action('wp_enqueue_scripts', 'vbtk_scripts_and_styles', 999);
    // ie conditional wrapper

    // launching this stuff after theme setup
    vbtk_theme_support();

    // adding sidebars to Wordpress (these are created in functions.php)
    add_action( 'widgets_init', 'vbtk_register_sidebars' );
    // adding the vbtk search form (created in functions.php)
    add_filter( 'get_search_form', 'vbtk_wpsearch' );

    // cleaning up random code around images
    add_filter('the_content', 'vbtk_filter_ptags_on_images');
    // cleaning up excerpt
    add_filter('excerpt_more', 'vbtk_excerpt_more');

} /* end vbtk start */

/*********************
WP_HEAD GOODNESS
The default wordpress head is a mess. 
Let's clean it up by removing all the junk we don't need.
*********************/

function vbtk_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
} /* end vbtk head cleanup */

// remove WP version from RSS
function vbtk_rss_version() { return ''; }

// remove injected CSS for recent comments widget
function vbtk_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}

// remove injected CSS from recent comments widget
function vbtk_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

// remove injected CSS from gallery
function vbtk_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function vbtk_scripts_and_styles() {
  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
  if (!is_admin()) {
    $theme_version = wp_get_theme()->Version;

	// removes WP version of jQuery
	wp_deregister_script('jquery');
	
	// loads jQuery 2.1.0
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/bower_components/foundation/js/vendor/jquery.js', array(), '2.1.0', false );
    
    // modernizr (without media query polyfill)
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/bower_components/foundation/js/vendor/modernizr.js', array(), '2.5.3', false );
    wp_enqueue_script( 'fastclick', get_template_directory_uri().'/bower_components/fastclick/lib/fastclick.js', array(), false, true);
    
    // adding Foundation scripts file in the footer
    wp_enqueue_script( 'foundation-js', get_template_directory_uri() . '/bower_components/foundation/js/foundation.min.js', array( 'jquery' ), $theme_version, true );

  	// wp_enqueue_script( 'foundation.abide', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.abide.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.accordion', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.accordion.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.alert', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.alert.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.clearing', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.clearing.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.dropdown', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.dropdown.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.interchange', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.interchange.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.equalizer', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.equalizer.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.joyride', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.joyride.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.magellan', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.magellan.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.offcanvas', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.offcanvas.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.equalizer', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.equalizer.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.orbit', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.orbit.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.reveal', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.reveal.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.tab', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.tab.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.tooltip', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.tooltip.js', array( 'foundation' ), false, true);
	// wp_enqueue_script( 'foundation.topbar', get_template_directory_uri().'/bower_components/foundation/js/foundation/foundation.topbar.js', array( 'foundation' ), false, true);

    // wp_enqueue_script( 'slick-carousel', get_template_directory_uri().'/bower_components/slick-carousel/slick/slick.min.js', array(), false, true);
    // wp_enqueue_script( 'tablesaw', get_template_directory_uri().'/bower_components/filament-tablesaw/dist/stackonly/tablesaw.stackonly.js', array(), false, true);
    // wp_enqueue_script( 'jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js', array( 'jquery' ), false, true);
	// wp_enqueue_script( 'jqueryui-touch', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array( 'jquery', 'jqueryui' ), false, true);

	wp_enqueue_style( 'slick-carousel.css', get_stylesheet_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), date( 'YmdH' ) );
	wp_enqueue_style( 'slick-carousel-theme.css', get_stylesheet_directory_uri() . '/bower_components/slick-carousel/slick/slick-theme.css', array(), date( 'YmdH' ) );
   
    // register main stylesheet
    wp_enqueue_style( 'vbtk-stylesheet', get_template_directory_uri() . '/library/css/style.css', array(), date( 'YmdH' ), 'all' );
    
    // register foundation icons
    wp_enqueue_style( 'foundation-icons', get_template_directory_uri() . '/library/css/icons/foundation-icons.css', array(), date( 'YmdH' ), 'all' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
      wp_enqueue_script( 'comment-reply' );
    }

    //adding scripts file in the footer
    wp_enqueue_script( 'vbtk-js', get_template_directory_uri() . '/library/js/min/scripts-min.js', array( 'jquery' ), date( 'YmdH' ), true );

  }
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function vbtk_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support('post-thumbnails');

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	add_image_size('home-portfolio-small', '600', '', false);
	add_image_size( 'slider-bg-lg', '1400', '505', true );

	// rss 
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );
	
	//html5 support (http://themeshaper.com/2013/08/01/html5-support-in-wordpress-core/)
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	

} /* end vbtk theme support */


function vbtk_get_the_excerpt($post_id) {
  global $post;  
  $save_post = $post;
  $post = get_post($post_id);
  setup_postdata( $post );
  $output = get_the_excerpt();
  $post = $save_post;
  return $output;
}

/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using vbtk_related_posts(); )
function vbtk_related_posts() {
	echo '<ul id="vbtk-related-posts">';
	global $post;
	$tags = wp_get_post_tags($post->ID);
	if($tags) {
		foreach($tags as $tag) { $tag_arr .= $tag->slug . ','; }
        $args = array(
        	'tag' => $tag_arr,
        	'numberposts' => 5, /* you can change this to show more */
        	'post__not_in' => array($post->ID)
     	);
        $related_posts = get_posts($args);
        if($related_posts) {
        	foreach ($related_posts as $post) : setup_postdata($post); ?>
	           	<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
	        <?php endforeach; }
	    else { ?>
            <?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'vbtktheme' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_query();
	echo '</ul>';
} /* end vbtk related posts function */

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function vbtk_page_navi($before = '', $after = '') {
	global $wpdb, $wp_query;
	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;
	if ( $numposts <= $posts_per_page ) { return; }
	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}
	$pages_to_show = 7;
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;
	if($start_page <= 0) {
		$start_page = 1;
	}
	$end_page = $paged + $half_page_end;
	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if($start_page <= 0) {
		$start_page = 1;
	}
	echo $before.'<nav class="page-navigation"><ul class="pagination">'."";
	if ($start_page >= 2 && $pages_to_show < $max_page) {
		$first_page_text = __( "First", 'vbtktheme' );
		echo '<li><a href="'.get_pagenum_link().'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
	}
	echo '<li>';
	previous_posts_link('<<');
	echo '</li>';
	for($i = $start_page; $i  <= $end_page; $i++) {
		if($i == $paged) {
			echo '<li class="current"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
		} else {
			echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
		}
	}
	echo '<li>';
	next_posts_link('>>');
	echo '</li>';
	if ($end_page < $max_page) {
		$last_page_text = __( "Last", 'vbtktheme' );
		echo '<li><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
	}
	echo '</ul></nav>'.$after."";
} /* end page navi */

/*********************
ADD FOUNDATION FEATURES TO WORDPRESS
*********************/
// Add "has-dropdown" CSS class to navigation menu items that have children in a submenu.
function nav_menu_item_parent_classing( $classes, $item )
{
    global $wpdb;
    
    if ( 
        !property_exists( $item, 'classes' ) 
        || !is_array( $item->classes )
    ) {
        return $classes;
    }

    $has_children = in_array( 'menu-item-has-children', $item->classes );

    if ( $has_children ) {
        array_push( $classes, "has-dropdown" );
    }

    return $classes;
}
 
add_filter( "nav_menu_css_class", "nav_menu_item_parent_classing", 10, 2 );

//Deletes empty classes and changes the sub menu class name
    function change_submenu_class($menu) {
        $menu = preg_replace('/ class="sub-menu"/',' class="dropdown"',$menu);
        return $menu;
    }
    add_filter ('wp_nav_menu','change_submenu_class');


//Use the active class of the ZURB Foundation for the current menu item. (From: https://github.com/milohuang/reverie/blob/master/functions.php)
function required_active_nav_class( $classes, $item ) {
    if ( $item->current == 1 || $item->current_item_ancestor == true ) {
        $classes[] = 'active';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'required_active_nav_class', 10, 2 );

// Search Form
function vbtk_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __('Search for:', 'vbtktheme') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','vbtktheme').'" />
	<input type="submit" id="searchsubmit" class="button" value="'. esc_attr__('Search') .'" />
	</form>';
	return $form;
} // don't remove this bracket!

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs
function vbtk_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function vbtk_excerpt_more($more) {
	global $post;
	// edit here if you like
return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __('Read', 'vbtktheme') . get_the_title($post->ID).'">'. __('Read more &raquo;', 'vbtktheme') .'</a>';
}

//  Stop WordPress from using the sticky class (which conflicts with Foundation), and style WordPress sticky posts using the .wp-sticky class instead
function remove_sticky_class($classes) {
	$classes = array_diff($classes, array("sticky"));
	$classes[] = 'wp-sticky';
	return $classes;
}
add_filter('post_class','remove_sticky_class');

/*
 * This is a modified the_author_posts_link() which just returns the link.
 *
 * This is necessary to allow usage of the usual l10n process with printf().
 */
function vbtk_get_the_author_posts_link() {
	global $authordata;
	if ( !is_object( $authordata ) )
		return false;
	$link = sprintf(
		'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
		get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
		esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ), // No further l10n needed, core will take care of this one
		get_the_author()
	);
	return $link;
}

if( function_exists('acf_add_options_page') ) {
	
	
	acf_add_options_page(array(
		'page_title' 	=> 'Site Settings',
		'menu_title'	=> 'Site Settings',
		'menu_slug' 	=> 'site-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	
}

/**
 * Extend WordPress search to include custom fields
 *
 * http://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    
    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;
   
    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );


?>
