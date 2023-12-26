<?php

/* Archive used for post formats and custom post types, but not categories */

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

get_header();

?>

<main id="bd">

<article>

<?php

ob_start();
get_template_part( 'template-parts/pagination' );
$displayPagination = ob_get_clean();

$tagID = "";
$archiveCount = 0;
$archiveTitle = post_type_archive_title('', false);
if ( is_tag()) {
	$archiveTitle = single_tag_title( '', false );
	$tagID = get_queried_object()->term_id;
	$archiveCount = get_queried_object()->count;
} else if (get_post_type() == 'faq') {
	// $getID isn't defined so get_post_type($getID) won't work here
	$tagID = get_queried_object()->term_id;
	$archiveCount = wp_count_posts( 'faq' )->publish;
} else if (is_author()) {
	$authorID = get_the_author_meta('ID');
	$fname = get_the_author_meta('first_name');
	$lname = get_the_author_meta('last_name');
	$archiveTitle = trim( "$fname $lname" );
	$archiveCount = count_user_posts($authorID);
}

$footerWidget = "";
if ( is_tag() ) {
	ob_start();
	dynamic_sidebar( 'chat-suggestion-bar' );
	$footerWidget = ob_get_contents();
	ob_end_clean();
}

// $archiveCount = wp_count_posts()->publish;
$displayArchiveCount = number_format($archiveCount)." item";
if ($archiveCount > 1) { $displayArchiveCount .= "s"; }

$breadcrumbTitle = get_the_title(1140);
if (is_singular('faq')) {
	$breadcrumbTitle = 'FAQ';
}

$displayArchiveThumbnail = "";

$archiveLink = "#";
$archiveDescription = "<p>Description goes here.</p>";
if ( is_tag() ) {

	$archiveDescription = "<p>Definition goes here.</p>";

	$permalinkBase = get_option( 'tag_base' );
	$archiveLink = "/$permalinkBase/";
	$tag = get_tag($tagID);
	$tagDescription = "";
	if ($tag) {
		$tagDescription = $tag->description;
	}
	if ($tagDescription <> "") {
		$archiveDescription = "<p>$tagDescription</p>";
	}

	$displayTopicDescriptionExpanded = "";
	$displayTopicDescriptionExpandedButton = "";
	$getTopicDescriptionExpanded = get_term_meta($tagID, '_topic_description_expanded', true) ?? "";
	if ($getTopicDescriptionExpanded <> "") {
		$displayTopicDescriptionExpanded = $getTopicDescriptionExpanded;
		$displayTopicDescriptionExpandedButton = '<a href="#" class="button fwdlabs-toggle" data-toggle="#definition-long">More</a>';
	}

	$archiveDescription = <<<EOD
<small>Short Definition</small>
$archiveDescription
<small>$displayTopicDescriptionExpandedButton</small>
<div id="definition-long" style="display:none;">
<small>$displayTopicDescriptionExpanded</small>
</div>
EOD;

} else if (is_archive('faq')) {

	$archiveDescription = "<p>Your frequently asked questions, answered.</p>";
	ob_start();
	dynamic_sidebar( 'faq-description-text' );
	$faqText = ob_get_contents();
	ob_end_clean();
	if ($faqText <> "" ) { $archiveDescription = $faqText; }

} else if (is_singular('faq')) {

	$permalinkBase = 'faq';
	$archiveLink = "/$permalinkBase/";
	$archiveDescription = "<p>Frequently asked questions.</p>";

} else if (is_author()) {

	$getAuthorBio = nl2br(get_the_author_meta("description", $authorID));
	if ($getAuthorBio <> "") {
		$archiveDescription = "<p>$getAuthorBio</p>";
	}

	$getAuthorAvatar = get_avatar( $authorID, 200 );
	if ($getAuthorAvatar) {
		$displayArchiveThumbnail = '<div class="column-flex-0 person-wrap" style="margin-right:1rem;">'.$getAuthorAvatar.'</div>';
	}

}

$displayArchiveDescription = <<<EOD
<div class="row custom-excerpt" style="align-items:center;">
$displayArchiveThumbnail
<div class="column-flex-1">
$archiveDescription
</div>
</div>
EOD;

// $displayBreadcrumb = '<p><a href="'.$archiveLink.'">'.$breadcrumbTitle.'</a></p>';

ob_start();
get_template_part( 'template-parts/breadcrumb' );
$displayBreadcrumbs = ob_get_clean();

$displayArchiveTitle = "<h1 style=\"margin-top:1rem;\">$archiveTitle</h1>";
if ($displayBreadcrumbs == "") { $displayArchiveTitle = "<h1>$archiveTitle</h1>"; }

echo <<<EOD
<section class="primary">
<div class="gutter">
$displayBreadcrumbs
$displayArchiveTitle
$displayArchiveDescription
<small class="last">$displayArchiveCount</small>
</div>
</section>
<section>
<div class="gutter">
EOD;

if ($paged > 1) {
	echo $displayPagination;
}

if (have_posts()) : while (have_posts()) : the_post();

	get_template_part( 'template-parts/title-excerpt-tag' );

endwhile;

echo $displayPagination;

echo $footerWidget;

else:

	echo "<!-- else -->";

endif; ?>

</div>
</section>
</article>

</main><!-- /#bd -->

<?php get_footer(); ?>