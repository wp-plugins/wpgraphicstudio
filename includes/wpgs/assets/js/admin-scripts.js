jQuery(document).ready(function ($) {

	/**
	 * Download Configuration Metabox
	 */
	var WPGS_Download_Configuration = {
		init : function() {
			this.add();
			this.move();
			this.remove();
			this.type();
			this.prices();
			this.files();
		},
		clone_repeatable : function(row) {

			clone = row.clone();

			/** manually update any select box values */
			clone.find( 'select' ).each(function() {
				$( this ).val( row.find( 'select[name="' + $( this ).attr( 'name' ) + '"]' ).val() );
			});

			var count  = row.parent().find( 'tr' ).length - 1;

			clone.removeClass( 'wpgs_add_blank' );

			clone.find( 'td input, td select' ).val( '' );
			clone.find( 'input, select' ).each(function() {
				var name 	= $( this ).attr( 'name' );

				name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');

				$( this ).attr( 'name', name ).attr( 'id', name );
			});
			return clone;
		},

		add : function() {
			$( 'body' ).on( 'click', '.submit .wpgs_add_repeatable', function(e) {
				e.preventDefault();
				var button = $( this ),
				row = button.parent().parent().prev( 'tr' ),
				clone = WPGS_Download_Configuration.clone_repeatable(row);
				clone.insertAfter( row );
			});
		},

		move : function() {
			/*
			* Disabled until we can work out a way to solve an issue
			if( ! $('.wpgs_repeatable_table').length )
				return;

			$(".wpgs_repeatable_table tbody").sortable({
				handle: '.wpgs_draghandle', items: '.wpgs_repeatable_row', opacity: 0.6, cursor: 'move', axis: 'y', update: function() {
					var count  = 0;
					$(this).find( 'tr' ).each(function() {
						$(this).find( 'input, select' ).each(function() {
							var name   = $( this ).attr( 'name' );
							name       = name.replace( /\[(\d+)\]/, '[' + count + ']');
							$( this ).attr( 'name', name ).attr( 'id', name );
						});
						count++;
					});
				}
			});
			*/
		},

		remove : function() {
			$( 'body' ).on( 'click', '.wpgs_remove_repeatable', function(e) {
				e.preventDefault();

				var row   = $(this).parent().parent( 'tr' ),
					count = row.parent().find( 'tr' ).length - 1,
					type  = $(this).data('type'),
					repeatable = 'tr.wpgs_repeatable_' + type + 's';

				if( count > 1 ) {
					$( 'input, select', row ).val( '' );
					row.fadeOut( 'fast' ).remove();
				} else {
					switch( type ) {
						case 'price' :
							alert( wpgs_vars.one_price_min );
							break;
						case 'file' :
							alert( wpgs_vars.one_file_min );
							break;
						default:
							alert( wpgs_vars.one_field_min );
							break;
					}
				}

				/* re-index after deleting */
			    $(repeatable).each( function( rowIndex ) {
			        $(this).find( 'input, select' ).each(function() {
			        	var name = $( this ).attr( 'name' );
			        	name = name.replace( /\[(\d+)\]/, '[' + rowIndex+ ']');
			        	$( this ).attr( 'name', name ).attr( 'id', name );
			    	});
			    });

			});
		},

		type : function() {

			$( 'body' ).on( 'change', '#wpgs_product_type', function(e) {
				$( '#wpgs_download_files' ).toggle();
				$( '#wpgs_products' ).toggle();
				$( '#wpgs_download_limit_wrap' ).toggle();
			});

		},

		prices : function() {
			$( 'body' ).on( 'change', '#wpgs_variable_pricing', function(e) {
				$( '.wpgs_pricing_fields' ).toggle();
				$( '.wpgs_repeatable_condition_field' ).toggle();
				$( '#wpgs_download_files table .pricing' ).toggle();
			});
		},

		files : function() {
			if( typeof wp == "undefined" || wpgs_vars.new_media_ui != '1' ){
				//Old Thickbox uploader
				if ( $( '.wpgs_upload_image_button' ).length > 0 ) {
					window.formfield = '';

					$('body').on('click', '.wpgs_upload_image_button', function(e) {
						e.preventDefault();
						window.formfield = $(this).parent().prev();
						window.tbframe_interval = setInterval(function() {
							jQuery('#TB_iframeContent').contents().find('.savesend .button').val(wpgs_vars.use_this_file).end().find('#insert-gallery, .wp-post-thumbnail').hide();
						}, 2000);
						if (wpgs_vars.post_id != null ) {
							var post_id = 'post_id=' + wpgs_vars.post_id + '&';
						}
						tb_show(wpgs_vars.add_new_download, 'media-upload.php?' + post_id +'TB_iframe=true');
					});

					window.wpgs_send_to_editor = window.send_to_editor;
					window.send_to_editor = function (html) {
						if (window.formfield) {
							imgurl = $('a', '<div>' + html + '</div>').attr('href');
							window.formfield.val(imgurl);
							window.clearInterval(window.tbframe_interval);
							tb_remove();
						} else {
							window.wpgs_send_to_editor(html);
						}
						window.send_to_editor = window.wpgs_send_to_editor;
						window.formfield = '';
						window.imagefield = false;
					}
				}
			} else {
				// WP 3.5+ uploader
				var file_frame;
				window.formfield = '';

				$('body').on('click', '.wpgs_upload_image_button', function(e) {

					e.preventDefault();

					var button = $(this);

					window.formfield = $(this).closest('.wpgs_repeatable_upload_wrapper');

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
						file_frame.open();
					  return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						frame: 'post',
						state: 'insert',
						title: button.data( 'uploader_title' ),
						button: {
							text: button.data( 'uploader_button_text' ),
						},
						multiple: $(this).data('multiple') == '0' ? false : true  // Set to true to allow multiple files to be selected
					});

					file_frame.on( 'menu:render:default', function(view) {
				        // Store our views in an object.
				        var views = {};

				        // Unset default menu items
				        view.unset('library-separator');
				        view.unset('gallery');
				        view.unset('featured-image');
				        view.unset('embed');

				        // Initialize the views in our view object.
				        view.set(views);
				    });

					// When an image is selected, run a callback.
					file_frame.on( 'insert', function() {

						var selection = file_frame.state().get('selection');
						selection.each( function( attachment, index ) {
							attachment = attachment.toJSON();
							if(index == 0){
								// place first attachment in field
								window.formfield.find('.wpgs_repeatable_upload_field').val(attachment.url);
								window.formfield.find('.wpgs_repeatable_name_field').val(attachment.title);
							} else{
								// Create a new row for all additional attachments
								var row = window.formfield,
								clone = WPGS_Download_Configuration.clone_repeatable(row);
								clone.find('.wpgs_repeatable_upload_field').val(attachment.url);
								if(attachment.title.length > 0){
									clone.find('.wpgs_repeatable_name_field').val(attachment.title);
								}else{
									clone.find('.wpgs_repeatable_name_field').val(attachment.filename);
								}
								clone.insertAfter( row );
							}
						});
					});

					// Finally, open the modal
					file_frame.open();
				});


				// WP 3.5+ uploader
				var file_frame;
				window.formfield = '';
			}

		}

	}

	WPGS_Download_Configuration.init();

	//$('#edit-slug-box').remove();

	// Date picker
	if ($('.form-table .wpgs_datepicker').length > 0) {
		var dateFormat = 'mm/dd/yy';
		$('.wpgs_datepicker').datepicker({
			dateFormat: dateFormat
		});
	}

	$('#purchased-downloads').on('click', '.wpgs-remove-purchased-download', function() {
		var $this = $(this);
		data = {
			action: $this.data('action'),
			download_id: $this.data('id')
		};
		$.post(ajaxurl, data, function (response) {
			if (response != 'fail') {
				$('.purchased_download_' + $this.data('id')).remove();
			}
		});
		return false;
	});

	// Add a New Download from the Add Downloads to Purchase Box
	$('#wpgs-add-downloads-to-purchase').on('click', '.wpgs-add-another-download', function() {
		var downloads_select_elem = $('#wpgs-add-downloads-to-purchase select.wpgs-downloads-list:last').parent().clone(),
		    count = $('#wpgs-add-downloads-to-purchase select.wpgs-downloads-list').length,
		    download_section = $('#wpgs-add-downloads-to-purchase select.wpgs-downloads-list:last').parent();

		if (downloads_select_elem.has('select.wpgs-variable-prices-select')) {
			$('select.wpgs-variable-prices-select', downloads_select_elem).remove();
		}

		$(downloads_select_elem).children('select').prop('name', 'downloads[' + count + '][id]');
		downloads_select_elem.insertAfter(download_section);

		return false;
	});

	// On Download Select, Check if Variable Prices Exist
	$('#wpgs-add-downloads-to-purchase').on('change', 'select.wpgs-downloads-list', function() {
		var $el = $(this),
		    download_id = $('option:selected', $el).val(),
		    array_key   = $('#wpgs-add-downloads-to-purchase select').length - 1;

		if (parseInt(download_id) != 0 ) {
			var variable_price_check_ajax_data = {
				action : 'wpgs_check_for_download_price_variations',
				download_id: download_id,
				array_key: array_key,
				nonce: $('#wpgs_add_downloads_to_purchase_nonce').val()
			};
			$('.wpgs_add_download_to_purchase_waiting:last').removeClass('hidden');
			$.post(ajaxurl, variable_price_check_ajax_data, function(response) {
				$el.next('select').remove();
				$el.after(response);
				if( ! $('.wpgs-remove-download', $el.parent()).length && $('#wpgs-add-downloads-to-purchase select.wpgs-downloads-list').length > 1 ) {
					$el.parent().append('&nbsp;<a href="#" class="wpgs-remove-download">' + wpgs_vars.remove_text + '</a>');
				}
				$('.wpgs_add_download_to_purchase_waiting:last').addClass('hidden');
			});
		} else {
			$el.next('select').remove();
			$('.wpgs_add_download_to_purchase_waiting:last').addClass('hidden');
		}
	});

	// Remove a Download Row
	$('#wpgs-add-downloads-to-purchase').on('click', '.wpgs-remove-download', function() {
		$(this).parent().remove();
		return false;
	});

	// When the Add Downloads button is clicked...
	$('#wpgs-add-download').on('click', function() {
		$('#wpgs-add-downloads-to-purchase select.wpgs-downloads-list').each(function() {
			var id = $('option:selected', this).val();

			if ($(this).next().hasClass('wpgs-variable-prices-select')) {
				var variable_price_id = $('option:selected', $(this).next()).val(),
					variable_price_title = $('option:selected', $(this).next()).text(),
				    variable_price_html = '<input type="hidden" name="wpgs-purchased-downloads[' + id + '][options][price_id]" value="' + variable_price_id + '"/> ' + '(' + variable_price_title + ')';
			} else {
				var variable_price_id = '',
				    variable_price_html = '';
			}

			data = {
				action: 'wpgs_get_download_title',
				download_id: id
			};
			$.post(ajaxurl, data, function (response) {
				if (response != 'fail') {
					var html = '<div class="purchased_download_' + id + '"><input type="hidden" name="wpgs-purchased-downloads[' + id + ']" value="' + id + '"/><strong>' + response + variable_price_html + '</strong> - <a href="#" class="wpgs-remove-purchased-download" data-action="remove_purchased_download" data-id="' + id + '">Remove</a></div>';
					$(html).insertBefore('#edit-downloads');
				}
			});
		});
		tb_remove();
		return false;
	});

	// Show / hide the send purchase receipt check box on the Edit payment screen
	$('#wpgs_payment_status').change(function() {
		if( $('#wpgs_payment_status option:selected').val() == 'publish' ) {
			$('#wpgs_payment_notification').slideDown();
		} else {
			$('#wpgs_payment_notification').slideUp();
		}
	});

	$('.download_page_wpgs-payment-history .row-actions .delete a').on('click', function() {
		if( confirm( wpgs_vars.delete_payment ) ) {
			return true;
		}
		return false;
	});

	$('.wpgs-delete-payment-note').on('click', function() {
		if( confirm( wpgs_vars.delete_payment_note) ) {
			return true;
		}
		return false;
	});

	$('#the-list').on('click', '.editinline', function() {
		inlineEditPost.revert();

		var post_id = $(this).closest('tr').attr('id');

		post_id = post_id.replace("post-", "");

		var $wpgs_inline_data = $('#post-' + post_id);

		var regprice = $wpgs_inline_data.find('.column-price .downloadprice-' + post_id).val();

		// If variable priced product disable editing, otherwise allow price changes
		if ( regprice != $('#post-' + post_id + '.column-price .downloadprice-' + post_id).val() ) {
			$('.regprice', '#wpgs-download-data').val(regprice).attr('disabled', false);
		} else {
			$('.regprice', '#wpgs-download-data').val( wpgs_vars.quick_edit_warning ).attr('disabled', 'disabled');
		}
	});

	// Show the email template previews
	if( $('#email-preview-wrap').length ) {
		$('#open-email-preview').colorbox({
			inline: true,
			href: '#email-preview',
			width: '80%',
			height: 'auto'
		});
	}

	// Reporting
	$( '#wpgs-graphs-date-options' ).change( function() {
		var $this = $(this);
		if( $this.val() == 'other' ) {
			$( '#wpgs-date-range-options' ).show();
		} else {
			$( '#wpgs-date-range-options' ).hide();
		}
	});

	// Customer Export
	$( '#wpgs_customer_export_download' ).change( function() {
		var $this = $(this);
		if( $this.val() == '0' ) {
			$( '#wpgs_customer_export_option' ).show();
		} else {
			$( '#wpgs_customer_export_option' ).hide();
		}
	});

	// Update base state field based on selected base country
	$('select[name="wpgs_settings_taxes[base_country]"]').change(function() {
		var $this = $(this), $tr = $this.closest('tr');
		data = {
			action: 'wpgs_get_shop_states',
			country: $(this).val(),
			field_name: 'wpgs_settings_taxes[base_state]'
		};
		$.post(ajaxurl, data, function (response) {
			if( 'nostates' == response ) {
				$tr.next().hide();
			} else {
				$tr.next().show();
				$tr.next().find('select').replaceWith( response );
			}
		});

		return false;
	});

	// Update tax rate state field based on selected rate country
	$('body').on('change', '#wpgs_tax_rates select', function() {
		var $this = $(this);
		data = {
			action: 'wpgs_get_shop_states',
			country: $(this).val(),
			field_name: $this.attr('name').replace('country', 'state')
		};
		$.post(ajaxurl, data, function (response) {
			if( 'nostates' == response ) {
				var text_field = '<input type="text" name="' + data.field_name + '" value=""/>';
				$this.parent().next().find('select').replaceWith( text_field );
			} else {
				$this.parent().next().find('input,select').show();
				$this.parent().next().find('input,select').replaceWith( response );
			}
		});

		return false;
	});

	// Insert new tax rate row
	$('#wpgs_add_tax_rate').on('click', function() {
		var row = $('#wpgs_tax_rates tr:last');
		var clone = row.clone();
		var count = row.parent().find( 'tr' ).length;
		clone.find( 'td input' ).val( '' );
		clone.find( 'input, select' ).each(function() {
			var name = $( this ).attr( 'name' );
			name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');
			$( this ).attr( 'name', name ).attr( 'id', name );
		});
		clone.insertAfter( row );
		return false;
	});

	// Remove tax row
	$('body').on('click', '#wpgs_tax_rates .wpgs_remove_tax_rate', function() {
		if( confirm( wpgs_vars.delete_tax_rate ) )
			$(this).closest('tr').remove();
		return false;
	});

    // Hide Symlink option if Download Method is set to Direct
    if( $('select[name="wpgs_settings_misc[download_method]"]:selected').val() != 'direct' ) {
        $('select[name="wpgs_settings_misc[download_method]"]').parent().parent().next().hide();
        $('select[name="wpgs_settings_misc[download_method]"]').parent().parent().next().find('input').attr('checked', false);
    }
    // Toggle download method option
    $('select[name="wpgs_settings_misc[download_method]"]').on('change', function() {
        var symlink = $(this).parent().parent().next();
        if( $(this).val() == 'direct' ) {
            symlink.hide();
        } else {
            symlink.show();
            symlink.find('input').attr('checked', false);
        }
    });

    // Settings Upload field JS
    if( typeof wp == "undefined" || wpgs_vars.new_media_ui != '1' ){
		//Old Thickbox uploader
		if ( $( '.wpgs_settings_upload_button' ).length > 0 ) {
			window.formfield = '';

			$('body').on('click', '.wpgs_settings_upload_button', function(e) {
				e.preventDefault();
				window.formfield = $(this).parent().prev();
				window.tbframe_interval = setInterval(function() {
					jQuery('#TB_iframeContent').contents().find('.savesend .button').val(wpgs_vars.use_this_file).end().find('#insert-gallery, .wp-post-thumbnail').hide();
				}, 2000);
				tb_show(wpgs_vars.add_new_download, 'media-upload.php?TB_iframe=true');
			});

			window.wpgs_send_to_editor = window.send_to_editor;
			window.send_to_editor = function (html) {
				if (window.formfield) {
					imgurl = $('a', '<div>' + html + '</div>').attr('href');
					window.formfield.val(imgurl);
					window.clearInterval(window.tbframe_interval);
					tb_remove();
				} else {
					window.wpgs_send_to_editor(html);
				}
				window.send_to_editor = window.wpgs_send_to_editor;
				window.formfield = '';
				window.imagefield = false;
			}
		}
	} else {
		// WP 3.5+ uploader
		var file_frame;
		window.formfield = '';

		$('body').on('click', '.wpgs_settings_upload_button', function(e) {

			e.preventDefault();

			var button = $(this);

			window.formfield = $(this).parent().prev();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data( 'uploader_title' ),
				button: {
					text: button.data( 'uploader_button_text' ),
				},
				multiple: false
			});

			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};

		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');

		        // Initialize the views in our view object.
		        view.set(views);
		    });

			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {

				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					window.formfield.val(attachment.url);
				});
			});

			// Finally, open the modal
			file_frame.open();
		});


		// WP 3.5+ uploader
		var file_frame;
		window.formfield = '';
	}

    // Bulk edit save
    $( 'body' ).on( 'click', '#bulk_edit', function() {

		// define the bulk edit row
		var $bulk_row = $( '#bulk-edit' );

		// get the selected post ids that are being edited
		var $post_ids = new Array();
		$bulk_row.find( '#bulk-titles' ).children().each( function() {
			$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});

		// get the stock and price values to save for all the product ID's
		var $price = $( '#wpgs-download-data input[name="_wpgs_regprice"]' ).val();

		var data = {
			action: 		'wpgs_save_bulk_edit',
			wpgs_bulk_nonce:	$post_ids,
			post_ids:		$post_ids,
			price:			$price
		};

		// save the data
		$.post( ajaxurl, data );

	});

    $('.wpgs-select-chosen').chosen();
    if( $('.wpgs-color-picker').length ) {
	    $('.wpgs-color-picker').wpColorPicker();
	}
});
