<?php

get_header();

ob_start();
get_template_part( 'template-parts/pagination-adjacent' );
$displayPaginationAdjacent = ob_get_clean();

$tagBase = get_option( 'tag_base' );

?>

<main id="bd">

<?php

if (have_posts()) : while (have_posts()) : the_post();

	$getID = get_the_ID();
	$authorID = get_the_author_meta( 'ID' );

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

	$displayContentTitle = "";
	if (!is_front_page()) {
		$displayContentTitle = "<h1>$contentTitle</h1>";
	}

?>

<article>

<?php

$edit = get_edit_post_link($getID);
if ($edit) {
	$content = '<p><a href="'.$edit.'">Edit</a></p>'.$content;
}

$getPageTitle = get_the_title();
if ($getPageTitle <> "") {
	$displayPageTitle = "<h1>$getPageTitle</h1>";
}
if (is_front_page()) { $displayPageTitle = ""; }

if (has_excerpt()) {
	$displayPageTitle .= '<div class="custom-excerpt">'.$pageExcerpt.'</div>';
}

$getAllTopicCategories = array();
$displayGlossaryTerms = "";
// if (is_single()) {
	$tags = get_the_tags();
	if ($tags) {
		
		$displayGlossaryTerms = "<p>Topics: ";
		foreach($tags as $tag) {
			
			$tagID = $tag->term_id;
			$tagName = $tag->name;
			$tagSlug = $tag->slug;

			$getTopicCategoryName = "";
			$getTopicCategory = get_term_meta($tagID, '_topic_category', true) ?? "";
			if ($getTopicCategory) {
				$getTopicCategoryName = get_term( $getTopicCategory )->name;
				$getAllTopicCategories[] = $getTopicCategory;
			}
			
			$displayGlossaryTerms .= '<a href="/'.$tagBase.'/'.$tagSlug.'/" class="chip" title="Topic within '.$getTopicCategoryName.'">'.$tagName.'</a> ';

		}
		$displayGlossaryTerms .= "</p>";
	}
// }

$displayPostDate = "";
// if (is_single()) {

	if ( get_post_type( $getID ) == 'post' ) {
		$displayPostDate = '<p>'.get_the_date( 'F j, Y' ).'</p>';
	}
// }

ob_start();
get_template_part( 'template-parts/breadcrumb' );
$displayBreadcrumbs = ob_get_clean();

$displayAuthor = "";
$getAuthor = do_shortcode('[author id="'.$authorID.'"]');
if ($getAuthor) {
	$getAuthorName = get_the_author_meta('display_name', $authorID);
	$displayAuthor = <<<EOD
<div id="commentator">
<p><strong>About the Commentator, $getAuthorName</strong></p>
$getAuthor
</div>
EOD;
}

/*
$displayAuthorAvatar = "";
$getAuthorBio = nl2br(get_the_author_meta("description", $authorID));
if ($getAuthorBio) {
	$getAuthorName = get_the_author_meta('display_name', $authorID);
	$getAuthorAvatar = get_avatar( $authorID, 200 );
	if ($getAuthorAvatar) {
		$displayAuthorAvatar = '<div class="column-flex-0" style="width:200px; margin-right:1rem;">'.$getAuthorAvatar.'</div>';
	}
	$getAuthorPermalink =  get_author_posts_url( $authorID );
	$displayAuthor = <<<EOD
<div id="commentator"><blockquote>
<p><strong>About the Commentator, $getAuthorName</strong></p>
<div class="row" style="align-items: center;">
$displayAuthorAvatar
<div class="column-flex-1"><p>$getAuthorBio</p><p class="last"><a href="$getAuthorPermalink">See All Posts</a></p></div>
</div>
</blockquote></div>
EOD;
}
*/

ob_start();
get_template_part( 'template-parts/pageviews' );
$displayPageviews = ob_get_clean();

ob_start();
get_template_part( 'template-parts/related-content' );
$displayRelatedContent = ob_get_clean();

// Echo HTML
echo <<<EOD
<section class="primary">
<div class="gutter">
$displayBreadcrumbs
</div>
</section>
<section>
<div class="gutter">

$displayPostThumbnail

$displayPageTitle

$displayPostDate

$displayGlossaryTerms

$content

$displayAuthor

$displayPaginationAdjacent

$displayPageviews

</div>
</section>

$displayRelatedContent

</article>
EOD;

endwhile;

else:

	echo "<!-- else -->";

endif; ?>

</main><!-- /#bd -->

<?php get_footer(); ?>