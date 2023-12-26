<?php

$getAllTopicCategories = array();
$displayGlossaryTerms = "";

$tagBase = get_option( 'tag_base' );

$categoryID = "";
$getAllCategories = get_the_category();
if ($getAllCategories) {
	$categoryID = $getAllCategories[0]->cat_ID;
}

$tags = get_the_tags();
if ($tags) {
	foreach($tags as $tag) {		
		$tagID = $tag->term_id;
		$getTopicCategory = get_term_meta($tagID, '_topic_category', true) ?? "";
		if ($getTopicCategory) {
			$getAllTopicCategories[] = $getTopicCategory;
		}
	}
}

$typeLabel = "Posts";
if (is_singular('faq')) { $typeLabel = "FAQs"; } else if (in_category('visualizations')) { $typeLabel = "Visualizations"; }

$displaySameTopicContent = "";

// If a post or a visualization, look through a query
if ($categoryID <> "") {
	$queryLatestPostsArgs = array(
		'cat' => $categoryID, // Unique to non-FAQs
		'fields' => 'ids',
		'posts_per_page' => 3,
		'post__not_in' => array(get_the_ID()),
	);
	$queryLatestPosts = new WP_Query( $queryLatestPostsArgs );
	$getLatestPostIDsByCategoryIDs = $queryLatestPosts->posts;
	if ($getLatestPostIDsByCategoryIDs) {
		$displaySameTopicContent = "<h3>Recent and Related $typeLabel</h3>"; // Same topic(s)
		foreach ($getLatestPostIDsByCategoryIDs as $getLatestPostID) {
			$getLatestPostTitle = get_the_title($getLatestPostID);
			$getLatestPostPermaklink = get_permalink($getLatestPostID);
			$displaySameTopicContent .= "<p><a href=\"$getLatestPostPermaklink\">$getLatestPostTitle</a></p>";
		}
	}
} else if (is_singular('faq')) {
	$queryLatestPostsArgs = array(
		'post_type' => 'faq', // Unique to FAQs
		'fields' => 'ids',
		'posts_per_page' => 3,
		'post__not_in' => array(get_the_ID()),
	);
	$queryLatestPosts = new WP_Query( $queryLatestPostsArgs );
	$getLatestPostIDsByCategoryIDs = $queryLatestPosts->posts;
	if ($getLatestPostIDsByCategoryIDs) {
		$displaySameTopicContent = "<h2>Recent and Related $typeLabel</h2>"; // Same topic(s)
		foreach ($getLatestPostIDsByCategoryIDs as $getLatestPostID) {
			$getLatestPostTitle = get_the_title($getLatestPostID);
			$getLatestPostPermaklink = get_permalink($getLatestPostID);
			$displaySameTopicContent .= "<p><a href=\"$getLatestPostPermaklink\">$getLatestPostTitle</a></p>";
		}
	}
}

$getUniqueTopicCategories = array_unique($getAllTopicCategories);

$getAllTopicIDs = array();
$displayRelatedTopicCategoryContent = "";
foreach ($getUniqueTopicCategories as $getTopicCategory) {

	$getTopicCategoryName = get_term( $getTopicCategory )->name;
	
	// Better to wait until more content is tagged to dive into this
	// $displayRelatedTopicCategoryContent .= "<p><strong>Recent $typeLabel Related to $getTopicCategoryName</strong></p><p>TBD?</p>";

	$getAllTopicIDs[] = $getTopicCategory;

}

$getUniqueTopicIDs = array_unique($getAllTopicIDs);

$displayRelatedTopicsByCategory = "";
if (count($getUniqueTopicIDs) > 0) {
	$displayRelatedTopicsByCategory = "<h2>All Related Topics</h2><p>";
	foreach ($getUniqueTopicIDs as $getTopicID) {
	$tags = get_tags(array("meta_key" => "_topic_category", "meta_value" => $getTopicID));
		foreach($tags as $tag) {

			$getTagID = $tag->term_id;
			$getTagSlug = $tag->slug;
			$getTagName = $tag->name;
			$getTagDescription = $tag->description;
			$getTagCount = $tag->count;
			
			$displayRelatedTopicsByCategory .= '<a href="/'.$tagBase.'/'.$getTagSlug.'/" class="chip" title="Topic within '.$getTopicCategoryName.'">'.$getTagName.'</a> ';
			
		}
	}
	$displayRelatedTopicsByCategory .= "</p>";
}

echo <<<EOD
<section class="secondary">
	<div class="gutter">

<p>You might also be interested in...</p>

$displaySameTopicContent

$displayRelatedTopicCategoryContent

$displayRelatedTopicsByCategory

	</div>
</section>
EOD;

?>