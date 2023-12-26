<?php

$getID = get_the_ID();
$getPost = get_post($getID);
$postParent = $getPost->post_parent;

$getBreadcrumbs = "";

// Sub-pages
if ($postParent <> 0) {
	$getParentPageTitle = get_the_title($postParent);
	$getParentPagePermalink = get_permalink($postParent);
	$getBreadcrumbs .= '<a href="'.$getParentPagePermalink.'">'.$getParentPageTitle.'</a>';
}

// Single posts and visualizations
if (is_single()) {
	$category = get_the_category();
	if ( ! empty( $category ) ) {
		$categoryName = $category[0]->name;
		$categoryID = $category[0]->term_id;
		$categoryLink = get_category_link($categoryID);
		$getBreadcrumbs .= '<a href="'.$categoryLink.'">'.$categoryName.'</a>';
	}
}

// Tag
if ( is_tag()) {
    $topicBase = 'topic';
	$getBreadcrumbs .= '<a href="/'.$topicBase.'/">Topics</a>';
}

// FAQ
if ( get_post_type( $getID ) == 'faq' && is_singular()) {
	$post_type_data = get_post_type_object( 'faq' );
    $faqBase = $post_type_data->rewrite['slug'];
	$getBreadcrumbs .= '<a href="/'.$faqBase.'/">FAQ</a>';
}

// Author
if ( is_author()) {
    $topicBase = 'topic';
	$getBreadcrumbs .= '<strong>Author</strong>';
}

if ($getBreadcrumbs <> "") {
	echo <<<EOD
<div class="breadcrumb">
<p class="last">$getBreadcrumbs</p>
</div>
EOD;
}

?>