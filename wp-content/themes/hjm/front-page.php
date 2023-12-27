<?php

get_header();

echo '<main id="bd">';

?>
<div>
	<div class="recent-posts-container">
		<h2 class="bold">Recent Posts</h2>
		<div class="recent-posts">
			<?php
			$featured_posts = get_field('featured_posts');
				
			if(!empty($featured_posts)){
				$args = array('post__in' => $featured_posts,'posts_per_page'=>6,  'ignore_sticky_posts' => 1,'orderby' => 'post__in'  );
				$query = new WP_Query($args);
				if($query->have_posts()):while($query->have_posts()):$query->the_post();
				?>
				<div class="post-preview-card">
					<div class="card-calendar-wrap">
						<img class="calendar" src="<?php echo get_bloginfo('template_url'); ?>/images/calendar.svg" />
						<p class="date-text"><?php echo get_the_date( 'F j, Y' ); ?></p>
					</div>
					<div class="recent-posts-inner">
						<p class="text-wrapper"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></p>
						<p class="div">
							<?php echo wp_trim_words(get_the_content(),'40',' [...]'); ?>
							
						</p>
						<div>
							<?php
								$posttags = get_the_tags();
								
								if ($posttags) {
									if(count($posttags) > 5){
										$remaining = count($posttags)-5;
									} ?>
									<ul class="posttags">
									<?php
									$i=0;
									foreach($posttags as $tag) {
										if($i==5){
											?>
											<li>
												<span><?php echo "+".$remaining.' more'; ?></span>
											</li>
											<?php
											break;
										}else{
											?>
											<li>
												<a href="<?php echo esc_attr( get_tag_link( $tag->term_id ) ); ?>"><?php echo $tag->name; ?></a>
											</li>
											<?php
										}

									$i++; 
								}
								?>
								</ul>
								<?php
								}
							?>
						</div>
					</div>
				</div>
				<?php
				endwhile;endif;
				wp_reset_query();
			}
			?>
		</div>
		<a href="<?php echo get_bloginfo('url'); ?>/posts" class="btn-icon floatright">See all posts <img src="<?php echo get_bloginfo('template_url'); ?>/images/arrow-right.png"></a>
			<div style="clear:both;"></div>
	</div>
</div>


<div>
            <div class="visual-slider">
                <h2 class="bold">Visualize Health Care</h2>
                <div class="owl-carousel">
					<?php
						$query = new WP_Query(array('posts_per_page'=>5,'cat' => 53));
						if($query->have_posts()):while($query->have_posts()):$query->the_post(); ?>
						<div class="item"> 
						<?php
							$thumbnail = get_the_post_thumbnail_url(get_the_ID(), "medium");
						?>
						<img src="<?php echo $thumbnail; ?>"/>
						</div>
						<?php
						endwhile; endif;
					?>
					
					
                </div>
                <div class="visuals-btn-wrap">
				<a href="<?php echo get_bloginfo('url'); ?>/visuals" class="btn-icon floatright">See all visuals <img src="<?php echo get_bloginfo('template_url'); ?>/images/arrow-right.png"></a>
					<div style="clear:both;"></div>
                </div>
            </div>
        </div>


<?php

echo '</main><!-- /#bd -->';

get_footer();

?>