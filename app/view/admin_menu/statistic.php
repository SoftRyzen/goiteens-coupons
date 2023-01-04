<?php
/**
 *  Statistic page
 */

$table_items = 0;
$promocodes = GOIT_PRMCODE()->model->database->get_promocodes();
$promo_counter = GOIT_PRMCODE()->model->database->get_promocodes_count();

/* It's creating an data array with the keys and values. */
$data = [
	'item'        => [],
	'group_array' => new ArrayObject(array()),
	'group_count' => null,
	'group_name'  => null,
	'url'         => true
];

// var_dump(GOIT_PRMCODE()->model->order->get_promocode_orders(14));
?>

<section class="goit">
	<div class="goit__header-wrapper">
		<h1>
			<?php _e('Статистика промокодів GoITeens', 'goit_promocode'); ?>
		</h1>
	</div>


	<div class="goit-statistic">
		<div class="goit-statistic__table">
			<div class="goit-statistic__table-datepicker">
				<svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M8 9C7.44772 9 7 9.44771 7 10C7 10.5523 7.44772 11 8 11H16C16.5523 11 17 10.5523 17 10C17 9.44771 16.5523 9 16 9H8Z"
						fill="#1d2327" />
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M6 3C4.34315 3 3 4.34315 3 6V18C3 19.6569 4.34315 21 6 21H18C19.6569 21 21 19.6569 21 18V6C21 4.34315 19.6569 3 18 3H6ZM5 18V7H19V18C19 18.5523 18.5523 19 18 19H6C5.44772 19 5 18.5523 5 18Z"
						fill="#1d2327" />
				</svg>
				<input id="datepicker" class="form-control" type="text" name="daterange" value="" />

				<div class="result">
					<?php _e('Кількість оплат', 'goit_promocode'); ?>: <span>20</span>
					<?php _e('Сума оплат', 'goit_promocode'); ?> <span>4000₴</span>
					<?php _e('Сума знижки', 'goit_promocode'); ?>: <span>200₴</span>
				</div>
			</div>

			<div class="goit-statistic__table-header">
				<div>
					<?php _e("ID's", 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Промокод', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Кількість оплат', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Сума оплат', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Сума знижки', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Менеджер', 'goit_promocode'); ?>
				</div>
				<div>
					<?php _e('Статус', 'goit_promocode'); ?>
				</div>
			</div>
			<div class="goit-statistic__table-body">
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

							GOIT_PRMCODE()->view->load('admin_menu/template-parts/statistic-group', $data);
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

							GOIT_PRMCODE()->view->load('admin_menu/template-parts/statistic-group', $data);
							$table_items++;

							$data['group_array'] = new ArrayObject(array());
							$data['group_count'] = null;
							$data['group_name'] = null;

							$data['group_count']++;
							$data['group_array']->append($item);
							$data['group_name'] = $item->promocod_group;

						} else {
							if (!empty($data['group_name'])) {
								GOIT_PRMCODE()->view->load('admin_menu/template-parts/statistic-group', $data);
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
							GOIT_PRMCODE()->view->load('admin_menu/template-parts/statistic-single', $data);
						}

						?>
					<?php endforeach; ?>
				<?php endif; ?>
				<div class="not_found <?php if ($promocodes)
					echo 'hidden'; ?>">
					<?php _e('Нічого не знайдено', 'goit_promocode'); ?>
				</div>
			</div>
			<div class="goit-statistic__table-footer">
				<div class="counter-elements">
					<?php echo 'Кількість елементів на сторінці: ' . $table_items . '  (промокодів: ' . $promo_counter . ')'; ?>
				</div>
			</div>

		</div>

		<?php GOIT_PRMCODE()->view->load('admin_menu/template-parts/footer'); ?>
</section>