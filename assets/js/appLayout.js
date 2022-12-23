(function($){

	class BilberrryAdminLayout {

		constructor() {

			this.setupLayout();
			this.expItems();
			this.progressBarSetup();
			this.tabsSetup();
			this.addSelectType();
		}

		/**
		 * Setup layout
		 */
		setupLayout() {



		}
		/**
		 * Progress bar layout
		 */
		progressBarSetup() {
			// Progress bar basic functional
			$('.progress').each(function (){
				let bar = $(this).find('.progress-bar');
				let val = bar.attr('data-progress');
				bar.css("width", val+'%');
			});
		}

		/**
		 * Setup Export items layout
		 */
		expItems() {
			// Show custom items count input
			$('body').on('change', 'select[name="items-show-count"]', function (e){
				let val = $(this).val();
				if(val == 'custom'){
					$('form[name="custom-items-count"]').addClass('is-active');
				}else{
					$('form[name="custom-items-count"]').removeClass('is-active');
				}
			});

			// Highlight selected item
			$('body').on('click', '.bb-imp__exp-item td', function (e){
				$(this).parent('.bb-imp__exp-item').toggleClass('is-active');
			});
			$('body').on('click', '.bb-imp__exp-grid-item', function (e){
				$(this).toggleClass('is-active');
			});

			// Highlight all items
			$('body').on('click', '.select-all' ,function (e){
				e.preventDefault();
				if($('.bb-imp__exp-items--main .row').hasClass('is-active')){
					if($(this).hasClass('is-active')){
						$(this).removeClass('is-active');
						$('.bb-imp__exp-item').each(function (){
							$(this).removeClass('is-active');
						})
					}else{
						$(this).addClass('is-active');
						$('.bb-imp__exp-item').each(function (){
							$(this).addClass('is-active');
						})
					}
				}else{
					if($(this).hasClass('is-active')){
						$(this).removeClass('is-active');
						$('.bb-imp__exp-grid-item').each(function (){
							$(this).removeClass('is-active');
						})
					}else{
						$(this).addClass('is-active');
						$('.bb-imp__exp-grid-item').each(function (){
							$(this).addClass('is-active');
						})
					}
				}
			});

			// Change export items grid layout
			$('body').on('click', '.row-layout' ,function (e){
				e.preventDefault();
				$('.grid-layout').removeClass('is-active');
				$('.bb-imp__exp-items--main .row').addClass('is-active');
				$(this).addClass('is-active');
				$('.bb-imp__exp-items--main .grid').removeClass('is-active');
			});
			$('body').on('click', '.grid-layout' ,function (e){
				e.preventDefault();
				$('.row-layout').removeClass('is-active');
				$('.bb-imp__exp-items--main .grid').addClass('is-active');
				$(this).addClass('is-active');
				$('.bb-imp__exp-items--main .row').removeClass('is-active');
			});
		}


		/**
		 * Setup tabs functionality
		 */
		tabsSetup() {
			$('.bb-imp__history--top--tabs li a').click(function (e){
				e.preventDefault();
				let dest = $(this).attr('href');
				$('.bb-imp__history--top--tabs li a').removeClass('is-active');
				$(this).addClass('is-active');
				$('.bb-imp__history--main').removeClass('is-active');
				$('.bb-imp__history--main'+dest).addClass('is-active');
			});
		}

		/**
		 * Setup Select type
		 */
		addSelectType() {
			// Clone one more select
			$('.btn-add-type').click(function (e){
				let max = $(this).attr('data-max');
				let curr = $(this).attr('data-curr');
				if(max === curr){
					$(this).attr('disabled', true);
					return;
				}
				e.preventDefault();
				let clonedWrap = $('.bb-imp__select-block--top--dropdowns .drop-down-wrap').clone(true).addClass('cloned');
				if(clonedWrap.length >  1){
					clonedWrap =  clonedWrap[0];
				}
				$('.bb-imp__select-block--top--dropdowns').append(clonedWrap);
				curr++;
				$(this).attr('data-curr', curr);
			});

			// Remove cloned select
			$('.drop-down-wrap .remove').on('click', function (e){
				$(this).parent('.drop-down-wrap').remove();
				let curr = $('.btn-add-type').attr('data-curr');
				curr--;
				$('.btn-add-type').attr('data-curr', curr);
			});
		}
	}

	window.BilberrryAdminLayout = new BilberrryAdminLayout();

})( window.jQuery );
