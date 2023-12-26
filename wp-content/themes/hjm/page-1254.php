<?php

/* Topics */

get_header();

$getID = get_the_ID();
$getTitle = get_the_title();
$pageExcerptRaw = get_the_excerpt();
$pageExcerpt = apply_filters('the_content', $pageExcerptRaw);
$pageContentRaw = get_the_content();
$pageContent = apply_filters('the_content', $pageContentRaw);

?>

<main id="bd">

<article>

<?php

$displayDescription = "";
if (has_excerpt()) {
	$displayDescription = '<div class="custom-excerpt">'.$pageExcerpt.'</div>';
}

echo <<<EOD
<section class="primary">
<div class="gutter">
<h1>$getTitle</h1>
$displayDescription
</section>
<section>
<div class="gutter">

$pageContent

</div>
</section>
</article>

</main><!-- /#bd -->
EOD;

get_footer();

?>