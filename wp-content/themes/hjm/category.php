<?php

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

get_header();

$getTheCategory = get_the_category();
$catID = $getTheCategory[0]->term_id;
$categoryCount = $getTheCategory[0]->category_count;

?>

<main id="bd">

<article>

<?php

$categoryTitle = single_cat_title("", false);

$displayCategoryDescription = "";
$categoryDescription = category_description( $catID );
if ($categoryDescription <> "") {
	$displayCategoryDescription = "<div class=\"custom-excerpt\"><p>$categoryDescription</p></div>";
} else {
	$displayCategoryDescription = "<div class=\"custom-excerpt\"><p>Description goes here.</p></div>";
}

$displayCategoryCount = "";
// $category = get_category($catID);
if (is_numeric($categoryCount)) {
	$displayCategoryCount = $categoryCount." ";
}
if ($catID == 53) { $displayCategoryCount .= "visualization"; } else { $displayCategoryCount .= "post"; }
if ($categoryCount > 1) { $displayCategoryCount .= "s"; }
$displayCategoryCount .= " in this category";

echo <<<EOD
<section class="primary">
<div class="gutter">
<h1>$categoryTitle</h1>
$displayCategoryDescription
<small class="last">$displayCategoryCount</small>
</div>
</section>
<section>
<div class="gutter">
EOD;

if ($paged > 1) {
	the_posts_pagination( array(
		'mid_size'  => 2,
		'prev_text' => __( 'Newer', 'textdomain' ),
		'next_text' => __( 'Older', 'textdomain' ),
	) );
}

if (have_posts()) : while (have_posts()) : the_post();

	get_template_part( 'template-parts/title-excerpt-tag' );

/*
$getID = get_the_ID();

	$contentTitle = get_the_title();

	$contentPermalink = get_permalink();

	$contentRaw = get_the_content();
	$content = apply_filters('the_content', $contentRaw);

	$getPost = get_post($getID);
	$postParent = $getPost->post_parent;
	$postSlug = $getPost->post_name;

	$pageExcerptRaw = get_the_excerpt();
	$pageExcerpt = apply_filters('the_content', $pageExcerptRaw);

if ($content <> "") {
echo <<<EOD

<p><a href="$contentPermalink" style="display:block;">
$contentTitle
<span style="font-weight:normal; display:block;">$pageExcerptRaw</span>
</a></p>

EOD;
}

*/

endwhile;

echo "<br><br>";

the_posts_pagination( array(
	'mid_size'  => 2,
	'prev_text' => __( 'Newer', 'textdomain' ),
	'next_text' => __( 'Older', 'textdomain' ),
) );

else:

	echo "<!-- else -->";

endif; ?>

</div>
</section>
</article>

</main><!-- /#bd -->

<?php get_footer(); ?>