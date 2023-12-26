<?php

/* Topics */

get_header();

$getID = get_the_ID();
$getTitle = get_the_title();
$pageExcerptRaw = get_the_excerpt();
$pageExcerpt = apply_filters('the_content', $pageExcerptRaw);

?>

<main id="bd">

<article>

<?php

$displayDescription = "";
if (has_excerpt()) {
	$displayDescription = '<div class="custom-excerpt">'.$pageExcerpt.'</div>';
}

$tags = get_tags();
$countTags = count($tags);

$tagBase = get_option( 'tag_base' );

$displayTopicsWithinCategories = "";
$getTopicCategories = get_terms( array(
	'taxonomy'   => 'topic_category',
	'hide_empty' => false,
) );
if ($getTopicCategories) {
	
	foreach ($getTopicCategories as $getTopicCategory) {

		$termID = $getTopicCategory->term_id;
		$termName = $getTopicCategory->name;

		$displayCategorizedTopics = '<div class="grid">';
		$tags = get_tags(array("meta_key" => "_topic_category", "meta_value" => $termID));
		foreach($tags as $tag) {

			$tagID = $tag->term_id;
			$tagSlug = $tag->slug;
			$tagName = $tag->name;
			$tagDescription = $tag->description;
			$tagCount = $tag->count;

			$tagCountSuffix = "items";
			if ($tagCount == 1) { $tagCountSuffix = "item"; }

			$displayCategorizedTopics .= <<<EOD
<div class="topic-wrap">
<a href="/$tagBase/$tagSlug/"><span class="h4 last" style="padding-top:0 !important;">$tagName</span><span class="date">$tagCount $tagCountSuffix</span></a>
</div>
EOD;

		}
		$displayCategorizedTopics .= '</div><!-- /.grid -->';

		$displayTopicsWithinCategories .= '<small>'.$termName.'</small>'.$displayCategorizedTopics;

	}
	
}

echo <<<EOD
<section class="primary">
<div class="gutter">
<h1>$getTitle</h1>
$displayDescription
<small class="last">$countTags terms</small>
</section>
<section>
<div class="gutter">

$displayTopicsWithinCategories

</div>
</section>
</article>

</main><!-- /#bd -->
EOD;

get_footer();

?>