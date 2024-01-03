<?php

get_header();

if(!empty(get_field('banner_image'))){
	?>
	<div class="home-banner-wrap">
		<img src="<?php echo get_field('banner_image'); ?>" alt="">
	</div>
	<?php
}
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
			<?php endwhile; endif; wp_reset_query(); ?>
		</div>
		<div class="visuals-btn-wrap">
			<a href="<?php echo get_bloginfo('url'); ?>/visuals" class="btn-icon floatright">See all visuals <img src="<?php echo get_bloginfo('template_url'); ?>/images/arrow-right.png"></a>
			<div style="clear:both;"></div>
		</div>
	</div>
</div>




<div class="container">
	<div class="faq">
		<h2 class="bold">Frequently asked questions</h2>
		<div class="faq-container accordion">
			<?php
				// $faq_args = array(
				// 	'post_type' => 'faq',
				// 	'posts_per_page' => 6,
				// 	'meta_key' => 'views',
				// 	'orderby' => 'meta_value_num',
				// 	'order' => 'DESC'
				// );
				// $query = new WP_Query($faq_args);

				$featured_faqs = get_field('select_faqs');
				
				$args = array('post_type'=>'faq','post__in' => $featured_faqs,'posts_per_page'=>6,  'ignore_sticky_posts' => 1,'orderby' => 'post__in'  );
				$query = new WP_Query($args);
				$faq_arr = array();
				if($query->have_posts()):while($query->have_posts()):$query->the_post();
				$faq_arr[] = array('title'=> get_the_title(),'description' => get_the_excerpt());
				?>
				<!-- <div class="accordion-item">
				<button aria-expanded="false"><span class="accordion-title"><?php echo get_the_title(); ?></span><span class="icon" aria-hidden="true"></span></button>
					<div class="accordion-content">
						<p><?php //echo get_the_excerpt(); ?></p>
					</div>
				</div> -->
				<?php
				endwhile;endif;
				wp_reset_query();

				if(!empty($faq_arr)){
					$countRecords = count($faq_arr);
					$col1 = array_slice($faq_arr, 0, $countRecords/2 + 0.5);
					$col2 = array_slice($faq_arr, $countRecords/2 + 0.5, $countRecords);
					$row = array("column1" => $col1, "column2" => $col2);
					?>
					<div class="single-column">
						<?php
						if(!empty($row['column1'])){
							foreach($row['column1'] as $key => $val){
								?>
								<div class="accordion-item">
									<button aria-expanded="false"><span class="accordion-title"><?php echo $val['title']; ?></span><span class="icon" aria-hidden="true"></span></button>
										<div class="accordion-content">
											<p><?php echo $val['description']; ?></p>
										</div>
								</div> 
							<?php
							}
						}
						?>
					</div>
					<div class="single-column">
						<?php
						if(!empty($row['column2'])){
							foreach($row['column2'] as $key => $val){
								?>
								<div class="accordion-item">
									<button aria-expanded="false"><span class="accordion-title"><?php echo $val['title']; ?></span><span class="icon" aria-hidden="true"></span></button>
										<div class="accordion-content">
											<p><?php echo $val['description']; ?></p>
										</div>
								</div> 
							<?php
							}
						}
						?>
					</div>
					<?php
				}


				
				// echo "<pre>";
				// print_r($row);
				// die;

			?>
			<!-- <div class="faq-item">
				<button class="faq-question">What other countries have tried single payer?</button>
				<div class="faq-answer">
					<p>Content for the answer...</p>
				</div>
			</div>
			<div class="faq-item">
				<button class="faq-question">Who finances for single payer?</button>
				<div class="faq-answer">
					<p>Content for the answer...</p>
				</div>
			</div>
			<div class="faq-item">
				<button class="faq-question">Would doctors make less under single payer?</button>
				<div class="faq-answer">
					<p>Content for the answer...</p>
				</div>
			</div>
			
			<div class="faq-item">
				<button class="faq-question">Will patient choice be reduced under SP?</button>
				<div class="faq-answer">
					<p>Content for the answer...</p>
				</div>
			</div>
			
			<div class="faq-item">
				<button class="faq-question">What about all the insurance industry jobs?</button>
				<div class="faq-answer">
					<p>Content for the answer...</p>
				</div>
			</div>
			
			<div class="faq-item">
				<button class="faq-question">Does single payer equal socialism?</button>
				<div class="faq-answer">
					<p>Content for the answer...</p>
				</div>
			</div> -->
		</div>
	</div>
</div>


<section class="ai-chat">
	<div class="gutter">
		<h2 class="bold">Ask your own question!</h2>
	<div class="iframe-wrap"><iframe src="https://widget.writesonic.com/CDN/index.html?service-base-url=https://api.botsonic.ai&amp;token=7174aca8-b181-4b2f-b132-406b824fd7bb&amp;base-origin=https://bot.writesonic.com&amp;instance-name=Botsonic&amp;standalone=true&amp;page-url=https://bot.writesonic.com/00db58eb-d803-4d19-a55c-01867f01add9?t=share&amp;workspace_id=4482bd9a-fa91-483c-8768-8d9a7c5d8ebc" frameborder="0">
	</iframe></div>

	</div>

</section>

<?php

echo '</main><!-- /#bd -->';

get_footer();

?>