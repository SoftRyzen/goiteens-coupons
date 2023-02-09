<section class="goit">
	<div class="goit__header">
		<div class="goit__header-wrapper">
			<h1>
				<?php _e('Редагування промокоду', 'goit_promocode'); ?>
			</h1>
		</div>
	</div>

	<div class="goit__body goit-edit">

		<?php GOIT_PRMCODE()->view->load('admin_elements/edit', $data); ?>

		<div class="goit-edit__side container">
			<div class="goit-edit__side-header">
				<h4 class="title">
					<?php _e('Публікація', 'goit_promocode'); ?>
				</h4>
			</div>
			<div class="goit-edit__side-buttons">
				<a id="update_promocode" href="#" class="goit-primary-btn">
					<?php _e('Обновити', 'goit_promocode'); ?>
				</a>
			</div>
		</div>

	</div>

	<?php GOIT_PRMCODE()->view->load('admin_elements/footer'); ?>
</section>