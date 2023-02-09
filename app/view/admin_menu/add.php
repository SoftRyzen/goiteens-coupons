<?php
/**
 *  Admin Add Coupon page
 */
$data = [	
	'item'        => [],
	'group_array' => new ArrayObject(array()),
	'group_count' => null,
	'group_name'  => null,
	'url'         => true,
	'action'      => 'new', // edit, new_add	
	'class'  => 'add',
];

$user = wp_get_current_user(); ?>

<section class="goit">
	<div class="goit__header">
		<div class="goit__header-wrapper">
			<h1>
				<?php _e('Додати Промокоди', 'goit_promocode'); ?>
			</h1>
		</div>
	</div>


	<div class="goit__body goit-add">

		<?php // Content Wrapper
        GOIT_PRMCODE()->view->load('admin_elements/edit', $data); ?>

		<div class="goit-add__side container">
			<div class="goit-add__side-header">
				<h4 class="title"><?php _e('Публікація', 'goit_promocode'); ?></h4>
			</div>

			<div class="goit-add__side-buttons">
				<a id="add_promocode" href="#" class="goit-primary-btn"><?php _e('Опублікувати', 'goit_promocode'); ?></a>
			</div>
		</div>

	</div>
	<?php GOIT_PRMCODE()->view->load('admin_elements/footer'); ?>
</section>