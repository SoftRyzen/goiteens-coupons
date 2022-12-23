<script>
	// Must be move to app.js
	let promocode = document.querySelector('#toplevel_page_goit_promocode');
	let promocode_link = document.querySelector('#toplevel_page_goit_promocode > a');

	promocode_link.classList.remove('wp-not-current-submenu');
	promocode_link.classList.add('wp-has-current-submenu');
	promocode.classList.remove('wp-not-current-submenu');
	promocode.classList.add('wp-has-current-submenu');
	promocode.classList.add('wp-menu-open');
</script>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	$action = $_GET['action'];
} else {
	echo 'Not Found Promocode';
	exit;
}

if (isset($_GET['post'])):
	$post = sanitize_text_field($_GET['post']); ?>

<div class="wrap">
	<h1><?php _e('Змінити Промокод', 'goit_promocode'); ?></h1>
	<form action="<?php echo admin_url('/admin.php?page=goit_promocode_post&post=' . $post . '&action=edit') ?>"
		method="post" name="<?php echo $post ?>">
		<input type="hidden" name="product_id" value="<?php echo $post ?>">
	</form>
	<a class="products-detail products-detail_click"
		href="<?php echo admin_url('/admin.php?page=goit_promocode_post&post=' . $post . '&action=edit') ?>"
		onclick="document.<?php echo 'form' . $post ?>.submit();return false;">Відправити тестову форму</a>
</div>

<?php endif;
if (isset($_GET['post'])) {
}
if (isset($_POST['product_id'])) {
	print_r($_POST);
	unset($_POST);
}
?>