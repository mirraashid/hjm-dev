<?php

class HJM {

	public function __construct() {

		// Actions
		add_action( 'init', array(&$this, 'register_custom_menus'));
		add_action( 'init', array(&$this, 'changeTagsToTopics'));
		add_action( 'init', array(&$this, 'createPostTypeAndSettings' ) );
		add_action( 'init', array(&$this, 'createTopicCategoryTaxonomy' ) );
		add_action( 'init', array(&$this, 'register_custom_widgets'));
		add_action( 'wp_enqueue_scripts', array(&$this, 'custom_enqueue'));
		add_action( 'login_enqueue_scripts', array(&$this, 'custom_login_logo'));
		add_action( 'admin_menu', array(&$this, 'addCustomTaxonomyToMenu' ));
		add_action( 'template_redirect', array(&$this, 'removeDateArchives' ));

			// Tag modifications
			add_action( 'post_tag_edit_form_fields', array(&$this, 'addTermToCustomTaxonomy'));
			add_action( 'edited_post_tag', array(&$this, 'saveTermToCustomTaxonomy'));

			// Completely Remove Comments
			// Source: https://gist.github.com/mattclements/eab5ef656b2f946c4bfb
			add_action('admin_init', array(&$this, 'df_disable_comments_post_types_support')); // Disable support for comments and trackbacks in post types
			add_action('admin_menu', array(&$this, 'df_disable_comments_admin_menu')); // Remove comments page in menu
			add_action('admin_init', array(&$this, 'df_disable_comments_admin_menu_redirect')); // Redirect any user trying to access comments page
			add_action('init', array(&$this, 'df_disable_comments_admin_bar')); // Remove comments links from admin bar
			add_action('admin_init', array(&$this, 'df_disable_comments_dashboard')); // Remove comments metabox from dashboard

		// Filters
		add_filter( 'pre_option_link_manager_enabled', '__return_true' ); // Links support
		add_filter( 'embed_oembed_html', array(&$this, 'wrap_oembed_html'));
		add_filter( 'wp_title', array(&$this, 'custom_document_title_separator'), 10, 3 );
		add_filter( 'wpcf7_load_js', '__return_false' ); // Remove Contact Form 7 from all pages except home and contact
		add_filter( 'wpcf7_load_css', '__return_false' ); // Remove Contact Form 7 from all pages except home and contact
		add_filter( 'user_trailingslashit', array(&$this, 'remove_category'), 100, 2 );
		add_filter( 'post_gallery', array(&$this, 'customGalleryOutput'), 10, 3 ); // Customize [gallery]
		add_filter( 'wp_nav_menu_objects', array(&$this, 'addAncestorClassToMenu'));
		add_filter( 'query_vars', array(&$this, 'customQueryVars'));
		add_filter( 'manage_edit-post_tag_columns', array(&$this, 'customTagColumns'));
		add_filter( 'manage_post_tag_custom_column', array(&$this, 'customTagColumnsContent'), 10, 3);
		
		// Shortcode
		add_shortcode('author', array(&$this, 'shortcodeAuthor'));

		// Theme Support
		add_post_type_support('page', 'excerpt');
		add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
		add_theme_support( 'html5', [ 'script', 'style', 'search-form' ] ); // Removes type="text/javascript" which throws errors in an HTML validator
		add_theme_support( 'post-formats', array( 'quote', 'image', 'video' ) );

		// Remove filters
		remove_filter ('term_description', 'wpautop'); // Exclude <p> from automatically appearing around category descriptions

		// Remove emoji support
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		// Remove excess markup
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
		remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
		remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
		remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file
		remove_action( 'wp_head', 'index_rel_link' ); // index link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
		remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post
		remove_action( 'wp_head', 'wp_generator' ); // Remove Wordpress version

	}

	function custom_enqueue($hook) {

		remove_action('wp_head', 'wp_print_scripts');
		remove_action('wp_head', 'wp_print_head_scripts', 9);
		remove_action('wp_head', 'wp_enqueue_scripts', 1);

		wp_enqueue_script( 'hjm-slick',
			'/wp-content/themes/hjm/js/slick.min.js',
			array( 'jquery' ),
			'1.8.1',
			true
		);

		$js = get_stylesheet_directory_uri()."/js/hjm.js";
		$jsLastModified = filemtime(get_template_directory()."/js/hjm.js");
		wp_enqueue_script('hjm',
			'/wp-content/themes/hjm/js/hjm.js',
			array( 'jquery', 'hjm-slick' ), // Other scripts that we depend upon
			$jsLastModified,
			true);

		add_action('wp_footer', 'wp_print_scripts', 5);
		add_action('wp_footer', 'wp_enqueue_scripts', 5);
		add_action('wp_footer', 'wp_print_head_scripts', 5);

	}

	function custom_document_title_separator( $title, $sep, $seplocation ) {
	    return str_replace( " $sep ", " | ", $title );
	}

	function wrap_oembed_html($code){
	    if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
	        return '<div class="video-embed">' . $code . '</div>';
	    }
	    return $code;
	}

	function register_custom_menus() {
		register_nav_menus(
			array(
				'ace-header-menu' => __( 'Header Menu' ),
				'ace-footer-menu' => __( 'Footer Menu' )
			)
		);
	}

	function register_custom_widgets() {

		register_sidebar( array (
			'name' => 'Ai-Chat Suggestion Bar',
			'id' => 'chat-suggestion-bar',
			'description' => 'Highlighted area to suggest using the Ai-Chat.',
			'before_widget' => '<section id="chat-suggestion-bar" class="widget">',
			'after_widget' => "</section>",
			'before_title' => '<h4>',
			'after_title' => '</h4>',
		) );

		register_sidebar( array (
			'name' => 'Header Announcement Bar',
			'id' => 'header-announcement-bar',
			'description' => 'Optional site-wide header announcement, above the logo and nav. Title is omitted.',
			'before_widget' => '<section id="header-announcement-bar" class="widget"><div class="gutter">',
			'after_widget' => "</div></section>",
			'before_title' => '<span style="display:none;">',
			'after_title' => '</span>',
		) );

		register_sidebar( array (
			'name' => 'FAQ Description Text',
			'id' => 'faq-description-text',
			'description' => 'Optional introduction on the FAQ archive page. Title is omitted.',
			'before_widget' => '<section id="faq-description-text" class="widget" style="align-items:flex-start;">',
			'after_widget' => "</section>",
			'before_title' => '<span style="display:none;">',
			'after_title' => '</span>',
		) );

	}

	function createPostTypeAndSettings() {

		// FAQ

		$labels = array(
			'name'               => _x( 'FAQs', 'post type general name' ),
			'singular_name'      => _x( 'FAQ', 'post type singular name' ),
			'add_new'            => __( 'Add New' ),
			'add_new_item'       => __( 'Add New FAQ' ),
			'edit_item'          => __( 'Edit FAQ' ),
			'new_item'           => __( 'New FAQ' ),
			'all_items'          => __( 'All FAQs' ),
			'view_item'          => __( 'View FAQ' ),
			'search_items'       => __( 'Search FAQs' ),
			'not_found'          => __( 'No FAQs found' ),
			'not_found_in_trash' => __( 'No FAQs found in the Trash' ),
			'parent_item_colon'  => '',
			'menu_name'          => 'FAQs'
		  );
  
		$args = array(
			'label' => "FAQs",
			'labels' => $labels,
			'description' => "Frequently asked questions.",
			'public' => true,
			'has_archive' => true, // Enables "archive"
			'publicly_queryable' => true, // If false, shows 404 at permalink aka "view" custom post type no longer works; if true, it allows slug to be edited
			'query_var' => true, // Required for permalinks
			'menu_icon' => 'dashicons-editor-help',
			'menu_position' => 20,
			'rewrite' => array( 'slug' => 'faq' ),
			'hierarchical' => false, // If like pages, true; if like posts, false
			'taxonomies' => array('post_tag'), // Other options include 'category' and 'post_tag'
			'capability_type' => 'page', // Should this type behave like post or page?
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
				'custom-fields',
				'page-attributes',
			)
		);

		register_post_type( 'faq', $args );

	}

	// Register Custom Taxonomy
	function createTopicCategoryTaxonomy() {

		$labels = array(
			'name'                       => 'Topic Categories',
			'singular_name'              => 'Topic Category',
			'menu_name'                  => 'Topic Category',
			'all_items'                  => 'All Topic Categories',
			'parent_item'                => 'Parent Topic Category',
			'parent_item_colon'          => 'Parent Topic Category:',
			'new_item_name'              => 'New Topic Category Name',
			'add_new_item'               => 'Add New Topic Category',
			'edit_item'                  => 'Edit Topic Category',
			'update_item'                => 'Update Topic Category',
			'view_item'                  => 'View Topic Category',
			'separate_items_with_commas' => 'Separate topic categories with commas',
			'add_or_remove_items'        => 'Add or remove topic categories',
			'choose_from_most_used'      => 'Choose from the most used',
			'popular_items'              => 'Popular Topic Categories',
			'search_items'               => 'Search Topic Categories',
			'not_found'                  => 'Not Found',
			'items_list'                 => 'Items list',
			'items_list_navigation'      => 'Items list navigation',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'rewrite'					 => array('slug' => 'topic-category'),
			'query_var'             	 => true, // Important to stay true
			'show_ui'                    => true,
			'show_admin_column'          => true, // Sortable column is generally helpful
			'show_in_nav_menus'          => true

		);
		register_taxonomy(
			'topic_category',
			array( 'post_tag' ),
			$args
			);

	}

	function addCustomTaxonomyToMenu() {
		
		$parent_slug = '/edit.php';
		$page_title = "Topic Categories";
		$menu_title = "Topic Categories";
		$capability = 'administrator';
		$menu_slug = '/edit-tags.php?taxonomy=topic_category';
		$function = '';
		$position = '';
		add_submenu_page(
			$parent_slug,
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function,
			$position
		);

	}

	// Modify taxonomy 
	// Part 1 of 2
	// Used for Topics (previously Tags)
	function addTermToCustomTaxonomy(){

		$taxonomy = $_GET['taxonomy']; // post_tag
		$getExistingTopicCategory = get_term_meta($_GET['tag_ID'], '_topic_category', true) ?? "";
		$getExistingTopicDescriptionExpanded = get_term_meta($_GET['tag_ID'], '_topic_description_expanded', true) ?? "";

		$displayTopicCategories = "";
		$getTopicCategories = get_terms( array(
			'taxonomy'   => 'topic_category',
			'hide_empty' => false,
		) );
		if ($getTopicCategories) {
			$displayTopicCategories .= '<select id="topic_category" name="topic_category"><option value="">Select one...</option>';
			foreach ($getTopicCategories as $getTopicCategory) {
				$termID = $getTopicCategory->term_id;
				$termName = $getTopicCategory->name;
				$displayTopicCategories .= '<option value="'.$termID.'"';
				if ($termID == $getExistingTopicCategory) { 
					$displayTopicCategories .= ' selected';
				}
				$displayTopicCategories .= '>'.$termName.'</option>';
			}
			$displayTopicCategories .= '</select>';
		}

    	echo <<<EOD
<tr class="form-field">
	<th scope="row" valign="top"><label for="topic_description_expanded">Description Expanded (Optional)</label></th>
	<td>
	
<textarea id="topic_description_expanded" name="topic_description_expanded" rows="10">$getExistingTopicDescriptionExpanded</textarea>
	
	</td>
</tr>		
<tr class="form-field">
	<th scope="row" valign="top"><label for="topic_category">Topic Category</label></th>
	<td>

$displayTopicCategories

<small><a href="/wp-admin/edit-tags.php?taxonomy=topic_category" target="_blank">See all topic categories</a></small>

	</td>
</tr>
EOD;
	
	}

	// Custom Taxonomy
	// Part 2 of 2
	// Used for Topics (previously Tags)
	function saveTermToCustomTaxonomy() {
		if ( isset( $_POST['topic_description_expanded'] ) ) {
			update_term_meta($_POST['tag_ID'], '_topic_description_expanded', $_POST['topic_description_expanded']);
		}
		if ( isset( $_POST['topic_category'] ) ) {
			update_term_meta($_POST['tag_ID'], '_topic_category', $_POST['topic_category']);
		}
	}

	function removeDateArchives() {
		if ( is_date() ) {
			global $wp_query;
			$wp_query->set_404();
		}
	}

	function remove_category( $string, $type )  {
		if ( $type != 'single' && $type == 'category' && ( strpos( $string, 'category' ) !== false ) )          {
			$url_without_category = str_replace( "/category/", "/", $string );
			return trailingslashit( $url_without_category );
		}
		return $string;
	}

	function getChildPages($parentID) {
		$args = array(
			'post_parent' => $parentID,
			'post_type'   => 'any',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'order' => 'DESC',
			'orderby' => 'menu_order',
		);
		return get_children( $args );
	}

	function addAncestorClassToMenu( $items ) {
		
		$parents = array();
		foreach ( $items as $item ) {
			if ( in_array('current-post-ancestor', $item->classes)  ) {
				$parents[] = $item->menu_item_parent;
			}
		}
	
		foreach ( $items as $item ) {
			if ( in_array( $item->ID, $parents ) ) {
				$item->classes[] = 'current-menu-ancestor'; 
			}
		}
	
		return $items;    
	}

	function customQueryVars( $qvars ) {
		$qvars[] = 'type';
		return $qvars;
	}

	function customTagColumns($columns) {
		$columns['topic_extras'] = 'Extras';
    	return $columns;
	}

	function customTagColumnsContent( $value, $column_name, $tax_id ) {
	    switch ( $column_name ) {
			case 'topic_extras':

				$getTopicCategory = get_term_meta($tax_id, '_topic_category', true) ?? "";

				$topicCategoryName = get_term( $getTopicCategory )->name;

				echo '<a href="/wp-admin/term.php?taxonomy=topic_category&tag_ID='.$getTopicCategory.'&post_type=post&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dtopic_category">'.$topicCategoryName.'</a>';

				echo '<br>Expanded desc.: ';
				$getTopicDescriptionExpanded = get_term_meta($tax_id, '_topic_description_expanded', true) ?? "";
				if ($getTopicDescriptionExpanded <> "") {
					echo '<strong style="color:green">Yes</strong>';
				} else {
					echo '<span style="color:red">No</span>';
				}

				break;
			default :
				break;
	    }
	}

	// Usage: [author id="1"]
	function shortcodeAuthor($atts = [], $content = null, $tag = '') {

		// normalize attribute keys, lowercase
	    $atts = array_change_key_case((array)$atts, CASE_LOWER);

	    // override default attributes with user attributes
	    $wporg_atts = shortcode_atts([
			'id' => '',
			'image' => '',
			'bio' => '',
		], $atts, $tag);

		$id = $wporg_atts['id'];

		if ($id == "1") { return false; } // hjmadmin

		$getAuthorAvatar = $wporg_atts['image'];
		$getAuthorBio = $wporg_atts['bio'];

		$displayAuthorAvatar = "";
		$displayAuthorBio = "";
		$displayAuthorPermalink = "";

		// Author should be in the system
		if ($id <> "") {

			$user = get_userdata( $id );
			if ( $user === false ) { return false; }

			$getAuthorBio = get_the_author_meta("description", $id);
			// $getAuthorName = get_the_author_meta('display_name', $id);
			$getAuthorAvatar = get_avatar( $id, 200 );
			$getAuthorPermalink =  get_author_posts_url( $id );

			$displayAuthorPermalink = '<small class="last" style="margin-top:1rem;"><a href="'.$getAuthorPermalink.'">See All Posts</a></small>';

		// Not in the system, so check for variables via the shortcode
		} else {

			if ($getAuthorAvatar <> "") { $getAuthorAvatar = '<img src="'.$getAuthorAvatar.'" alt="" width="200" style="width:200px; height:auto;">'; }

		}

		if ($getAuthorAvatar <> "") {
			$displayAuthorAvatar = <<<EOD
<div class="column-flex-0" style="width:200px; margin-right:1rem;">
$getAuthorAvatar
</div>
EOD;
		}

		if ($getAuthorBio <> "") {
			$displayAuthorBio = '<p class="last">'.nl2br($getAuthorBio)."</p>";
		}

	    // start output
	    $display = <<<EOD
<div class="row person-wrap" style="align-items: center;">
$displayAuthorAvatar
<div class="column-flex-1">
$displayAuthorBio 
$displayAuthorPermalink
</div>
</div>
EOD;

	    // return output
		return $display;

	}

	function changeTagsToTopics() {
		global $wp_taxonomies;
		$labels = &$wp_taxonomies['post_tag']->labels;
		$labels->name = __('Topics');
		$labels->singular_name = __('Topics');
		$labels->menu_name = "Topics";
		$labels->name_admin_bar = "Topics";
		$labels->add_new_item = __('Add New Topic Name');
		$labels->edit_item = __('Edit Topic');
		$labels->search_items = __('Search Topics');
		$labels->all_items = __('Topics');
		$labels->separate_items_with_commas = __('Separate Topics with commas');
		$labels->choose_from_most_used = __('Choose from the most used Topics');
		$labels->popular_items = __('Popular Topics');
		$labels->view_item = __('View Topic Landing Page');
		$labels->update_item = __('Update Topic Name');
		$labels->new_item_name = __('Your New Topic Name');
		$labels->add_or_remove_items = "Add or Remove Topic";
		$labels->not_found = "Topic Not Found";
		$labels->no_terms = "No Topics";
		$labels->items_list_navigation = "Navigate to the list of topics";
		$labels->items_list = "List of Topics";
		$labels->back_to_items = "Back to Topics";
	}

	public static function countSearchOnMultipleTypesByKeyword($keyword, $tag = "") {

		$results = array();
		$types = array("any","post","faq","visual");
		foreach ($types as $type) {
			
			$categoryID = "";
			$postType = $type; // Works for any, faq
			if ($type == "post") { $postType = array("post", "page"); $categoryID = "-53"; }
			if ($type == "visual") { $postType = "post"; $categoryID = "53"; }

			$searchQueryArgs = array(
				's' => $keyword,
				'post_type' => $postType,
				'cat' => $categoryID
			);
			
			if ($tag <> "") {
				$searchQueryArgs = array(
					's' => $keyword,
					'post_type' => $postType,
					// 'tag_id' => $topic,
					'tag__and' => $tag,
					// tag__and if being more inclusive
					'cat' => $categoryID
				);
			}
			
			$searchQuery = new WP_Query($searchQueryArgs);
			$totalResults = $searchQuery->found_posts; // ." for tag $tag"
			wp_reset_postdata();

			$results[$type] = $totalResults;

		}
		
		return $results;

	}

	public static function displaySearchOnMultipleTypesByKeyword($keyword, $tag = "") {

		$display = "";

		$countSearchOnMultipleTypesByKeyword = HJM::countSearchOnMultipleTypesByKeyword($keyword, $tag);
		if (!$countSearchOnMultipleTypesByKeyword) { return false; }
		
		$countAny = $countSearchOnMultipleTypesByKeyword['any'];
		$countPost = $countSearchOnMultipleTypesByKeyword['post'];
		$countFAQ = $countSearchOnMultipleTypesByKeyword['faq'];
		$countVisual = $countSearchOnMultipleTypesByKeyword['visual'];

		// if (is_array($tag)) { $tag = explode(",", $tag); }
		// if ($topic <> "") { $topic = htmlspecialchars($topic); }

		// Only display if there's more than one and "any" doesn't perfectly match just one other type
		if ($countAny > 1) {
			// && ($countAny <> $countPost && $countAny <> $countVisual && $countAny <> $countFAQ)
			$display = "<small>Filter results: ";
			if ($countAny > 0) { 
				$display .= "<a class=\"chip\" href=\"/?s=$keyword&tag=$tag\">$countAny Any Content Type</a>";
			}
			if ($countPost > 0) { 
				$display .= "<a class=\"chip\" href=\"/?s=$keyword&type=post&tag=$tag\">$countPost Post";
				if ($countPost > 1) { $display .= "s"; }
				$display .= "</a>";
			}
			if ($countVisual > 0) { 
				$display .= "<a class=\"chip\" href=\"/?s=$keyword&type=visual&tag=$tag\">$countVisual Visual";
				if ($countVisual > 1) { $display .= "s"; }
				$display .= "</a>";
			}
			if ($countFAQ > 0) { 
				$display .= "<a class=\"chip\" href=\"/?s=$keyword&type=faq&tag=$tag\">$countFAQ FAQ";
				if ($countFAQ > 1) { $display .= "s"; }
				$display .= "</a>";
			}
			$display .= "</small>";
		}
		
		return $display;

	}

	function customGalleryOutput( $output, $attr) {

		global $post, $wp_locale;
	
		static $instance = 0;
		$instance++;
	
		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}
	
		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $attr));
	
		$id = intval($id);
		if ( 'RAND' == $order )
			$orderby = 'none';
	
		if ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}
	
		if ( empty($attachments) )
			return '';
	
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}
	
		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$columns = intval($columns);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		// $float = is_rtl() ? 'right' : 'left';

		$size = "medium";
	
		$selector = "gallery-{$instance}";
	
		$output = apply_filters('gallery_style', "<div id='$selector' class='carousel galleryid-{$id}'>");
	
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			
			// $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
			// $link = wp_get_attachment_link($id, $size, false, false); // data-lity

			$thumbnailImage = wp_get_attachment_image_url($id, $size);
			$linkedImage = wp_get_attachment_image_url($id, 'full');
			$link = '<a href="'.$linkedImage.'"><img src="'.$thumbnailImage.'"></a>';

			$output .= '<div>';
			$output .= $link;
			/*
			if ( $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<{$captiontag} class='gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			*/
			$output .= "</div>";
			
		}
	
		
		$output .= "</div>\n";
		

		return $output;
	}

	function custom_login_logo() { ?>
		<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url('/mstile-150x150.png');
			height:150px;
			width:150px;
			background-size: 150px 150px;
			background-repeat: no-repeat;
			padding-bottom: 10px;
		}
		.login form{
		box-shadow:none;
		padding:20px;
		}
		#login {
		background: #FFF;
		margin: 50px auto;
		padding: 40px 20px;
		width: 400px;
		}
		.login label {
		color: #555;
		font-size: 14px;
		}
		.login form .forgetmenot{
		float:none;
		}
		#login form p.submit{
		margin-top:15px;
		}
		.login.wp-core-ui .button-primary {
		background: #2FABF9;
		border-color:#2FABF9;
		box-shadow: 0 1px 0 #2FABF9;
		color: #FFF;
		text-shadow: none;
		float: none;
		clear: both;
		display: block;
		width: 100%;
		padding: 7px;
		height: auto;
		font-size: 15px;
		}
		.login #login_error {
			color:red;
			border-left:0;
			box-shadow:none;
			text-align:center;
		}
		</style>
	<?php }

	// Disable support for comments and trackbacks in post types
	function df_disable_comments_post_types_support() {
		$post_types = get_post_types();
		foreach ($post_types as $post_type) {
			if(post_type_supports($post_type, 'comments')) {
				remove_post_type_support($post_type, 'comments');
				remove_post_type_support($post_type, 'trackbacks');
			}
		}
	}

	// Close comments on the front-end
	function df_disable_comments_status() {
		return false;
	}

	// Hide existing comments
	function df_disable_comments_hide_existing_comments($comments) {
		$comments = array();
		return $comments;
	}

	// Remove comments page in menu
	function df_disable_comments_admin_menu() {
		remove_menu_page('edit-comments.php');
	}

	// Redirect any user trying to access comments page
	function df_disable_comments_admin_menu_redirect() {
		global $pagenow;
		if ($pagenow === 'edit-comments.php') {
			wp_redirect(admin_url()); exit;
		}
	}

	// Remove comments metabox from dashboard
	function df_disable_comments_dashboard() {
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	}

	// Remove comments links from admin bar
	function df_disable_comments_admin_bar() {
		if (is_admin_bar_showing()) {
			remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
		}
	}

	// Check if URL is absolute vs. relative
	// Reference: https://stackoverflow.com/a/45186948
	function isAbsoluteUrl($url) {
			$pattern = "/^(?:ftp|https?|feed)?:?\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
			(?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
			(?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
			(?:[\w#!:\.\?\+\|=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";
			return (bool) preg_match($pattern, $url);
	}

}

$hjm = new HJM();

?>
