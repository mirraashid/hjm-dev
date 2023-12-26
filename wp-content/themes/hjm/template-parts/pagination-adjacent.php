<?php

// Third argument means limit the links to the same category
$previous = get_previous_post_link('%link', 'Previous', true);
$next = get_next_post_link('%link', 'Next', true);

if ($previous || $next) {
    echo <<<EOD
<nav class="navigation pagination">
	<div class="nav-links">
	    <div class="column-flex-1">$previous</div>
	    <div class="column-flex-1" style="text-align:right;">$next</div>
	</div>
</nav>
EOD;
}

?>