<?php

if (is_search()) {

    the_posts_pagination( array(
        'mid_size'  => 2,
        'prev_text' => __( 'Previous', 'textdomain' ),
        'next_text' => __( 'Next', 'textdomain' ),
    ) );

} else {

    the_posts_pagination( array(
        'mid_size'  => 2,
        'prev_text' => __( 'Newer', 'textdomain' ),
        'next_text' => __( 'Older', 'textdomain' ),
    ) );

}

?>