<?php
/**
 *  Admin Promocodes page
 */
$order = 'ASC';
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
/* It's checking if the search value is set. If it's set, it's adding the search value to the action
url and getting the promocodes from the database. If it's not set, it's just getting the promocodes
from the database. */
if (isset($_GET['search'])) {
	$action_url = admin_url('/admin.php?page=goit_promocode&search=') . $_GET['search'];
	$search_value = $_GET['search'];
	$promocodes = GOIT_PRMCODE()->model->database->get_promocodes();
	$promo_counter = GOIT_PRMCODE()->model->database->get_promocodes_count();
} else {
	$promocodes = GOIT_PRMCODE()->model->database->get_promocodes();
	$promo_counter = GOIT_PRMCODE()->model->database->get_promocodes_count();
} ?>

<section class="goit-promocodes">
	<div class="goit-promocodes__header-wrapper">
		<h1>
			<?php _e('Промокоди GoITeens', 'goit_promocode'); ?>
		</h1>
		<a href="<?php echo admin_url('/admin.php?page=goit_promocode_add') ?>" class="page-title-action">
			<?php _e('Додати промокод', 'goit_promocode'); ?>
		</a>
		<form id="goit-promocodes-search" action="<?php echo $action_url; ?>" method="POST"
			class="media-toolbar-primary search-form">
			<label for="media-search-input" class="media-search-input-label"><?php _e("Пошук", 'goit_promocode'); ?></label>
			<input type="search" id="goit-promocodes-search-input" class="search" <?php echo 'value="' . $search_value . '"'; ?>>
		</form>
	</div>

	<div class="goit-promocodes__table">
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
		<?php if ($promocodes):
	        foreach ($promocodes as $key => $item):
		        /**
				 * It's checking if the promocode group is empty or not. 
				 * If it's not empty, it's adding the promocode to the group.
				 **/

		        if (
		        	(!empty($item->promocod_group) && empty($data['group_name'])) ||
		        	(!empty($item->promocod_group) && $item->promocod_group == $data['group_name'])
		        ) {

			        $data['group_count']++;
			        $data['group_array']->append($item);
			        $data['group_name'] = $item->promocod_group;

		        } else if (!empty($item->promocod_group) && $item->promocod_group == $data['group_name']
		        	&& $key == array_key_last($promocodes)) {

			        GOIT_PRMCODE()->view->load('admin_menu/group-promocode', $data);
			        $table_items++;

			        $data['group_array'] = new ArrayObject(array());
			        $data['group_count'] = null;
			        $data['group_name'] = null;

		        } else if (!empty($item->promocod_group) && !empty($data['group_name']) && $item->promocod_group !== $data['group_name']) {

			        GOIT_PRMCODE()->view->load('admin_menu/group-promocode', $data);
			        $table_items++;

			        $data['group_array'] = new ArrayObject(array());
			        $data['group_count'] = null;
			        $data['group_name'] = null;

			        $data['group_count']++;
			        $data['group_array']->append($item);
			        $data['group_name'] = $item->promocod_group;

		        } else {
			        if (!empty($data['group_name'])) {
				        GOIT_PRMCODE()->view->load('admin_menu/group-promocode', $data);
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
			        GOIT_PRMCODE()->view->load('admin_menu/item-promocode', $data);
		        }

        ?>
		<?php endforeach; else:
	        echo _e('Нічого не знайдено', 'goit_promocode');
        endif; ?>

		<?php echo '<p>Кількість елементів на сторінці: ' . $table_items . '  (промокодів: ' . $promo_counter . ')</p>'; ?>
	</div>
</section>