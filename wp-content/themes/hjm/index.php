<?php

get_header();

echo '<main id="bd">';

if (have_posts()) : while (have_posts()) : the_post();

	$getID = get_the_ID();

	$contentTitle = get_the_title();

	$contentRaw = get_the_content();
	$content = apply_filters('the_content', $contentRaw);

	$getPost = get_post($getID);
	$postParent = $getPost->post_parent;
	$postSlug = $getPost->post_name;

	$pageExcerptRaw = get_the_excerpt();
	$pageExcerpt = apply_filters('the_content', $pageExcerptRaw);

	$displayPostThumbnail = "";
	$getPostThumbnail = get_the_post_thumbnail_url($getID, "full");
	if ($getPostThumbnail) {
		$displayPostThumbnail = '<p><img src="'.$getPostThumbnail.'" style="width:100%; height:auto;"></p>';
	}

	$displayContentTitle = "<h1>$contentTitle</h1>";

echo '<article>';

$edit = get_edit_post_link($getID);
if ($edit) {
	$content = '<p><a href="'.$edit.'">Edit</a></p>'.$content;
}

$getPageTitle = get_the_title();
if ($getPageTitle <> "") {
	$displayPageTitle = "<h1>$getPageTitle</h1>";
}

if (has_excerpt()) {
	$displayPageTitle .= '<div class="custom-excerpt">'.$pageExcerpt.'</div>';
}

ob_start();
get_template_part( 'template-parts/breadcrumb' );
$displayBreadcrumbs = ob_get_clean();

// Echo HTML
echo <<<EOD
<section>
<div class="gutter">

$displayBreadcrumbs

$displayPostThumbnail

$displayPageTitle

$content

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