<?php
$id = $group = '';
$data = [
	'item'        => [],
	'group_array' => new ArrayObject(array()),
	'group_count' => null,
	'group_name'  => null,
	'url'         => true,
	'action'      => '', // edit, new_add
	'class'       => ''
];

if (isset($_GET['action']))
	$data['action'] = $_GET['action'];

$id = isset($_GET['id']) ? $_GET['id'] : false;
$group = isset($_GET['group']) ? $_GET['group'] : false;

$promocod_id = $id ? $id : $group;
$promocod_group = $id ? false : true;
$promocodes = GOIT_PRMCODE()->model->promocode->get_promocode($promocod_id, $promocod_group);

if ($data['action'] == 'edit') {
	$data['class'] = $data['action'];
	$title = __('Редагування промокоду', 'goit_promocode') . ' ' . $promocod_id;
} else if ($data['action'] == 'new_add') {
	$data['class'] = 'post';
	$title = __('Додано новий промокод', 'goit_promocode') . ' ' . $promocod_id;
} else {
	$data['class'] = 'post';
	$title = __('Промокод', 'goit_promocode') . ' ' . $promocod_id;
} ?>

<script>
	/* It's a script that is used to open the menu in the admin panel. */
	const promocode = document.querySelector('#toplevel_page_goit_promocode'),
		promocode_link = document.querySelector('#toplevel_page_goit_promocode > a');

	document.title = '<?php echo $title; ?>';
	promocode_link.classList.remove('wp-not-current-submenu');
	promocode_link.classList.add('wp-has-current-submenu');
	promocode.classList.remove('wp-not-current-submenu');
	promocode.classList.add('wp-has-current-submenu');
	promocode.classList.add('wp-menu-open');
</script>


<?php // Content Wrapper
if ($data['action'] == 'edit'):
	if ($promocodes):
		foreach ($promocodes as $item):

			if (!empty($item->promocod_group)) {

				$data['item'] = null;
				$data['group_count']++;
				$data['group_array']->append($item);
				$data['group_name'] = $item->promocod_group;

			}
			if (empty($item->promocod_group)) {
				$data['item'] = $item;
			}
		endforeach;
	endif;

	GOIT_PRMCODE()->view->load('admin_menu/edit', $data);

else:
	if ($promocodes):
		foreach ($promocodes as $key => $item):

			if ($group) {

				$data['group_count']++;
				$data['group_array']->append($item);
				$data['group_name'] = $item->promocod_group;

				if ($key == array_key_last($promocodes)) {
					GOIT_PRMCODE()->view->load('admin_elements/post-group', $data);
				}

			} else {
				$data['item'] = $item;
				$data['url'] = false;
				if ($item->promocod_group)
					$data['group_name'] = $item->promocod_group;

				GOIT_PRMCODE()->view->load('admin_elements/post-single', $data);
			}

		endforeach;
	endif;
endif;

?>