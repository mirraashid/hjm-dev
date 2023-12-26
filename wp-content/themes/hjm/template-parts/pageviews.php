<?php

$getID = get_the_ID();
$getTitle = get_the_title();
$getPermalink = get_permalink();

$shareURL = rawurlencode($getPermalink);
$shareMessage = rawurlencode($getTitle."\n\n".$getPermalink);
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shareURL;
$twitterURL = 'https://twitter.com/intent/tweet?source=tweetbutton&text='.$shareMessage.rawurlencode("\n\n via @Health__Justice");
$linkedinURL = 'https://www.linkedin.com/sharing/share-offsite/?url='.$shareURL;
$mailURL = 'mailto:?subject='.$getTitle.'&body='.$shareMessage;

$displayViews = "";
if(function_exists('the_views')) {
    $getViews = (int) get_post_meta( $getID, 'views', true );
    $displayViews = $getViews." view";
    if ($getViews > 1) { $displayViews .= "s"; }
}

if ($displayViews <> "") {
    echo <<<EOD
<div class="row row-on-mobile">
<div class="column-flex-1">
<small>$displayViews</small>
</div>
<div class="column-flex-1">
<small style="text-align:right;">Share to <a href="$facebookURL" target="_blank" rel="nofollow">Facebook</a> <a href="$twitterURL" target="_blank" rel="nofollow">Twitter</a> <a href="$linkedinURL" target="_blank" rel="nofollow">LinkedIn</a> <a href="$mailURL" target="_blank" rel="nofollow">Email</a></small>
</div>
</div>
EOD;
}

?>