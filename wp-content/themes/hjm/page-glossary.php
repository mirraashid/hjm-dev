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

$displayTopicsWithinCategories = "";
$getTopicCategories = get_terms( array(
	'taxonomy'   => 'topic_category',
	'hide_empty' => false,
) );
if ($getTopicCategories) {
	
	foreach ($getTopicCategories as $getTopicCategory) {

		$termID = $getTopicCategory->term_id;
		$termName = $getTopicCategory->name;

		$displayCategorizedTopics = "";
		$tags = get_tags(array("meta_key" => "_topic_category", "meta_value" => $termID));
		foreach($tags as $tag) {

			$tagID = $tag->term_id;
			$tagSlug = $tag->slug;
			$tagName = $tag->name;
			$tagDescription = $tag->description;

			$displayTagDescription = "";
			if ($tagDescription <> "") {
				$displayTagDescription = $tagDescription;
			}


			$displayCategorizedTopics .= <<<EOD
<p><a href="/$tagBase/$tagSlug/">$tagName</a><br>
$displayTagDescription</p>
EOD;

		}
		
		$displayTopicsWithinCategories .= '<small>'.$termName.'</small>'.$displayCategorizedTopics;

	}
	
}

$displayContent = "";
$content = get_the_content();
if ($content <> "") {
	$displayContent = "<hr>".apply_filters('the_content', $content);
}

echo <<<EOD
<section class="primary">
<div class="gutter">
<h1>$getTitle</h1>
$displayDescription
</section>
<section>
<div class="gutter">

$displayTopicsWithinCategories

$displayContent

</div>
</section>
</article>

</main><!-- /#bd -->
EOD;

get_footer();

?>