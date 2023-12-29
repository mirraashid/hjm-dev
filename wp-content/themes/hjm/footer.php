<footer id="ft">
	<div class="container-1000">

	<?php
	$siteName = get_bloginfo( 'name' );
		// $displayFooterMenu1 = "<ul class='footer-pn-1'>";
		// $footerMenuItems = wp_get_nav_menu_items( 'Primary Nav' );
		// foreach ( $footerMenuItems as $menuItem ) {
		// 	$menuItemTitle = $menuItem->title;
		// 	$menuItemPermalink = $menuItem->url;
		// 	$displayFooterMenu1 .= '<li><a href="'.$menuItemPermalink.'">'.$menuItemTitle.'</a></li>';
		// }
		// $displayFooterMenu1 .= "</ul>";
		// echo $displayFooterMenu1;

		wp_nav_menu(array('container'=>false,'menu_class'=>'footer-pn-1','menu'=>'Primary Nav'));

		wp_nav_menu(array('container'=>false,'menu_class'=>'footer-pn-2','menu'=>'Secondary Nav'));
	?>
	<div class="newsletter-wrap">
		<p>Get <img src="<?php echo get_bloginfo('template_url'); ?>/images/HJM-logo.png"> in your inbox!</p>
	<?php $subscribeForm = do_shortcode('[mc4wp_form id=1188]');
		echo $subscribeForm;
	?>
	</div>
	<section style="">
<div class="row row-on-mobile" style="align-items: center; ">
<div class="column-flex-1"><div class="gutter" style="text-align:center; padding-bottom:0;"><small class="first last">&copy; <?php echo $siteName; ?></small></div></div>

</div></div>
</section>

	</div>
</footer>
<?php wp_footer(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
<script>
WebFont.load({
	google: {
		families: ['Palanquin:300,600','Lora:400,600']
	}
});
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3F58J1XXYT"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-3F58J1XXYT');
</script>

</body>
</html>