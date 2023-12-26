<?php

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$tag = ( get_query_var( 'tag' ) ) ? get_query_var( 'tag' ) : ''; // Example: 1 or 1,2,3
$type = ( get_query_var( 'type' ) ) ? get_query_var( 'type' ) : ''; // Non-standard variable, so manually set in functions.php

get_header();

?>

<main id="bd">

<article>

<?php

$getSearchQuery = get_search_query();

ob_start();
get_template_part( 'template-parts/pagination' );
$displayPagination = ob_get_clean();

echo <<<EOD
<section>
<div class="gutter">
<h1>Search</h1>
EOD;

if ($paged > 1) {
	echo $displayPagination;
}

$categoryID = "";

$searchQueryType = "any";
if ($type == "post") { $searchQueryType = array("post", "page"); $categoryID = -53; }
if ($type == "faq") { $searchQueryType = "faq"; }
if ($type == "visual") { $searchQueryType = "post"; $categoryID = 53; }

$searchQueryArgs = array(
	's' => $getSearchQuery,
	'post_type' => $searchQueryType,
	'paged' => $paged,
	'cat' => $categoryID
);
if ($tag <> "") {
	$searchQueryArgs = array(
		's' => $getSearchQuery,
		'post_type' => $searchQueryType,
		// 'tag_id' => $tag,
		'tag__and' => $tag,
		'paged' => $paged,
		'cat' => $categoryID
	);
}
$searchQuery = new WP_Query($searchQueryArgs);

$totalResults = $searchQuery->found_posts;

$displayTagFilter = "";
if ($tag <> "") {
	$tags = explode(",", $tag);
	$displayTagFilter = "filtered by topic";
	if (count($tags) > 1) { $displayTagFilter .= "s"; }
	foreach ($tags as $individualTag) {
		$getTag = get_tag($individualTag);
		$removeThisFilterPermalink = "/?s=$getSearchQuery&topic=";
		
		$tagsToPullFrom = $tags;
		if (($key = array_search($individualTag, $tagsToPullFrom)) !== false) {
			unset($tagsToPullFrom[$key]); // Remove this one
		}
		$tagsToPullFromAsString = implode(",", $tagsToPullFrom);
		$removeThisFilterPermalink .= $tagsToPullFromAsString;
		
		if ($type <> "") {
			$removeThisFilterPermalink .= "&type=$type";
		}

		if ($getTag) {
			$displayTagFilter .= ' <span class="chip chip-larger">'.$getTag->name."&nbsp;<a href=\"$removeThisFilterPermalink\" title=\"Remove filter for more results\"><img src=\"/wp-content/themes/hjm/images/cancel.svg\" height=\"16\" width=\"16\" alt=\"Cancel\"></a></span>";
		}
	}
}

$displayTypeFilter = "";
if ($type <> "") {

	if ($displayTagFilter <> "") { $displayTypeFilter .= " and "; }
	$removeThisFilterPermalink = "/?s=$getSearchQuery";
	if ($tag <> "") { $removeThisFilterPermalink .= "&tag=$tag"; }

	$displayType = $type;
	if ($type == "faq") { $displayType = "FAQ"; }

	$displayTypeFilter .= "filtered by type <span class=\"chip chip-larger\">$displayType&nbsp;<a href=\"$removeThisFilterPermalink\" title=\"Remove filter for more results\"><img src=\"/wp-content/themes/hjm/images/cancel.svg\" height=\"16\" width=\"16\" alt=\"Cancel\"></a></span>";

}

// Check if the search term matches a tag or category, since those aren't looked at in WP_Query
// Note in_array() is for strict searching, so not ideal here e.g. if (in_array(strtolower($getSearchQuery), $getAllTagsNamesArray)) 
$getAllTags = get_tags();
$getMatchingTagsNamesArray = array();
$displayTagSearchMatches = "";
foreach ($getAllTags as $getTag) {
	$getTagID = $getTag->term_id;
	$getTagName = $getTag->name;
	if (strpos(strtolower($getTagName), strtolower($getSearchQuery)) !== false) {
		$getMatchingTagsNamesArray[$getTagID] = $getTagName;
	}
}
if ($getMatchingTagsNamesArray && $getSearchQuery <> "") {
	$displayTagSearchMatches = "<blockquote>";
	$displayTagSearchMatches .= "<small>Your search may also relate to ";
	if (count($getMatchingTagsNamesArray) > 1) {
		$displayTagSearchMatches .= "these topics";
	} else {
		$displayTagSearchMatches .= "this topic";
	}
	$displayTagSearchMatches .= ":</small>";
	foreach ($getMatchingTagsNamesArray as $tagKey => $tagValue) {
		$tagPermalink = get_tag_link($tagKey);
		
		$getThisTag = get_tag($tagKey);
		$tagDescription = "";
		if ($getThisTag) {
			$getTagDescription = $getThisTag->description;
			if ($getTagDescription <> "") {
				$tagDescription = "<br>$getTagDescription";
			}
		}		

		$displayTagSearchMatches .= '<div class="template-part-title-excerpt-tag"><small class="last"><a href="'.$tagPermalink.'">'.$tagValue.'</a>'.$tagDescription.'</small></div>';
	}
	// $displayTagSearchMatches .= "</small>";
	$displayTagSearchMatches .= "<small class=\"last\"><a href=\"/topic/\">See all topics</a></blockquote>";
}

// DEBUG
// $countSearchOnMultipleTypesByKeyword = HJM::countSearchOnMultipleTypesByKeyword($getSearchQuery, $tag);
// print_r($countSearchOnMultipleTypesByKeyword);

$displaySearchOnMultipleTypesByKeyword = HJM::displaySearchOnMultipleTypesByKeyword($getSearchQuery, $tag);

$displayNoteIfZero = "";
if ($totalResults == 0) {
	$displayNoteIfZero = "<p>Please try a different keyword or use less filters. See our <a href=\"/search/\">Advanced Search</a> page for all options.</p>";
}

echo <<<EOD
<p>$totalResults results for "$getSearchQuery" $displayTagFilter $displayTypeFilter</p>

$displayNoteIfZero

$displaySearchOnMultipleTypesByKeyword

$displayTagSearchMatches
EOD;

if ($totalResults > 0) {

	if ($totalResults > 10 && ($tag == "" || $type == "")) {
		$advancedSearchLink = "/search/?";
		if ($getSearchQuery <> "") { $advancedSearchLink .= "q=$getSearchQuery"; }
		if ($type <> "") { $advancedSearchLink .= "&type=$type"; }
		// if ($tag <> "") { $advancedSearchLink .= "&tag=".implode(",", $tag); }
		echo '<small>You can also <a href="'.$advancedSearchLink.'">narrow results by a topic</a> via our Advanced Search page.</small>';
	}

	// echo '<div class="grid">';

	if ($searchQuery->have_posts()) : while ($searchQuery->have_posts()) : $searchQuery->the_post();

		get_template_part( 'template-parts/title-excerpt-tag' );

	endwhile;

	// echo '</div><!-- /.grid -->';

	echo $displayPagination;

	
		$advancedSearchLink = "/search/?";
		if ($getSearchQuery <> "") { $advancedSearchLink .= "q=$getSearchQuery"; }
		if ($type <> "") { $advancedSearchLink .= "&type=$type"; }
		// if ($tag <> "") { $advancedSearchLink .= "&tag=".implode(",", $tag); }
		echo '<small>Not finding what you\'re looking for? You can narrow results by a topic with our <a href="'.$advancedSearchLink.'">Advanced Search</a> or you can use <a href="/ai-chat/">HJM Ai-Chat</a>.</small>';

	else:

		echo "<!-- else -->";

	endif;
	
}

?>

</div>
</section>
</article>

</main><!-- /#bd -->

<?php get_footer(); ?>