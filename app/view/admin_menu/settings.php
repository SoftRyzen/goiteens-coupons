<?php
/**
 *  Settings Page
 */
use goit_prmcode\helper\{media, svg_icons}; ?>

<section id="settings" class="goit">
	<div class="goit__header-wrapper">
		<?php media::print_tag(__("Налаштування GoITeens", 'goit_promocode'), 'h1'); ?>
	</div>
	<?php if (isset($_GET['action'])): ?>
		<div class="goit__status--success">
			<?php if ($_GET['action'] == 'updated'): ?>
				<?php _e('Налаштування успішно оновлено', 'goit_promocode'); ?>
			<?php endif; ?>
			<?php if ($_GET['action'] == 'db'): ?>
				<?php _e('База данних оновлено. Всі записи видалено.', 'goit_promocode'); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="goit-settings">
		<div class="container">
			<div class="row">
				<div class="col-md-9 col-sm-12">
					<div class="goit-settings__block">
						<div class="goit-settings__block-header">
							<?php _e('Налаштування Zoho CRM & W4P', 'goit_promocode'); ?>
						</div>
						<div class="goit-settings__block-content">
							<?php foreach (GOIT_PRMCODE()->config as $key => $value):
								if ($key != 'products_update'): ?>
									<label for="<?php echo $key ?>">
										<?php echo $key ?>
									</label>
									<input type="text" name="<?php echo $key ?>" id="<?php echo strtolower($key); ?>"
										value="<?php echo $value ?>">
								<?php endif;
							endforeach; ?>
						</div>

						<div class="goit-settings__block-content d-flex align-items-center">
							<a id="zoho_products_update" href="#" class="goit-primary-btn">
								<?php _e('Синхронізація продуктів Zoho', 'goit_promocode'); ?>
							</a>
						</div>
					</div>
					<div class="goit-settings__block">
						<div class="goit-settings__block-header">
							<?php _e('База Данних', 'goit_promocode'); ?>
						</div>
						<div class="goit-settings__block-content">
							<a id="rebuild_db" href="#" class="goit-primary-btn mt-3">
								<?php _e('Перестоврення бази', 'goit_promocode'); ?>
							</a>
							<div class="info mt-4 col-md-12">
								<?php echo svg_icons::get('info') ?>
								<?php _e('Видалення таблиць плагіну і створення з нуля.', 'goit_promocode'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-12">
					<div class="goit-settings__side container">
						<div class="goit-settings__side-header">
							<h4 class="title">
								<?php _e('Оновлення', 'goit_promocode'); ?>
							</h4>
						</div>
						<div class="goit-settings__side-buttons">
							<a id="settings_update" href="#" class="goit-primary-btn">
								<?php _e('Обновити', 'goit_promocode'); ?>
							</a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="loader hidden"></div>
	<?php GOIT_PRMCODE()->view->load('admin_elements/footer'); ?>
</section>