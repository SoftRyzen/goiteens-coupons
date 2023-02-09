<?php
$dataArr = [
	'item'        => [],
	'group_array' => new ArrayObject(array()),
	'group_count' => null,
	'group_name'  => null,
	'url'         => true,
	'date_start'  => isset($data["date_start"]) ? $data["date_start"] : wp_date('Y-m-d', strtotime('-7 days')),
	'date_end'    => isset($data["date_end"]) ? $data["date_end"] : wp_date('Y-m-d')
];

if ($data["promocodes"]):
	foreach ($data["promocodes"] as $key => $item):
		/**
		 * It's checking if the promocode group is empty or not. 
		 * If it's not empty, it's adding the promocode to the group.
		 **/

		if (!empty($item->promocod_group) && $item->promocod_group == $dataArr['group_name']
			&& $key == array_key_last($data["promocodes"])) {

			$dataArr['group_count']++;
			$dataArr['group_array']->append($item);
			$dataArr['group_name'] = $item->promocod_group;

			GOIT_PRMCODE()->view->load('admin_elements/' . $data["page"] . '-group', $dataArr);

			$dataArr['group_array'] = new ArrayObject(array());
			$dataArr['group_count'] = null;
			$dataArr['group_name'] = null;

		} else if (
			(!empty($item->promocod_group) && empty($dataArr['group_name'])) ||
			(!empty($item->promocod_group) && $item->promocod_group == $dataArr['group_name'])
		) {

			$dataArr['group_count']++;
			$dataArr['group_array']->append($item);
			$dataArr['group_name'] = $item->promocod_group;

		} else if (!empty($item->promocod_group) && !empty($dataArr['group_name']) && $item->promocod_group !== $dataArr['group_name']) {

			GOIT_PRMCODE()->view->load('admin_elements/' . $data["page"] . '-group', $dataArr);

			$dataArr['group_array'] = new ArrayObject(array());
			$dataArr['group_count'] = null;
			$dataArr['group_name'] = null;

			$dataArr['group_count']++;
			$dataArr['group_array']->append($item);
			$dataArr['group_name'] = $item->promocod_group;

		} else {
			if (!empty($dataArr['group_name'])) {
				GOIT_PRMCODE()->view->load('admin_elements/' . $data["page"] . '-group', $dataArr);
			}
			$dataArr['group_array'] = new ArrayObject(array());
			$dataArr['group_count'] = null;
			$dataArr['group_name'] = null;
		}


		if (empty($item->promocod_group) && empty($dataArr['group_name'])) {
			$dataArr['item'] = $item;
			$dataArr['group_array'] = new ArrayObject(array());
			$dataArr['group_count'] = null;
			$dataArr['group_name'] = null;
			GOIT_PRMCODE()->view->load('admin_elements/' . $data["page"] . '-single', $dataArr);
		}
	?>
	<?php endforeach; ?>
<?php else: ?>
	<div class="not_found <?php if ($data["promocodes"])
		echo 'hidden'; ?>">
		<?php _e('Нічого не знайдено', 'goit_promocode'); ?>
	</div>
<?php endif; ?>
<div class="loader hidden"></div>