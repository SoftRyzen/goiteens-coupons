<?php
/**
 *  Admin Promocodes page
 */

$table_items = 0;
$search_value = '';
$action_url = admin_url('/admin.php?page=goit_promocode');

/* It's creating an data array with the keys and values. */
$data = [
	'item'        => [],
	'group_array' => new ArrayObject(array()),
	'group_count' => null,
	'group_name'  => null
];

/* It's checking if the status, id and type are set. If they are, it's calling the function
`change_promocode_status` from the database model. */
if (isset($_POST['status']) && isset($_POST['id']) && isset($_POST['type'])) {
	GOIT_PRMCODE()->model->database->change_promocode_status($_POST['id'], $_POST['status'], $_POST['type']);
}

if (isset($_GET['search'])) {
	$action_url = admin_url('/admin.php?page=goit_promocode&search=') . $_GET['search'];
	$search_value = $_GET['search'];
}
$promocodes = GOIT_PRMCODE()->model->database->get_promocodes($search_value);
$promo_counter = GOIT_PRMCODE()->model->database->get_promocodes_count($search_value);
?>

<section class="goit-promocodes">
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

	<div class="goit-promocodes__table">
		<div class="goit-promocodes__table-tabs">
			<a href="#" class="active" data-status="all">Усі</a>
			<a href="#" data-status="active">Активні</a>
			<a href="#" data-status="inactive">Неактивні</a>
		</div>
		<div class="goit-promocodes__table-header">
			<div> <?php _e("ID's", 'goit_promocode'); ?> </div>
			<div> <?php _e('Промокод', 'goit_promocode'); ?> </div>
			<div> <?php _e('Кількість оплат', 'goit_promocode'); ?> </div>
			<div> <?php _e('Сума оплат', 'goit_promocode'); ?> </div>
			<div> <?php _e('Сума знижки', 'goit_promocode'); ?> </div>
			<div> <?php _e('Початок', 'goit_promocode'); ?> </div>
			<div> <?php _e('Кінець', 'goit_promocode'); ?> </div>
			<div> <?php _e('Використано (%)', 'goit_promocode'); ?> </div>
			<div> <?php _e('Статус', 'goit_promocode'); ?> </div>
		</div>
		<div class="goit-promocodes__table-body">
			<?php if ($promocodes):
	            foreach ($promocodes as $key => $item):
		            /**
					 * It's checking if the promocode group is empty or not. 
					 * If it's not empty, it's adding the promocode to the group.
					 **/

		            if (!empty($item->promocod_group) && $item->promocod_group == $data['group_name']
		            	&& $key == array_key_last($promocodes)) {

			            $data['group_count']++;
			            $data['group_array']->append($item);
			            $data['group_name'] = $item->promocod_group;

			            GOIT_PRMCODE()->view->load('admin_menu/template-parts/group-promocode', $data);
			            $table_items++;

			            $data['group_array'] = new ArrayObject(array());
			            $data['group_count'] = null;
			            $data['group_name'] = null;

		            } else if (
		            	(!empty($item->promocod_group) && empty($data['group_name'])) ||
		            	(!empty($item->promocod_group) && $item->promocod_group == $data['group_name'])
		            ) {

			            $data['group_count']++;
			            $data['group_array']->append($item);
			            $data['group_name'] = $item->promocod_group;

		            } else if (!empty($item->promocod_group) && !empty($data['group_name']) && $item->promocod_group !== $data['group_name']) {

			            GOIT_PRMCODE()->view->load('admin_menu/template-parts/group-promocode', $data);
			            $table_items++;

			            $data['group_array'] = new ArrayObject(array());
			            $data['group_count'] = null;
			            $data['group_name'] = null;

			            $data['group_count']++;
			            $data['group_array']->append($item);
			            $data['group_name'] = $item->promocod_group;

		            } else {
			            if (!empty($data['group_name'])) {
				            GOIT_PRMCODE()->view->load('admin_menu/template-parts/group-promocode', $data);
				            $table_items++;
			            }
			            $data['group_array'] = new ArrayObject(array());
			            $data['group_count'] = null;
			            $data['group_name'] = null;
		            }


		            if (empty($item->promocod_group) && empty($data['group_name'])) {
			            $data['item'] = $item;
			            $data['group_array'] = new ArrayObject(array());
			            $data['group_count'] = null;
			            $data['group_name'] = null;
			            $table_items++;
			            GOIT_PRMCODE()->view->load('admin_menu/template-parts/item-promocode', $data);
		            }

            ?>
			<?php endforeach; ?>
			<?php endif; ?>
			<div class="not_found <?php if ($promocodes)
	            echo 'hidden'; ?>">
				<?php echo _e('Нічого не знайдено', 'goit_promocode'); ?>
			</div>
		</div>
		<div class="goit-promocodes__table-footer">
			<div class="counter-elements">
				<?php echo 'Кількість елементів на сторінці: ' . $table_items . '  (промокодів: ' . $promo_counter . ')'; ?>
			</div>
		</div>

		<?php GOIT_PRMCODE()->view->load('admin_menu/template-parts/footer'); ?>
	</div>
</section>