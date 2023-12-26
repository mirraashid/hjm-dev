<?php

$uploadsDirectoryArray = wp_upload_dir();
$uploadsDirectory = $uploadsDirectoryArray['url'];

$objDateTime = new DateTime('NOW');
$year = $objDateTime->format('Y');

$displayCredit = '<small id="credit" class="first last"><a href="https://fwdlabs.com/" target="_blank" rel="noopener">Site by FWD:labs</a></small>';

$subscribeForm = do_shortcode('[mc4wp_form id=1188]');

$displaySubscribeForm = "";
if (!is_page('subscribe')) {
	$displaySubscribeForm = <<<EOD
<div class="column-flex-1"><div class="gutter">
$subscribeForm
</div></div>
EOD;
}

$siteName = get_bloginfo( 'name' );
$siteDescription = get_bloginfo( 'description' );

$displayFooterMenu1 = "<ul>";
$footerMenuItems = wp_get_nav_menu_items( 'Primary Nav' );
foreach ( $footerMenuItems as $menuItem ) {
	$menuItemTitle = $menuItem->title;
	$menuItemPermalink = $menuItem->url;
	$displayFooterMenu1 .= '<li><a href="'.$menuItemPermalink.'">'.$menuItemTitle.'</a></li>';
}
$displayFooterMenu1 .= "</ul>";

$displayFooterMenu2 = "<ul>";
$footerMenu2Items = wp_get_nav_menu_items( 'Secondary Nav' );
foreach ( $footerMenu2Items as $menu2Item ) {
	$menu2ItemTitle = $menu2Item->title;
	$menu2ItemPermalink = $menu2Item->url;
	$displayFooterMenu2 .= '<li><a href="'.$menu2ItemPermalink.'">'.$menu2ItemTitle.'</a></li>';
}
$displayFooterMenu2 .= "</ul>";

echo <<<EOD
<footer id="ft">

<div class="row">

<div class="column-flex-1">

	<div class="gutter" style="padding-bottom:0;">
	<h2 class="last" style="color:#000;">$siteName</h2>
	<p class="last">$siteDescription</p>
	</div>

	<div class="gutter">

	<div class="row row-on-mobile">
	<div class="column-flex-1" style="margin-right:1rem;">
	$displayFooterMenu1
	</div>
	<div class="column-flex-1">
	$displayFooterMenu2
	</div>
	</div>

	</div>

</div>
$displaySubscribeForm
</div>

</footer><!-- /#ft -->

<section style="background:#eee;">
<div class="row row-on-mobile" style="align-items: center;">
<div class="column-flex-1"><div class="gutter" style="text-align:left;"><small class="first last">&copy; $siteName</small></div></div>
<div class="column-flex-1"><div class="gutter" style="text-align:center"><a href="https://www.facebook.com/HealthJusticeMonitor" target="_blank" rel="me"><img src="/wp-content/themes/hjm/images/facebook.svg" alt="Facebook" height="24" width="24"></a> <a href="https://twitter.com/Health__Justice" target="_blank" rel="me"><img src="/wp-content/themes/hjm/images/twitter.svg" alt="Twitter" height="24" width="24"></a></div></div>
<div class="column-flex-1"><div class="gutter" style="text-align:right;">$displayCredit</div></div>
</div></div>
</section>

EOD;

?>
</div><!-- /#doc -->

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
