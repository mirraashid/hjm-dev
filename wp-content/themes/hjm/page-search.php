<?php

/* Advanced Search */

get_header();

$getSearchQuery = $_GET['q'] ?? ""; // Intentional use of "q" instead of "s"
$type = ( get_query_var( 'type' ) ) ? get_query_var( 'type' ) : ''; // Non-standard variable, so manually set in functions.php

$getID = get_the_ID();
$getTitle = get_the_title();
$pageExcerptRaw = get_the_excerpt();
$pageExcerpt = apply_filters('the_content', $pageExcerptRaw);

/*
$displayAllTopics = '<div class="row" style="flex-wrap:wrap; margin-bottom:1rem;">';
$tags = get_tags();
foreach($tags as $tag) {
	$tagID = $tag->term_id;
	$tagSlug = $tag->slug;
	$tagName = $tag->name;
	$tagDescription = $tag->description;
	$tagCount = $tag->count;
    $displayAllTopics .= <<<EOD
<div class="column-flex-0"><label title="$tagCount"><span class="chip" style="white-space: nowrap;"><input type="checkbox" name="tag[]" value="$tagID">$tagName</span></label></div>
EOD;
}
$displayAllTopics .= '</div>';
*/

$displayTopicsWithinCategories = "";
$getTopicCategories = get_terms( array(
	'taxonomy'   => 'topic_category',
	'hide_empty' => false,
) );
if ($getTopicCategories) {
	$displayTopicsWithinCategories .= '<div style="margin-left:2rem;">';
	foreach ($getTopicCategories as $getTopicCategory) {

		$termID = $getTopicCategory->term_id;
		$termName = $getTopicCategory->name;

		$displayCategorizedTopics = '<div class="row" style="flex-wrap:wrap; margin-bottom:1rem;">';
		$tags = get_tags(array("meta_key" => "_topic_category", "meta_value" => $termID));
		foreach($tags as $tag) {
			$tagID = $tag->term_id;
			$tagSlug = $tag->slug;
			$tagName = $tag->name;
			$tagDescription = $tag->description;
			$tagCount = $tag->count;
			$displayCategorizedTopics .= <<<EOD
<div class="column-flex-0"><label title="$tagCount"><span class="chip" style="white-space: nowrap;"><input type="checkbox" name="tag[]" value="$tagID">$tagName</span></label></div>
EOD;
		}
		$displayCategorizedTopics .= '</div>';

		$displayTopicsWithinCategories .= '<small>'.$termName.'</small>'.$displayCategorizedTopics;

	}
	$displayTopicsWithinCategories .= '</div>';
}

$radioAnyChecked = "checked";
$radioPostChecked = "";
$radioVisualChecked = "";
$radioFAQChecked = "";
if ($type == "post") {
	$radioAnyChecked = "";
	$radioPostChecked = "checked";
	$radioVisualChecked = "";
	$radioFAQChecked = "";
} else if ($type == "visual") {
	$radioAnyChecked = "";
	$radioPostChecked = "";
	$radioVisualChecked = "checked";
	$radioFAQChecked = "";
} else if ($type == "faq") {
	$radioAnyChecked = "";
	$radioPostChecked = "";
	$radioVisualChecked = "";
	$radioFAQChecked = "checked";
}

$getAllTypes = array("post" => "Posts", "visual" => "Visual", "faq" => "FAQ");
$displayAllTypes = <<<EOD
<div style="margin-left:2rem;"><div class="row row-on-mobile" style="flex-wrap:wrap; margin-bottom:1rem;">
<div class="column-flex-0" style="margin-right:1rem;"><label style="white-space: nowrap;"><input type="radio" name="type" value="" checked>Any</label></div>
EOD;
foreach ($getAllTypes as $getTypeSlug => $getTypeName) {
	$displayAllTypes .= '<div class="column-flex-0" style="margin-right:1rem;"><label style="white-space: nowrap;"><input type="radio" name="type" value="'.$getTypeSlug.'" ';
	if ($getTypeSlug == "post") { $displayAllTypes .= $radioPostChecked; }
	else if ($getTypeSlug == "visual") { $displayAllTypes .= $radioVisualChecked; }
	else if ($getTypeSlug == "faq") { $displayAllTypes .= $radioFAQChecked; }
	$displayAllTypes .= '>'.$getTypeName.'</label></div>';
}
$displayAllTypes .= '</div></div>';

$displayContent = get_the_content();
$displayContent = apply_filters('the_content', $displayContent);

echo <<<EOD
<main id="bd">
<article>
<section>
<div class="gutter">

<h1>$getTitle</h1>

$displayContent

<form action="/" method="get" class="searchform searchform-advanced">

<label for="search"><strong>Keyword</strong> (Optional)</label>
<input type="text" name="s" id="search" value="$getSearchQuery" placeholder="Search HJM...">

<p><strong>Content Types</strong></p>
$displayAllTypes

<p><strong>Topics</strong> (Optional)</p>
$displayTopicsWithinCategories

<div class="aligncenter">
<input type="submit" value="Search">
</div>

</form>

</div>
</section>
</article>
</main><!-- /#bd -->
EOD;

get_footer();

?>