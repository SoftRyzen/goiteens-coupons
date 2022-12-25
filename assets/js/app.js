(function ($) {

	class promocodesAdmin {

		constructor() {

			this.setupAdminPanel()
			this.setupPromocodesPage()
			this.setupAddPage()
		}

		setupAdminPanel() {

			document.readyState
			const
				promocode = $('#toplevel_page_goit_promocode'),
				promocode_link = $('#toplevel_page_goit_promocode > a');

			promocode_link.removeClass('wp-not-current-submenu');
			promocode_link.addClass('wp-has-current-submenu');
			promocode.removeClass('wp-not-current-submenu');
			promocode.addClass('wp-has-current-submenu');
			promocode.addClass('wp-menu-open');
		}

		setupPromocodesPage() {

			/**
			 * "When the user types in the search input, the value of the input is taken and added to the action
			 * attribute of the form." <code>
			 */
			const searchAction = function () {
				const value = $('#goit-promocodes-search-input').val(),
					form = $('#goit-promocodes-search');
				form.attr('action', `/wp-admin/admin.php?page=goit_promocode&search=${value}`);
			}
			$('#goit-promocodes-search-input').keyup(searchAction);
			$('#goit-promocodes-search-input').click(searchAction);

			/**
			 * It's a function that adds a click event to the tabs and displays the appropriate items based on
			 * the status of the tab.
			 */
			const $tableTabs = $('.goit-promocodes__table-tabs > a'),
				$tableNotFound = $('.goit-promocodes__table-body .not_found'),
				$activeItems = $('.goit-promocodes__table-body > *[data-status="active"]'),
				$inactiveItems = $('.goit-promocodes__table-body > *[data-status="inactive"]'),
				$actionButton = $('.goit-promocodes__table-body *[data-new-status]');

			$tableTabs.click(function (e) {
				e.preventDefault();

				const $this = $(this),
					$status = $this.data('status');

				/* Adding a class of hidden to the not_found class and removing the active class from the tabs and
				adding the active class to the current tab. */
				$tableNotFound.addClass('hidden');
				$tableTabs.removeClass('active');
				$this.addClass('active');
				/* This is a conditional statement that checks the status of the tab 
				and displays the appropriate items. */
				if ($status == 'all') {
					$activeItems.removeClass('hidden');
					$inactiveItems.removeClass('hidden');
				} else if ($status == 'active') {
					$activeItems.removeClass('hidden');
					$inactiveItems.addClass('hidden');
					if ($activeItems.length == 0)
						$tableNotFound.removeClass('hidden');
				} else if ($status == 'inactive') {
					$inactiveItems.removeClass('hidden');
					$activeItems.addClass('hidden');
					if ($inactiveItems.length == 0)
						$tableNotFound.removeClass('hidden');
				}
			});


			/* This is a function that adds a click event to the buttons and sends a request to the server to
			change the status of the item. */
			$actionButton.click(function (e) {
				e.preventDefault();

				let status = $(this).data('new-status');
				if (status == 'active') status = 2;
				else status = 1;
				console.log($(this).parent().parent().parent());
				$(this).parent().parent().parent().addClass('in-progress');
				let data = {
					'type': $(this).data('type'),
					'id': $(this).data('id'),
					'status': status
				}

				$.post(
					pluginVars.adminURL,
					data,
					function (response) {
						location.reload();
					}
				);
			});
		}

		setupAddPage() {
			const
				$body = $('.goit-add__body '),
				$selectedProducts = $('.goit-add__body .selected-products'),
				$addButton = $('.goit-add__body #add_promocode'),
				// All inputs
				$input = $('.goit-add__body input'),
				$textarea = $('.goit-add__body textarea'),
				// Inputs
				$promocod = $('.goit-add__body input#promocod'),
				$count = $('.goit-add__body input#count'),
				$activete_count_user = $('.goit-add__body input#activete_count_user'),
				$product = $('.goit-add__body input#products'),
				$productSelect = $('.goit-add__body select#product'),
				$conditions = $('.goit-add__body textarea#conditions'),
				$tariff = $('.goit-add__body select#tariff'),
				$date_start = $('.goit-add__body input#date_start'),
				$date_end = $('.goit-add__body input#date_end'),
				$manager = $('.goit-add__body input#manager'),
				$status = $('.goit-add__body select#status'),
				$promocode_limit = $('.goit-add__body input#promocode_limit'),
				$amount_surcharge = $('.goit-add__body input#amount_surcharge'),
				$discount_tariff = $('.goit-add__body select#discount_tariff'),
				$msg_success = $('.goit-add__body textarea#msg_success'),
				$msg_not_found = $('.goit-add__body textarea#msg_not_found'),
				$msg_data_end = $('.goit-add__body textarea#msg_data_end');

			/**
			 * It changes the value of the input field to the value of the input field.
			 */
			const changeValue = function () {
				const value = $(this).val();
				$(this).attr('value', value);
			}
			const ajaxPromocodeCheck = function () {
				$.ajax({
					url: pluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data: {
						'action': 'ajax_check_promocode_name',
						'promocod': $promocod.val(),
						'count': $count.val()
					},
					beforeSend: function () {
						$addButton.attr('disabled', 'disabled');
						$promocod.removeClass('success');
						$promocod.removeClass('error');
						$promocod.addClass('in-progress');
					},
					success: function (count) {
						console.log(count);

						$promocod.removeClass('in-progress');

						if (count == 0) {
							$promocod.removeClass('error');
							$promocod.addClass('success');
							$addButton.attr('disabled', false);
						} else {
							$promocod.removeClass('success');
							$promocod.addClass('error');
							$addButton.attr('disabled', 'disabled');
						}
					}
				});
			}

			$input.keyup(changeValue);
			$textarea.keyup(changeValue);

			$productSelect.change(function () {
				const $this = $(this),
					productVal = $this.val();

				if ($productSelect.val() !== '') {
					$productSelect.removeClass('error');
				}

				if (productVal) {
					let allProducts = [];
					$selectedProducts.html(
						productVal.map(item => {
							let url = item.split('?id=')[0],
								ID = item.split('&name=')[0].split('?id=')[1],
								name = item.split('&name=')[1];
							allProducts = [...allProducts, ID];
							return `<a href="${url}" target="_blank">${name}</a>`;
						}
						).join('')
					);
					$product.attr('value', allProducts);

				}
			});

			$promocod.keyup(ajaxPromocodeCheck);
			$count.keyup(ajaxPromocodeCheck);
			$count.bind('keyup mouseup', ajaxPromocodeCheck);

			/* This is a function that adds a click event to the button and sends a request to the server to add
			a new item. */
			$addButton.click(function () {

				$input.removeClass('error');
				$textarea.removeClass('error');

				if ($promocod.val() == '') {
					$promocod.addClass('error');
				}
				if ($activete_count_user.val() == '') {
					$activete_count_user.addClass('error');
				}
				if ($product.val() == '') {
					$productSelect.addClass('error');
				}
				if ($date_start.val() == '') {
					$date_start.addClass('error');
				}
				if ($promocode_limit.val() == '') {
					$promocode_limit.addClass('error');
				}
				if ($amount_surcharge.val() == '') {
					$amount_surcharge.addClass('error');
				}
				if ($count.val() == '') {
					$count.addClass('error');
				}

				if ($promocod.val() == '' ||
					$activete_count_user.val() == '' ||
					$product.val() == '' ||
					$date_start.val() == '' ||
					$promocode_limit.val() == '' ||
					$amount_surcharge.val() == '' ||
					$count.val() == '') { return; }

				$body.addClass('in-progress');
				$addButton.attr('disabled', 'disabled');

				let get, data = {
					'action': 'add',
					'promocod': $promocod.val().toUpperCase(),
					'activete_count_user': $activete_count_user.val(),
					'product': $product.val(),
					'tariff': $tariff.val(),
					'conditions': $conditions.val(),
					'date_start': $date_start.val(),
					'date_end': $date_end.val(),
					'manager': $manager.val(),
					'status': $status.val(),
					'promocode_limit': $promocode_limit.val(),
					'amount_surcharge': $amount_surcharge.val(),
					'discount_tariff': $discount_tariff.val(),
					'msg_success': $msg_success.val(),
					'msg_not_found': $msg_not_found.val(),
					'msg_data_end': $msg_data_end.val(),
					'count': $count.val()
				}

				if ($count.val() == 1) {
					get = `&id=${$promocod.val()}&action=new_add`;
				} else {
					get = `&group=${$promocod.val()}&action=new_add`;
				}

				$.post(
					pluginVars.postURL,
					data,
					function (response) {
						window.location.replace(pluginVars.postURL + get);
					}
				);
			});

		}

	}

	window.promocodesAdmin = new promocodesAdmin();

})(window.jQuery);
