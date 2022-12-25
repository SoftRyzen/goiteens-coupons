<?php
/**
 *  Admin Add Coupon page
 */

use goit_prmcode\helper\svg_icons;

$user = wp_get_current_user(); ?>

<section class="goit-add">
	<div class="goit__header">
		<div class="goit__header-wrapper">
			<h1>
				<?php _e('Додати Промокоди', 'goit_promocode'); ?>
			</h1>
		</div>
	</div>


	<div class="goit-add__body">
		<div class="goit-add__content container">
			<div id="promocode_id" class="row">
				<div id="promocode_id_title" class="col-md-7">
					<!-- promocod -->
					<label for="promocod"><?php echo _e('Унікальний Промокод', 'goit_promocode'); ?></label>
					<input type="text" name="promocod" size="45" id="promocod" value
						placeholder="<?php echo _e('ПРОМОКОД ЛАТИНИЦЕЮ', 'goit_promocode'); ?>">
				</div>
				<div class="col-md-2">
					<!-- count -->
					<label for="count"><?php echo _e('Кількість', 'goit_promocode'); ?></label>
					<input type="number" name="count" size="45" value="1" id="count" min="1">
				</div>

				<div class="col-md-3">
					<label for="status"><?php echo _e('Статус', 'goit_promocode'); ?></label>
					<select name="status" id="status">
						<option value="2"><?php echo _e('Активований', 'goit_promocode'); ?></option>
						<option value="1"><?php echo _e('Призупинений', 'goit_promocode'); ?></option>
					</select>
				</div>
			</div>
			<div class="goit-add__separator"></div>
			<div class="row">
				<div class="col-md-3 pr-md-1">
					<!-- promocode_limit -->
					<label for="promocode_limit"><?php echo _e('Кількість активацій', 'goit_promocode'); ?></label>
					<input type="number" name="promocode_limit" id="promocode_limit" value="1" min="1" />
				</div>
				<div class="col-md-3 pl-md-1">
					<!-- activete_count_user -->
					<label for="activete_count_user"><?php echo _e('Активації для 1 користувача', 'goit_promocode'); ?></label>
					<input type="number" name="activete_count_user" id="activete_count_user" value="1" min="1" />
				</div>
				<div class="col-md-3 searator-left pr-md-1">
					<!-- date_start -->
					<label for="date_start"><?php echo _e('Початок дії', 'goit_promocode'); ?></label>
					<input type="date" id="date_start" name="date_start" value="<?php echo date('Y-m-d') ?>"
						min="<?php echo date('Y-m-d') ?>" max="">
				</div>
				<div class="col-md-3 pl-md-1">
					<!-- date_end -->
					<label for="date_end"><?php echo _e('Кінець дії', 'goit_promocode'); ?></label>
					<input type="date" id="date_end" name="date_end" value="" min="<?php echo date('Y-m-d') ?>" max="">
				</div>
			</div>

			<div class="goit-add__separator"></div>
			<div class="row">
				<!-- info block -->
				<div class="col-md-12">
					<div class="info mb-2">
						<?php echo svg_icons::get('info') ?>
						<?php echo _e('Обраний тариф діє для усіх обраних курсів, та группи купонів.', 'goit_promocode'); ?>
					</div>
				</div>
				<!-- product -->
				<div class="col-md-6">
					<label for="product" class="d-flex"><?php echo _e('Оберіть продукт', 'goit_promocode'); ?></label>
					<select name="product" id="product" multiple="multiple" class="mb-2">
						<?php
                        $loop = new WP_Query(array(
                        	'post_type'      => 'courses',
                        	'posts_per_page' => -1,
                        	'orderby'        => 'title',
                        	'order'          => 'ASC',
                        ));
                        while ($loop->have_posts()):
	                        $loop->the_post();
	                        print the_title(); ?>

						<option
							value="<?php echo get_the_permalink(); ?>?id=<?php echo get_the_ID(); ?>&name=<?php echo get_the_title(); ?>">
							<?php echo get_the_title(); ?></option>

						<?php endwhile;
                        wp_reset_postdata(); ?>
					</select>
				</div>
				<!-- selected products -->
				<div id="selected-products" class="col-md-6">
					<label for="products"><?php echo _e('Обрані продукти', 'goit_promocode'); ?></label>
					<input type="hidden" id="products" value="">
					<div class="selected-products mb-2"></div>
				</div>
				<!-- tariff -->
				<div class="col-md-6">
					<label for="tariff"><?php echo _e('Оберіть Тариф', 'goit_promocode'); ?></label>
					<select name="tariff" id="tariff">
						<option value="1"><?php echo _e('1 місяць', 'goit_promocode'); ?></option>
						<option value="2"><?php echo _e('2 місяця', 'goit_promocode'); ?></option>
						<option value="3"><?php echo _e('3 місяця', 'goit_promocode'); ?></option>
						<option value="4"><?php echo _e('4 місяця', 'goit_promocode'); ?></option>
						<option value="5"><?php echo _e('5 місяців', 'goit_promocode'); ?></option>
						<option value="6"><?php echo _e('6 місяців', 'goit_promocode'); ?></option>
						<option value="7"><?php echo _e('7 місяців', 'goit_promocode'); ?></option>
						<option value="8"><?php echo _e('8 місяців', 'goit_promocode'); ?></option>
						<option value="9"><?php echo _e('9 місяців', 'goit_promocode'); ?></option>
						<option value="10"><?php echo _e('10 місяців', 'goit_promocode'); ?></option>
						<option value="11"><?php echo _e('11 місяців', 'goit_promocode'); ?></option>
						<option value="12"><?php echo _e('12 місяців', 'goit_promocode'); ?></option>
					</select>
				</div>
				<div class="col-md-3">
					<!-- amount_surcharge -->
					<label for="amount_surcharge"><?php echo _e('Сума знижки', 'goit_promocode'); ?></label>
					<input type="number" name="amount_surcharge" size="45" min="1" value id="amount_surcharge">
				</div>
				<div class="col-md-3">
					<!-- discount_tariff -->
					<label for="amount_surcharge"><?php echo _e('Тип знижки', 'goit_promocode'); ?></label>
					<select name="discount_tariff" id="discount_tariff">
						<option value="percent"><?php echo _e('Відсоток %', 'goit_promocode'); ?></option>
						<option value="UAH"><?php echo _e('UAH', 'goit_promocode'); ?></option>
						<option value="USD"><?php echo _e('USD', 'goit_promocode'); ?></option>
						<option value="EUR"><?php echo _e('EUR', 'goit_promocode'); ?></option>
					</select>
				</div>

			</div>

			<!-- conditions -->
			<div class="goit-add__separator"></div>
			<div class="row">
				<div class="col-md-12">
					<label for="conditions"><?php echo _e('Умови акції', 'goit_promocode'); ?></label>
					<textarea name="conditions" id="conditions" cols="30" rows="2" maxlength="50"></textarea>
				</div>
			</div>

			<!-- Messages -->
			<div class="goit-add__separator"></div>
			<div class="row">
				<div class="col-md-4">
					<!-- msg_success -->
					<label for="msg_success"><?php echo _e('Повідомлення: <b>Активовано</b>', 'goit_promocode'); ?></label>
					<textarea id="msg_success" name="msg_success" size="45" cols="8" rows="2"
						placeholder="<?php echo _e('Успішно - Промокод активовано!', 'goit_promocode'); ?>"><?php echo _e('Успішно - Промокод активовано!', 'goit_promocode'); ?></textarea>
				</div>
				<div class="col-md-4 searator-left">
					<!-- msg_not_found -->
					<label for="msg_success"><?php echo _e('Повідомлення: <b>Не існує</b>', 'goit_promocode'); ?></label>
					<textarea id="msg_not_found" name="msg_not_found" size="45" cols="8" rows="2"
						placeholder="<?php echo _e('На жаль такого промокода не існує.', 'goit_promocode'); ?>"><?php echo _e('На жаль такого промокода не існує.', 'goit_promocode'); ?></textarea>

				</div>
				<div class="col-md-4 searator-left">
					<!-- msg_data_end -->
					<label for="msg_data_end"><?php echo _e('Повідомлення: <b>Завершено</b>', 'goit_promocode'); ?></label>
					<textarea id="msg_data_end" name="msg_data_end" size="45" cols="8" rows="2"
						placeholder="<?php echo _e('На жаль термін дії промокоду закінчився.', 'goit_promocode'); ?>"><?php echo _e('На жаль термін дії промокоду закінчився.', 'goit_promocode'); ?></textarea>
				</div>
			</div>

			<!-- Manager -->
			<input type="hidden" id="manager" value="<?php echo $user->id; ?>">
		</div>


		<div class="goit-add__side">
			<div class="goit-add__side-header">
				<h4 class="title"><?php echo _e('Публікація', 'goit_promocode'); ?></h4>
			</div>

			<div class="goit-add__side-buttons">
				<a id="add_promocode" href="#" class="goit-primary-btn"><?php echo _e('Опублікувати', 'goit_promocode'); ?></a>
				<!-- <a href="#" class="goit-secondary-btn"><?php echo _e('Аналітика', 'goit_promocode'); ?></a> -->
			</div>

			<!-- <div class="goit-add__side-statistic"></div> -->
		</div>

	</div>
</section>