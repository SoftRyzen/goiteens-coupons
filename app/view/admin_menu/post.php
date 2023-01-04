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
if (isset($_GET['id']))
	$id = $_GET['id'];
if (isset($_GET['group']))
	$group = $_GET['group'];

$promocod_id = $id ? $id : $group;
$promocodes = GOIT_PRMCODE()->model->database->get_promocodes($promocod_id);

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

				$data['group_count']++;
				$data['group_array']->append($item);
				$data['group_name'] = $item->promocod_group;

			} else {
			}
			if (empty($item->promocod_group))
				$data['item'] = $item;

		endforeach;
	endif;
	?>
	<section class="goit">
		<div class="goit__header">
			<div class="goit__header-wrapper">
				<h1>
					<?php _e('Редагування промокоду', 'goit_promocode'); ?>
				</h1>
			</div>
		</div>

		<div class="goit__body goit-edit">

			<?php GOIT_PRMCODE()->view->load('admin_menu/template-parts/edit', $data); ?>

			<div class="goit-edit__side container">
				<div class="goit-edit__side-header">
					<h4 class="title">
						<?php _e('Публікація', 'goit_promocode'); ?>
					</h4>
				</div>
				<div class="goit-edit__side-buttons">
					<a id="update_promocode" href="#" class="goit-primary-btn">
						<?php _e('Оновити', 'goit_promocode'); ?>
					</a>
				</div>
			</div>

		</div>

		<?php GOIT_PRMCODE()->view->load('admin_menu/template-parts/footer'); ?>
	</section>
	<?php
endif;


if ($data['action'] != 'edit') {
	if ($promocodes):
		foreach ($promocodes as $key => $item):

			if (!empty($item->promocod_group)) {

				$data['group_count']++;
				$data['group_array']->append($item);
				$data['group_name'] = $item->promocod_group;

				if ($key == array_key_last($promocodes)) {
					GOIT_PRMCODE()->view->load('admin_menu/template-parts/post-group', $data);
				}

			} else {
				$data['item'] = $item;
				$data['url'] = false;
				GOIT_PRMCODE()->view->load('admin_menu/template-parts/post-single', $data);
			}


		endforeach;
	endif;
}
?>