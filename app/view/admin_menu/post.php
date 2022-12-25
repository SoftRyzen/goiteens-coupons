<?php

/* This is a PHP code. It is checking if the action is add. If it is, it is checking if the promocod is
set. If it is, it is setting the promocod to the promocod. It is doing this for all of the
variables. Then it is calling the add_promocodes function. Then it is calling wp_die. */


if (isset($_POST['promocod']))
	$promocod = $_POST['promocod'];

if (isset($_POST['activete_count_user']))
	$activete_count_user = $_POST['activete_count_user'];

if (isset($_POST['product']))
	$product = $_POST['product'];

if (isset($_POST['tariff']))
	$tariff = $_POST['tariff'];

if (isset($_POST['conditions']))
	$conditions = $_POST['conditions'];

if (isset($_POST['date_start']))
	$date_start = $_POST['date_start'];

if (isset($_POST['date_end']))
	$date_end = $_POST['date_end'];

if (isset($_POST['manager']))
	$manager = $_POST['manager'];

if (isset($_POST['status']))
	$status = $_POST['status'];

if (isset($_POST['promocode_limit']))
	$promocode_limit = $_POST['promocode_limit'];


if (isset($_POST['amount_surcharge']))
	$amount_surcharge = $_POST['amount_surcharge'];

if (isset($_POST['discount_tariff']))
	$discount_tariff = $_POST['discount_tariff'];

if (isset($_POST['count']))
	$count = $_POST['count'];

if (isset($_POST['msg_success']))
	$msg_success = $_POST['msg_success'];

if (isset($_POST['msg_not_found']))
	$msg_not_found = $_POST['msg_not_found'];

if (isset($_POST['msg_data_end']))
	$msg_data_end = $_POST['msg_data_end'];

if (isset($_POST['action']) == 'add') {
	GOIT_PRMCODE()->model->database->add_promocodes($promocod, $activete_count_user, $product,
		$tariff, $conditions, $date_start, $date_end, $manager, $status, $promocode_limit, $amount_surcharge, $discount_tariff, $count,
		$msg_success, $msg_not_found, $msg_data_end);
	exit;
}


if (isset($_POST['status']) && isset($_POST['id']) && isset($_POST['type'])) {
	GOIT_PRMCODE()->model->database->change_promocode_status($_POST['id'], $_POST['status'], $_POST['type']);
}




if (isset($_GET['new_add'])) {

}


if (isset($_GET['action'])) {
	if ($_GET['action'] == 'edit') {
		$action = $_GET['action'];
	}

	if ($_GET['action'] == 'new_add') {
		echo 'Новое добавление';
	}
}

if (isset($_GET['post'])):
	$post = sanitize_text_field($_GET['post']); ?>
<div class="goit__post">
	<div class="goit__header-wrapper">
		<h1>
			<?php _e('Промокоди GoITeens', 'goit_promocode'); ?>
		</h1>
		<a href="<?php echo admin_url('/admin.php?page=goit_promocode_add') ?>" class="page-title-action">
			<?php _e('Додати промокод', 'goit_promocode'); ?>
		</a>
		<form id="goit-promocodes-search" action="<?php echo $action_url; ?>" method="POST"
			class="media-toolbar-primary search-form">
			<input type="search" id="goit-promocodes-search-input" class="search"
				placeholder="<?php _e("Пошук", 'goit_promocode'); ?>" <?php echo 'value="' . $search_value . '"'; ?>>
		</form>
	</div>
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
</div>
<?php endif; ?>