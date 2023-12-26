<?php

get_header();

echo '<main id="bd">';

?>
<div class="home-main-heading">
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
<?php

if (have_posts()) : while (have_posts()) : the_post();

	$getID = get_the_ID();

	$contentTitle = get_the_title();

	$contentRaw = get_the_content();
	$content = apply_filters('the_content', $contentRaw);

	$getPost = get_post($getID);
	$postParent = $getPost->post_parent;
	$postSlug = $getPost->post_name;

	$pageExcerptRaw = get_the_excerpt();

	$displayContentTitle = "";

// Latest posts

$displayLatestPosts = "<ul>";
$getLatestPostsArgs = array(
    'posts_per_page' => 5,
    'cat' => 1,
);
$getLatestPosts = new WP_Query( $getLatestPostsArgs);
if ( $getLatestPosts->have_posts() ) {
	$i = 0;
    while ( $getLatestPosts->have_posts() ) {
    	$getLatestPosts->the_post();        
        $getLatestPostTitle = get_the_title();
		$getLatestPostPermalink = get_permalink();
		$getLatestPostExcerpt = get_the_excerpt();
		$getLatestPostDate = get_the_date( 'F j, Y' );
		$displayLatestPosts .= "<li><a href=\"$getLatestPostPermalink\">";
		if ($i == 0) { $displayLatestPosts .= "<span class=\"h3 last\">"; }
		$displayLatestPosts .= $getLatestPostTitle;
		if ($i == 0) { $displayLatestPosts .= "</span>"; }
		$displayLatestPosts .= "<span class=\"date\">$getLatestPostDate</span>";
		if ($i == 0) { $displayLatestPosts .= "<span>$getLatestPostExcerpt</span>"; }
		$displayLatestPosts .= "</a></li>";
		$i++;
    }
    wp_reset_postdata();
}
$displayLatestPosts .= "</ul><small><a href=\"/posts/\">All Posts</a></small>";

// Latest visualizations

$displayLatestVisualizations = '<div class="carousel">';
$getLatestPostsArgs = array(
    'posts_per_page' => 5,
    'cat' => 53,
	// 'meta_key' => 'views',
    // 'orderby' => 'meta_value_num',
    // 'order' => 'DESC'
);
$getLatestPosts = new WP_Query( $getLatestPostsArgs);
if ( $getLatestPosts->have_posts() ) {
    while ( $getLatestPosts->have_posts() ) {
    	$getLatestPosts->the_post();
		$getLatestPostID = get_the_ID();      
        $getLatestPostTitle = get_the_title();
		$getLatestPostPermalink = get_permalink();
		$getLatestPostExcerpt = get_the_excerpt();
		$displayThisPostThumbnail = "";
		$getThisPostThumbnail = get_the_post_thumbnail_url($getLatestPostID, "medium");
		if ($getThisPostThumbnail) {
			$displayThisPostThumbnail = '<img src="'.$getThisPostThumbnail.'" style="width:100%; height:auto; margin-bottom:1rem;">';
		}
		$displayLatestVisualizations .= "<div><a href=\"$getLatestPostPermalink\">$displayThisPostThumbnail $getLatestPostTitle<span>$getLatestPostExcerpt</span></a></div>";
    }
    wp_reset_postdata();
}
$displayLatestVisualizations .= "</div><small><a href=\"/visuals/\">All Visuals</a></small>";

// Popular FAQs

$displayPopularFAQs = "";
$getLatestPostsArgs = array(
	'post_type' => 'faq',
    'posts_per_page' => 1,
	'meta_key' => 'views',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
);
$getLatestPosts = new WP_Query( $getLatestPostsArgs);
if ( $getLatestPosts->have_posts() ) {
    while ( $getLatestPosts->have_posts() ) {
    	$getLatestPosts->the_post();        
        $getLatestPostTitle = get_the_title();
		$getLatestPostPermalink = get_permalink();
		$getLatestPostExcerpt = get_the_excerpt();
		$displayPopularFAQs .= "<p><a href=\"$getLatestPostPermalink\"><span class=\"h3 last\">$getLatestPostTitle</span><span>$getLatestPostExcerpt</span></a></p>";
    }
    wp_reset_postdata();
}
$displayPopularFAQs .= "<small class=\"last\"><a href=\"/faq/\">All FAQs</a></small>";

// Featured Posts

$getFeaturedPosts = get_option( 'sticky_posts' );
$displayFeaturedPost = "";
if ($getFeaturedPosts) {
	$displayFeaturedPost = <<<EOD
<section class="primary">
<div class="gutter">
<p style="color:#ccc;"><strong>Featured Post</strong></p>
EOD;
	$getFeaturedPost = $getFeaturedPosts[0]; // Just get the first match
	$getStickyPostTitle = get_the_title($getFeaturedPost);
	$getStickyPostPermalink = get_permalink($getFeaturedPost);
	$getStickyPostExcerpt = get_the_excerpt($getFeaturedPost);
	$displayFeaturedPost .= <<<EOD
	<a href="$getStickyPostPermalink"><span class="h3 last">$getStickyPostTitle</span><span class="p">$getStickyPostExcerpt</span></a>
</div>
</section>
EOD;
}

// Featured Topics

$displayFeaturedTopics = "";
$getTags = get_tags(array(
	'hide_empty' => false
));
if ($getTags) {
	shuffle($getTags);
	$countTags = 0;
	$displayFeaturedTopics .= '<div class="carousel-multiple">';
	foreach ($getTags as $getTag) {
		$getTagTitle = $getTag->name;
		$getTagSlug = $getTag->slug;
		$getTagDescription = $getTag->description;
		if ($countTags < 10) {
			if ($getTagDescription <> "") {
				$displayFeaturedTopics .= '<div class="gutter"><div class="chip" style="text-align:center; display:flex; align-items:center; justify-content:center;"><a href="/topic/'.$getTagSlug.'/" style="color:#000"><span class="h3 last">'.$getTagTitle.'</span></a></div></div>';
			} else {
				$displayFeaturedTopics .= '<div class="gutter"><div class="chip" style="text-align:center; display:flex; align-items:center; justify-content:center;"><a href="/topic/'.$getTagSlug.'/" style="color:#000"><span class="h3 last">'.$getTagTitle.'</span></a></div></div>';
			}
		}
		$countTags++;
	}
	$displayFeaturedTopics .= '</div>';
}

// Ai-Chat
$chatTitle = get_the_title(1254);
$chatExcerpt = get_the_excerpt(1254);
$chatExcerpt = apply_filters('the_content', $chatExcerpt);
$chatPermalink = get_permalink(1254);

echo '<article>';

$displayPageTitle = "";

if (has_excerpt()) {
	$displayPageTitle .= '<h1 style="text-align:center;">'.$pageExcerptRaw.'</h1>';
} else {
	$siteDescription = get_bloginfo( 'description' );
	$displayPageTitle .= '<h1 style="text-align:center;">'.$siteDescription.'</h1>';
}

$displayPostThumbnail = "/wp-content/uploads/1000_F_592567735_NIQZOtZAkIX8X5aKHEnjCSA1ZDzb4jQS.jpeg";

$displayPostThumbnail = "";
$getPostThumbnail = get_the_post_thumbnail_url($getID, "full");
if ($getPostThumbnail) {
	$displayPostThumbnail = 'background-image: url('.$getPostThumbnail.'); background-position:center bottom; ';
}

// HTML
echo <<<EOD
<section id="hero" style="height: 48vh; min-height:640px; background-size: cover; justify-content: flex-end; $displayPostThumbnail">
<div class="gutter" style="background: rgba(255,255,255,0.85); border-bottom:2px solid #ccc;">

$displayPageTitle

$content

</div>
</section>

$displayFeaturedPost

<section id="latest-posts-and-visualizations" class="row" style="align-items: flex-start; background:#fff;">
<div class="column-flex-1"><div class="gutter">
<p><strong>Latest Posts</strong></p>
$displayLatestPosts
</div></div>
<div class="column-flex-1"><div class="gutter">
<p><strong>Latest Visuals</strong></p>
$displayLatestVisualizations
</div></div>
</section>

<section id="featured-topics" class="primary" style="background-color:#333; text-align:center; display:none;">
<div class="gutter">
<p style="color:#ccc;"><strong>Featured Topics</strong></p>
$displayFeaturedTopics
<small class="last"><a href="/topic/">All Topics</a></small>
</div>
</section>

<section class="secondary">
<div class="gutter">
<p><strong>Popular FAQ</strong></p>
$displayPopularFAQs
</div>
</section>

<section class="tertiary" style="text-align:center;">
<div class="gutter">
<p><strong>$chatTitle</strong></p>
$chatExcerpt
<p class="last"><a href="$chatPermalink" class="button">Start Chat</a></p>
</div>
</section>

EOD;

echo '</article>';

endwhile;

else:

	echo "<!-- else -->";

endif;

echo '</main><!-- /#bd -->';

get_footer();

?>