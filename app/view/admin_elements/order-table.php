<div class="goit__table statistic-orders">
	<div class="goit__table-header">
		<div class="col">
			<?php _e("Order ID", 'goit_promocode'); ?>
		</div>
		<div class="col">
			<?php _e('Інвойс', 'goit_promocode'); ?>
		</div>
		<?php if ($data['type'] == 'group'): ?>
			<div class="col">
				<?php _e('Промокод', 'goit_promocode'); ?>
			</div>
		<?php endif; ?>
		<div class="col">
			<?php _e('Дата', 'goit_promocode'); ?>
		</div>
		<div class="col">
			<?php _e('Ціна', 'goit_promocode'); ?>
		</div>
		<div class="col">
			<?php _e('Знижка', 'goit_promocode'); ?>
		</div>
		<div class="col">
			<?php _e('Статус', 'goit_promocode'); ?>
		</div>
	</div>
	<div class="goit__table-body">
		<?php if ($data['orders']):
			foreach ($data['orders'] as $order): ?>

				<div class="goit__table-item">
					<div class="col">
						<?php echo $order->id; ?>
					</div>
					<div class="col">
						<?php echo $order->invoice; ?>
					</div>
					<?php if ($data['type'] == 'group'):
						// Promocod ?>
						<div class="col">
							<?php echo GOIT_PRMCODE()->model->promocode->get_promocode_name($order->promocod_id); ?>
						</div>
					<?php endif; ?>
					<div class="col">
						<?php echo wp_date("d.m.Y", strtotime($order->date_order)); ?>
					</div>
					<div class="col">
						<?php if ($order->discount_tariff != 'percent') {
							echo $order->product_price - $order->discount . ' UAH';
						} else {
							echo ($order->product_price * $order->discount) / 100;
						} ?>
					</div>
					<div class="col">
						<?php if ($order->discount_tariff != 'percent') {
							echo $order->discount . ' ' . $order->discount_tariff;
						} else {
							echo $order->discount . '%';
						} ?>
					</div>
					<div class="col">
						<?php if ($order->order_status == 0) {
							echo '<span class="status-in-progress">' . __('Не оплачено', 'goit_promocode') . '</span>';
						} else {
							echo '<span class="status-done">' . __('Оплачено', 'goit_promocode') . '</span>';
						} ?>
					</div>
				</div>


			<?php endforeach; ?>
		<?php else: ?>
			<div class="not_found">
				<?php _e('Нічого не знайдено', 'goit_promocode'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>