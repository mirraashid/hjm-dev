<?php

$siteName = get_option('blogname');
$siteDescription = "";

$css = get_stylesheet_directory_uri()."/style.css";
$cssLastModified = filemtime(get_template_directory()."/style.css");
$css .= "?$cssLastModified";

$displayLogo = '<img class="logo" src="/wp-content/themes/hjm/images/logo.png" alt="'.$siteName.'">';
if (!is_front_page()) { 
    $displayLogo = '<a href="/"><img class="logo" src="/wp-content/themes/hjm/images/logo.png" alt="'.$siteName.'"></a>';
}

?><!doctype html>
<html class="no-js" lang="en">
<head prefix=
"og: http://ogp.me/ns#
fb: http://ogp.me/ns/fb#
product: http://ogp.me/ns/product#">
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<title><?php bloginfo('name'); wp_title(' - '); ?></title>

<meta name="description" content="<?=$siteDescription;?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//www.youtube.com">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link rel="stylesheet" href="<?=$css;?>">

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ec1e24">
<meta name="msapplication-TileColor" content="#ec1e24">
<meta name="theme-color" content="#ec1e24">

<?php

$metaURL = get_site_url(); // Canonical permalink
$metaTitle = $siteName;
$metaDescription = $siteDescription;
$metaType = "website";
$metaSite = $siteName;
$metaFBAdmin = "";
$metaFBAppID = "160483940678081"; // FWDLABS
$metaImage = "/wp-content/themes/hjm/images/logo.png";
$metaTwitter = "";
$metaTwitterCard = "summary";

$displayFBVideo = "";
$displayFBAdmin = "";
$displayFBApp = '<meta property="fb:app_id" content="'.$metaFBAppID.'">';
$displayTwitterVideo = "";

if (is_single() || is_page()) {

	$postObject = $wp_query->get_queried_object();
	$postID = $postObject->ID;
	$postTitle = $postObject->post_title;
	$postPermalink = get_permalink($postID);
	$postExcerpt = $postObject->post_excerpt;
	$postThumbnail = get_the_post_thumbnail_url($postID, "full");
	
	if ($postTitle <> "" && !is_front_page()) {
		$metaTitle = htmlspecialchars($postTitle)." - ".$metaSite;
	}
	if ($postPermalink <> "") {
		$metaURL = $postPermalink;
	}
	if ($postExcerpt <> "") {
		$metaDescription = htmlspecialchars(trim(strip_tags($postExcerpt)));
	}
	if ($postThumbnail <> "") {
		$metaImage = $postThumbnail;
	}
} else if (is_category()) {
	
	$category = get_the_category();
	$categoryDescription = $category[0]->category_description;
	if ($categoryDescription <> "") {
	    $metaDescription = htmlspecialchars($categoryDescription);
    }
}

$displayHeaderWidget = "";
ob_start();
dynamic_sidebar( 'header-announcement-bar' );
$headerWidget = ob_get_contents();
ob_end_clean();
if ($headerWidget <> "" ) { $displayHeaderWidget = $headerWidget; }

echo <<<EOD
<!-- meta -->
<meta name="description" content="$metaDescription">
<meta itemprop="name" content="$metaTitle">
<meta itemprop="description" content="$metaDescription">
<meta itemprop="image" content="$metaImage">		
<link rel="canonical" href="$metaURL">
		
<!-- open graph -->
<meta property="og:url" content="$metaURL">
<meta property="og:title" content="$metaTitle">
<meta property="og:description" content="$metaDescription">
<meta property="og:type" content="$metaType">
<meta property="og:site_name" content="$metaSite">
<meta property="og:image" content="$metaImage">
$displayFBVideo
$displayFBAdmin
$displayFBApp

<!-- twitter card -->
<meta name="twitter:url" content="$metaURL">
<meta name="twitter:title" content="$metaTitle">
<meta name="twitter:description" content="$metaDescription">
<meta name="twitter:card" content="$metaTwitterCard">
<meta name="twitter:site" content="$metaTwitter">
<meta name="twitter:image" content="$metaImage">
$displayTwitterVideo
EOD;

?>

<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<?php echo $displayHeaderWidget; ?>

<div id="doc" class="container">

<div id="mobile-menu">

	<div style="display:flex; flex-direction:column; justify-content:space-between; height: 100%;"><div style="flex:0;">

		<div class="gutter" style="margin-top:1rem;">
		<div class="row row-on-mobile" style="align-items: center;">
		<div class="column-flex-0">
<?php echo $displayLogo; ?>
		</div>
		<div class="column-flex-1" style="text-align:right;">
<a href="#" class="fwdlabs-toggle" data-toggle="#mobile-menu"><img src="/wp-content/themes/hjm/images/close.svg" alt="Close" height="24" width="24" style="margin:0 0.5rem 1rem; line-height: 1.5;"></a>
<!--<p style="font-size: 1rem; font-weight: 300; line-height: 1.5;">X</p>-->
		</div>
		</div>
		</div>

	</div>
	<div style="flex:1; display: flex; align-items: center; justify-content: center; flex-direction: column;">

		<div class="row" style="flex-direction:column; align-items: center;">	
<?php
$mobileMenuItems = wp_get_nav_menu_items( 'Primary Nav' );
foreach ( $mobileMenuItems as $menuItem ) {
	$menuItemTitle = $menuItem->title;
	$menuItemPermalink = $menuItem->url;
	// echo print_r($menuItem, true);
	echo '<div class="flex-column-1"><a href="'.$menuItemPermalink.'" class="h2">'.$menuItemTitle.'</a></div>';
}
?>
		
		</div>

	</div></div>

</div>

<header id="hd"><div class="gutter">

<div class="row row-on-mobile">
<div class="column-flex-0">
<?php

echo $displayLogo;

?>
</div>
<div class="column-flex-1" style="display: flex; align-items: flex-end; justify-content: center; flex-direction:column;">
<?php

$mobileMenu = "";
if (!is_page('search') && !is_page(1140) && !is_page(1254) && !is_tag()) {
	$mobileMenu .= '<li class="mobile-only"><a href="#" class="fwdlabs-toggle" data-toggle="#search-bar"><img src="/wp-content/themes/hjm/images/search.svg" alt="Search" height="24" width="24"></a></li>';
}
$mobileMenu .= '<li class="mobile-only"><a href="#" class="fwdlabs-toggle" data-toggle="#mobile-menu"><img src="/wp-content/themes/hjm/images/menu.svg" alt="Menu" height="24" width="24"></a></li>';

wp_nav_menu( array(
	'menu' => 'Primary Nav',
    'container' => false,
	'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s'.$mobileMenu.'</ul>'
) );

?>

<?php if (!is_page('search') && (is_page(1140) || is_page(1254) || is_tag())) { ?>
	<small class="last"><a href="/search/">Advanced Search</a></small>
<?php } ?>

</div>
</div>

<?php if (!is_page('search') && !is_page(1140) && !is_page(1254) && !is_tag()) { ?>
<div id="search-bar">
<?php
if (!is_page('search')) {
	get_search_form();
}
?>
</div>
<?php } ?>

</div></header><!-- /#hd -->