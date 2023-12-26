<?php

$siteName = get_option('blogname');
$siteDescription = "";

$css = get_stylesheet_directory_uri()."/style.css";
$cssLastModified = filemtime(get_template_directory()."/style.css");
$css .= "?$cssLastModified";

$css1 = get_stylesheet_directory_uri()."/style1.css";
$cssLastModified1 = filemtime(get_template_directory()."/style1.css");
$css1 .= "?$cssLastModified1";

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
<link rel="stylesheet" href="<?php echo $css1; ?>">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">


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

<?php //echo $displayHeaderWidget; ?>
<header>
	<div class="flex-container">
		<a href="<?php echo get_bloginfo('url'); ?>">
			<img src="<?php echo get_bloginfo('template_url'); ?>/images/logo.png" alt="HJM - Health Justice Monitor" id="logo">
		</a>
		<div>
			<nav>
				<?php
				wp_nav_menu(array(
					'menu' => 'Primary Nav',
					'container' => false,
				));
				?>
				<!-- <ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Topics</a></li>
					<li><a href="#">Ai-Chat</a></li>
					<li><a href="#">Posts</a></li>
					<li><a href="#">FAQs</a></li>
					<li><a href="#">Visuals</a></li>
					<li><a href="#">Subscribe</a></li>
				</ul> -->
			</nav>
			<div id="search-conainer">
				<input class="search-input" type="text" placeholder="Find HJM content...">
				<input class="search-input" type="text" placeholder="Any content type">
				<button class="button-primary">Search</button>
			</div>
		</div>
	</div>
	<div style="text-align: center;">
		<h1>Demystifying Single Payer</h1>
		<p>Resources for understanding US health care and how to fix it.</p>
		<div class="hero-bubble">
			<div class="clickable-bubbles">What is Single Payer?</div>
		</div>
		<div class="hero-bubble">
			<div class="clickable-bubbles">Senate Bills</div>
		</div>
		<div class="hero-bubble">
			<div class="clickable-bubbles">Public Opinion</div>
		</div>
		<div class="hero-bubble">
			<div class="clickable-bubbles">2022 Annual Review</div>
		</div>
		<div class="hero-bubble">
			<div class="clickable-bubbles">Physician Support</div>
		</div>
	</div>
</header>