<?php
/**
 *  Products Page
 */

use goit_prmcode\helper\media;

$id = isset($_GET['id']) ? $_GET['id'] : false;
$last_product_update = GOIT_PRMCODE()->config['products_update'];?>

<section id="products" class="goit">
	<div class="goit__header-wrapper">
		<?php media::print_tag(__("Продукти GoITeens", 'goit_promocode'), 'h1'); ?>
	</div>
	<div class="goit__table">
		<div class="goit__table-header">
			<?php
			media::print_tag(__("Назва Продукту", 'goit_promocode'), 'div');
			media::print_tag(__("Zoho CRM ID", 'goit_promocode'), 'div');
			media::print_tag(__("Ціна за рік", 'goit_promocode'), 'div'); ?>
		</div>
		<div class="goit__table-body">
			<?php foreach (GOIT_PRMCODE()->model->product->get_all_products() as $product): ?>
				<div class="goit__table-item<?php if ($id == $product->id)
					echo ' current-product'; ?>">
					<?php media::print_tag($product->product_name, 'div', 'div', 'goit__table-item__product-name'); ?>
					<?php if (GOIT_PRMCODE()->config['products_crm_URL'] == ''): ?>
						<?php media::print_tag($product->product_id, 'div', 'div', 'goit__table-item__product-шв'); ?>
					<?php else: ?>
						<a href="<?php echo GOIT_PRMCODE()->config['products_crm_URL'] . $product->product_id; ?>"
							class="goit__table-item__product-id" target="_blank">
							<?php echo $product->product_id; ?>
						</a>
					<?php endif; ?>
					<?php media::print_tag($product->unit_price . '₴', 'div', 'div', 'goit__table-item__unit_price'); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="goit__table-footer">
			<div class="counter-elements">
				<?php echo __('Актуально станом на : ') . '<b>' . $last_product_update . '</b>'; ?>
			</div>
		</div>
		<?php GOIT_PRMCODE()->view->load('admin_elements/footer'); ?>
</section>