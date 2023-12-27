<?php

$tagID = "";
/*
if (is_tag()) {
	$tagID = get_queried_object()->term_id;
}
*/

$allowedTypes = array("post", "visual", "faq");

$type = ( get_query_var( 'type' ) ) ? get_query_var( 'type' ) : ''; // Non-standard variable, so manually set in functions.php
if ($type == "") {
	// Suggest a type if we're in that section
	if (is_category(1)) { $type = "post"; }
	if (is_category(53)) { $type = "visual"; }
	if ('faq' == get_post_type()) { $type = "faq"; }
}

$advancedSearchPermalink = get_bloginfo('url')."/search/";
if ($type <> "" && in_array($type, $allowedTypes)) { $advancedSearchPermalink .= "?type=$type"; }

/*
$getAllTags = get_tags();
$displayTagSelect = '<div class="column-flex-0"><select name="tag"><option value="">Any Topic</option>';
foreach ($getAllTags as $getTag) {
	$getTagID = $getTag->term_id;
	$getTagName = $getTag->name;
	$displayTagSelect .= '<option value="'.$getTagID.'"';
	if ($getTagID == $tagID) { $displayTagSelect .= ' selected'; } // You are here
	$displayTagSelect .= '>'.$getTagName.'</option>';
}
$displayTagSelect .= '</select></div>';
*/

$displayTagSelect = "";
$getTopicCategories = get_terms( array(
	'taxonomy'   => 'topic_category',
	'hide_empty' => false,
) );
if ($getTopicCategories) {
	$displayTagSelect .= '<div class="column-flex-0"><select name="topic"><option value="">Any Topic</option>';
	foreach ($getTopicCategories as $getTopicCategory) {

		$termID = $getTopicCategory->term_id;
		$termName = $getTopicCategory->name;

		$displayCategorizedTopicOptions = '';
		$tags = get_tags(array("meta_key" => "_topic_category", "meta_value" => $termID));
		foreach($tags as $tag) {

			$getTagID = $tag->term_id;
			$getTagSlug = $tag->slug;
			$getTagName = $tag->name;
			$getTagDescription = $tag->description;
			$getTagCount = $tag->count;

			$displayCategorizedTopicOptions .= '<option value="'.$getTagID.'"';
			if ($getTagID == $tagID) { $displayCategorizedTopicOptions .= ' selected'; } // You are here
			$displayCategorizedTopicOptions .= '>'.$getTagName.'</option>';
			
		}

		$displayTagSelect .= '<optgroup label="'.$termName.'">'.$displayCategorizedTopicOptions.'</optgroup>';

	}
	$displayTagSelect .= '</select></div>';
}

$getAllTypes = array("post" => "Post", "visual" => "Visual", "faq" => "FAQ");
$displayTypeSelect = '<div class="column-flex-0"><select name="type"><option value="">Any Content Type</option>';
foreach ($getAllTypes as $getTypeSlug => $getTypeName) {
	$displayTypeSelect .= '<option value="'.$getTypeSlug.'"';
	if ($getTypeSlug == $type) { $displayTypeSelect .= ' selected'; } // You are here
	$displayTypeSelect .= '>'.$getTypeName.'</option>';
}
$displayTypeSelect .= '</select></div>';

?>

<form action="/" method="get" class="searchform row" style="align-items: center;">
	<div class="column-flex-1"><label for="search" class="screen-reader-text">Search</label><input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="Find HJM content.." required></div>

<?php

// echo $displayTagSelect;
echo $displayTypeSelect;

// wp_dropdown_categories() doesn't provide enough control, even with copious arguments provided, like tag=0 if nothing selected vs. tag= as it should be
/*
$tagArgs = array(
	'taxonomy'           => 'post_tag',
	'show_option_all'    => 'Any Topic',
	'show_option_none'   => '',
	'orderby'            => 'name', 
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 1, 
	'child_of'           => 0,
	'echo'               => 0,
	'selected'           => 0,
	'hierarchical'       => 0, 
	'name'               => 'tag',
	'id'                 => '',
	'class'              => '',
	'depth'              => 0,
	'tab_index'          => 5,
	'hide_if_empty'      => false
);
$select = wp_dropdown_categories($tagArgs);
*/

echo <<<EOD
<div class="column-flex-0" style="display: flex; align-items: center;"><input type="submit" value="Search" class="button button-secondary"></div>



</form><div class="adv-search-div" style="">
<small class="last"><a href="$advancedSearchPermalink">Advanced&nbsp;Search</a></small>
</div>
EOD;

?>