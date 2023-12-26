<?php

$getID = get_the_ID();
$contentTitle = get_the_title();
$contentPermalink = get_permalink();

$displayTitle = '<span class="h2 last">'.$contentTitle.'</span>';

$displayExcerpt = "";
$displayTags = "";
$displayDate = "";
$displayType = "";
$displayDateOrType = "";

// if (is_category() || is_search() || is_tag()) {

    $displayExcerpt = '<span style="display:block; font-weight:normal; font-size: 1rem;">';
    // if (has_excerpt($getID)) {
    //    $displayExcerpt .= 'Excerpt: '.get_the_excerpt();
    // } else {
        $getExcerpt = strip_tags( get_the_excerpt() );
        $displayExcerpt .= $getExcerpt; // Backup
    // }
    $displayExcerpt .= '</span>';

    $getTags = get_the_tags($getID);
    $displayTags = "";
    if ($getTags) {
        $displayTags = '<span class="topics">';
        foreach ($getTags as $getTag) {
            $displayTags .= '<span class="chip">'.$getTag->name.'</span> ';
        }
        $displayTags .= '</span>';
    }
    
    if ( get_post_type( $getID ) == 'post' ) {
        $displayDate = '<span class="date" style="margin-right:1rem;">'.get_the_date( 'F j, Y' ).'</span>';
    }

    if ( is_search() || is_tag() ) {
        $displayPostType = "";
        $getPostType = get_post_type_object(get_post_type());
        if ($getPostType) {
            $displayPostType = esc_html($getPostType->labels->singular_name);
        }
        if (get_post_type( $getID ) == 'post' && in_category(53, $getID)) { $displayPostType = "Visual"; }
        $displayType = '<span class="post-type" style="margin-right:1rem;">'.$displayPostType.'</span>';
    }

    $displayPostThumbnail = "";
	$getPostThumbnail = get_the_post_thumbnail_url($getID, "medium");
	if ($getPostThumbnail) {
		$displayPostThumbnail = '<span style="flex:0; width:360px; margin-right:1rem;"><img src="'.$getPostThumbnail.'" style="max-width:360px; height:auto;"></span>';
	}

// }

echo <<<EOD
<div class="template-part-title-excerpt-tag">
<p class="last"><a href="$contentPermalink" style="display:flex;">
$displayPostThumbnail
<span style="flex:1; padding-top:0 !important;">
$displayTitle
<span class="row">$displayDate $displayType</span>
$displayExcerpt
$displayTags
</span>
</a></p>
</div>
EOD;

?>