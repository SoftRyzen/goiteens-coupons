(function ($) {

	class сouponsAdmin {

		constructor() {

			this.searchPromocodesPage();
		}

		searchPromocodesPage() {
			$('#goit-promocodes-search-input').keyup(function () {
				const value = $('#goit-promocodes-search-input').val();
				const form = $('#goit-promocodes-search');
				form.attr('action', `/wp-admin/admin.php?page=goit_promocode&search=${value}`);
			});
		}

	}

	window.сouponsAdmin = new сouponsAdmin();

})(window.jQuery);
