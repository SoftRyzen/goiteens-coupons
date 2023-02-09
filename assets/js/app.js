(function ($) {

	class promocodesAdmin {

		constructor() {

			this.setupPromocodesPage();
			this.setupPages();
			this.setupDatepiker();
			this.setupSettings();
			this.setupCheckPromocode();
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
					goitPluginVars.adminURL,
					data,
					function (response) {
						location.reload();
					}
				);
			});
		}

		setupPages() {
			const
				$body = $('.goit__body'),
				$selectedProducts = $('.goit__body .selected-products'),
				$addButton = $('.goit__body #add_promocode'),
				$updateButton = $('.goit__body #update_promocode'),
				// All inputs
				$input = $('.goit__body input'),
				$textarea = $('.goit__body textarea'),
				// Inputs
				$promocod = $('.goit__body input#promocod'),
				$count = $('.goit__body input#count'),
				$activete_count_user = $('.goit__body input#activete_count_user'),
				$product = $('.goit__body input#products'),
				$productSelect = $('.goit__body select#product'),
				$conditions = $('.goit__body textarea#conditions'),
				$date_start = $('.goit__body input#date_start'),
				$date_end = $('.goit__body input#date_end'),
				$manager = $('.goit__body input#manager'),
				$status = $('.goit__body select#status'),
				$promocode_limit = $('.goit__body input#promocode_limit'),
				$amount_surcharge = $('.goit__body input#amount_surcharge'),
				$discount_tariff = $('.goit__body select#discount_tariff'),
				$msg_success = $('.goit__body textarea#msg_success'),
				$msg_not_found = $('.goit__body textarea#msg_not_found'),
				$msg_data_end = $('.goit__body textarea#msg_data_end'),
				// Tariff blocks
				$tariffSelect = $('.goit__body select#tariff'),
				$tariffBTN = $('.goit__body #add_tariff'),
				$selectedTariff = $('.goit__body #selected-tariff .selected-tariff');

			let allTariffs = [];

			/**
			 * It changes the value of the input field to the value of the input field.
			 */

			let typingTimer;                //timer identifier
			let doneTypingInterval = 2000;  //time in ms, 5 seconds for example

			//on keyup, start the countdown
			$promocod.on('keyup', function () {
				$addButton.attr('disabled', 'disabled');
				$promocod.removeClass('success');
				$promocod.removeClass('error');
				$promocod.addClass('in-progress');
				clearTimeout(typingTimer);
				typingTimer = setTimeout(ajaxPromocodeCheck, doneTypingInterval);
			});
			$promocod.on('keydown', function () {
				clearTimeout(typingTimer);
			});

			//user is "finished typing," do something
			const ajaxPromocodeCheck = function () {
				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data: {
						'action': 'check_promocode_name',
						'nonce': goitPluginVars.ajaxNonce,
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

			const changeValue = function () {
				const value = $(this).val();
				$(this).attr('value', value);
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
							let name = item.split('&id=')[0],
								ID = item.split('&id=')[1];
							allProducts = [...allProducts, ID];
							return `<a href="${goitPluginVars.product}&id=${ID}" target="_blank">${name}</a>`;
						}
						).join('')
					);
					$product.attr('value', allProducts);

				}
			});
			$count.keyup(ajaxPromocodeCheck);
			$count.bind('keyup mouseup', ajaxPromocodeCheck);

			/* This is a function that adds a click event to the button and sends a request to the server to add
			a new item. */
			$addButton.click(function (e) {
				e.preventDefault();

				$input.removeClass('error');
				$textarea.removeClass('error');

				if ($promocod.val() == '') {
					$promocod.addClass('error');
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
					$product.val() == '' ||
					$date_start.val() == '' ||
					$promocode_limit.val() == '' ||
					$amount_surcharge.val() == '' ||
					$count.val() == '') { return; }

				$body.addClass('in-progress');
				$addButton.attr('disabled', 'disabled');

				let get, data = {
					'action': 'add_promocodes',
					'nonce': goitPluginVars.ajaxNonce,
					'promocod': $promocod.val().toUpperCase(),
					'activete_count_user': $activete_count_user.val(),
					'product': $product.val(),
					'tariff': allTariffs,
					'conditions': $conditions.val(),
					'date_start': $date_start.val(),
					'date_end': $date_end.val(),
					'manager': $manager.val(),
					'status': $status.val(),
					'promocode_limit': $promocode_limit.val(),
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

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data,
					success: function (response) {
						window.location.replace(goitPluginVars.postURL + get);
					}
				});

			});

			/* Sending an AJAX request to the server. */
			$updateButton.click(function (e) {
				e.preventDefault();

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
				$updateButton.attr('disabled', 'disabled');

				let get, data = {
					'action': 'update_promocode',
					'nonce': goitPluginVars.ajaxNonce,
					'promocod': $promocod.val().toUpperCase(),
					'tariff': allTariffs,
					'activete_count_user': $activete_count_user.val(),
					'product': $product.val(),
					'conditions': $conditions.val(),
					'date_start': $date_start.val(),
					'date_end': $date_end.val(),
					'status': $status.val(),
					'promocode_limit': $promocode_limit.val(),
					'count': $count.val(),
					'msg_success': $msg_success.val(),
					'msg_not_found': $msg_not_found.val(),
					'msg_data_end': $msg_data_end.val(),
				}

				if ($count.val() == 1) {
					get = `&id=${$promocod.val()}&action=updated`;
				} else {
					get = `&group=${$promocod.val()}&action=updated`;
				}

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data,
					success: function (response) {
						window.location.replace(goitPluginVars.postURL + get);
					}
				});

			});

			$tariffBTN.click(function (e) {
				e.preventDefault();

				if ($tariffBTN.attr('disabled') == 'disabled')
					return;

				let tariff = $tariffSelect.val(),
					amount_surcharge = $amount_surcharge.val(),
					discount_tariff = $discount_tariff.val(),
					block, months, symbol;

				/* Setting the symbol variable to the value of the discount_tariff variable. */
				if (discount_tariff == 'UAH')
					symbol = "₴";
				else if (discount_tariff == 'USD')
					symbol = "$";
				else if (discount_tariff == 'percent')
					symbol = "%";
				else symbol = discount_tariff;

				/* Checking the value of the variable "tariff" and then assigning a value to the variable "months"
				based on the value of "tariff". */
				if (tariff == 1) {
					months = tariff + ' місяць';
				} else if (tariff > 1 && tariff < 5) {
					months = tariff + ' місяці';
				} else {
					months = tariff + ' місяців';
				}

				$(`.goit__body select#tariff option[value="${$tariffSelect.val()}"]`).addClass('hidden');

				/* Creating a block of HTML code and adding it to the page. */
				block = `<div class="tariff" style="order: ${tariff}">${months} - знижка ${amount_surcharge + symbol}<span class="remove" data-value="${tariff}"></span>`;
				block += `<input type="hidden" id="tariff_${tariff}" value="${tariff}-${amount_surcharge} ${discount_tariff}"/></div>`;

				allTariffs.push([tariff, amount_surcharge, discount_tariff]);

				$selectedTariff.append(block);
				$tariffBTN.attr('disabled', 'disabled');

			});

			$tariffSelect.change(function () {
				$tariffBTN.attr('disabled', false);
			});

			$selectedTariff.on('click', '.remove', function () {
				let value = $(this).data('value');
				$(this).parent().remove();
				$(`.goit__body select#tariff option[value="${value}"]`).removeClass('hidden');
				if ($(`.goit__body select#tariff option:selected`).val() == value)
					$tariffBTN.attr('disabled', false);
			});

			/**
			 * If the value of the discount tariff is equal to percent, then set the max value of the amount
			 * surcharge to 100 and the min value to 0, and set the value of the amount surcharge to 0. Otherwise,
			 * set the max value of the amount surcharge to false and the min value to 0, and set the value of the
			 * amount surcharge to 0.
			 */
			$discount_tariff.change(maxValue);

			const maxValue = function () {

				if ($discount_tariff.val() == 'percent') {
					$amount_surcharge.attr('max', 100);
					$amount_surcharge.attr('min', 1);
					$amount_surcharge.val(1);
				} else {
					$amount_surcharge.attr('max', false);
					$amount_surcharge.attr('min', 1);
					$amount_surcharge.val(1);
				}

			};

			/* Calling the function maxValue() */
			maxValue();

			/* Checking if the value of the discount_tariff is percent and if the value of the amount_surcharge
			is greater than 100. If it is, it sets the value of the amount_surcharge to 100. */
			$amount_surcharge.keyup(function () {
				if ($discount_tariff.val() == 'percent') {
					if ($amount_surcharge.val() > 100) {
						$amount_surcharge.val(100);
					}
				}
			});

		}


		setupDatepiker() {

			const $results = $('#orders_result'),
				$bodyStatistic = $('.goit-statistic__table-body');

			let today = moment().format('D/M/YYYY'),
				yesterday = moment().subtract(1, 'days').format('D/M/YYYY'),
				week = moment().subtract(7, 'days').format('D/M/YYYY'),
				day30 = moment().subtract(30, 'days').format('D/M/YYYY');

			$('#datepicker').daterangepicker({
				opens: 'right',
				"ranges": {
					"Сьогодні": [
						today,
						today
					],
					"Вчора": [
						yesterday,
						yesterday
					],
					"Остані 7 Днів": [
						week,
						today
					],
					"Остані 30 Днів": [
						day30,
						today
					]
				},
				"alwaysShowCalendars": true,
				"startDate": week,
				"endDate": today
			}, function (start, end) {

				console.log("Нова дата фільтрації: " + start.format('D/M/YYYY') + ' - ' + end.format('D/M/YYYY'));

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data: {
						'action': 'statistic_datepicker',
						'nonce': goitPluginVars.ajaxNonce,
						'date_start': start.format('YYYY.M.D'),
						'date_end': end.format('YYYY.M.D'),
					},
					beforeSend: function () {

						$('.loader').removeClass('hidden');
						$results.addClass('is-loading');
						$bodyStatistic.addClass('is-loading');

					},
					success: function (answer) {

						$('.loader').addClass('hidden');
						$results.removeClass('is-loading');
						$bodyStatistic.removeClass('is-loading');

						$results.html(answer.orders_result);
						$bodyStatistic.html(answer.body_result);

					}
				});
			});
		}

		setupSettings() {

			const
				$settings = $('#settings'),
				$btnUpdate = $('#settings_update'),
				$btnSyncProducts = $('#zoho_products_update'),
				$btnResetDb = $('#rebuild_db'),
				// Inputs
				$zoho_client_id = $('#settings input#zoho_client_id'),
				$zoho_client_secret = $('#settings input#zoho_client_secret'),
				$zoho_refresh_token = $('#settings input#zoho_refresh_token'),
				$w4p_token = $('#settings input#w4p_token'),
				$products_crm_url = $('#settings input#products_crm_url');


			$btnUpdate.click(function (e) {
				e.preventDefault();

				let data = {
					'action': 'update_config',
					'nonce': goitPluginVars.ajaxNonce,
					'ZOHO_CLIENT_ID': $zoho_client_id.val(),
					'ZOHO_CLIENT_SECRET': $zoho_client_secret.val(),
					'ZOHO_REFRESH_TOKEN': $zoho_refresh_token.val(),
					'W4P_TOKEN': $w4p_token.val(),
					'products_crm_URL': $products_crm_url.val(),
				}

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data,
					beforeSend: function () {

						$('.loader').removeClass('hidden');
						$settings.addClass('is-loading');

					},
					success: function (response) {

						if (response) {

							// timeout 3 second for file update
							setTimeout(function () {

								window.location.replace(goitPluginVars.settings + '&action=updated');

							}, 3000);
						}

					}
				});
			});

			$btnSyncProducts.click(function (e) {
				e.preventDefault();

				let data = {
					'action': 'sync_crm_products',
					'nonce': goitPluginVars.ajaxNonce,
				}

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data,
					beforeSend: function () {

						$('.loader').removeClass('hidden');
						$settings.addClass('is-loading');

					},
					success: function (response) {

						window.location.replace(goitPluginVars.product);

					}
				});
			});

			$btnResetDb.click(function (e) {
				e.preventDefault();

				let data = {
					'action': 'rebuild_db',
					'nonce': goitPluginVars.ajaxNonce,
				}

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data,
					beforeSend: function () {

						$('.loader').removeClass('hidden');
						$settings.addClass('is-loading');

					},
					success: function (response) {

						window.location.replace(goitPluginVars.settings + '&action=db');

					}
				});
			});
		}

		setupCheckPromocode() {

			/*
			 * Must have be anywhere: 
			 *
			 * input:text #goit-promocode__input
			 * input:submit OR link #goit-promocode__activate
			 * div OR span #goit-promocode__msg
			 * 
			 */

			const $promocodeInput = $('#goit-promocode__input'),
				$promocodeButton = $('#goit-promocode__activate'),
				$msgBlock = $('#goit-promocode__msg'),
				$promocodeProduct = $promocodeInput.data('product');


			$promocodeButton.click(function (e) {
				e.preventDefault();

				$msgBlock.removeClass('success');
				$msgBlock.removeClass('error');
				$promocodeInput.removeClass('error');

				if ($promocodeInput.val().length == '') {
					$promocodeInput.addClass('error');
					return;
				}

				$.ajax({
					url: goitPluginVars.ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data: {
						'action': 'check_promocode',
						'nonce': goitPluginVars.ajaxNonce,
						'promocode': $promocodeInput.val(),
						'product': $promocodeProduct,
					},
					beforeSend: function () {

						$promocodeButton.parent().addClass('is-loading');

					},
					success: function (answer) {

						$promocodeButton.parent().removeClass('is-loading');

						if (answer.status) {
							$msgBlock.addClass('success');
						} else {
							$msgBlock.addClass('error');
						}

						$msgBlock.html(answer.msg);

					}
				});


			});

		}
	}

	window.promocodesAdmin = new promocodesAdmin();

})(window.jQuery);
