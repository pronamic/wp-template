jQuery(document).ready(function() {
	var mediaMetaBox = {
		config: {
			dom: {}
			, _frame: undefined
		}

		, init: function() {
			this.config.dom.$parent = jQuery('.site-image-parent');
			this.config.dom.$uploadButton = jQuery('.site-image-upload-button');
			this.config.dom.$uploadInput = jQuery('.site-image-upload-id');
			this.config.dom.$uploadPreview = jQuery('.site-image-upload-preview');
			this.config.dom.$uploadRemoveButton = jQuery('.site-image-remove-button');

			this.binds();
		}

		, binds: function() {
			var self = this;

			jQuery('#wpbody').on('click', '.site-image-upload-button', function(e) {
				e.preventDefault();

				self.frame().open();
			});
			
			jQuery('#wpbody').on('click', '.site-image-remove-button', function(e) {
				e.preventDefault();
				var image_id = jQuery(this).data('image-id'),
					value = self.config.dom.$uploadInput.val();
					
				value = value.replace(image_id, '');
				self.config.dom.$uploadInput.val(value);
				self.ajaxIt(value);
			});
		}

		, frame: function() {
			if (this._frame)
				return this._frame;

			var self = this;

			this._frame = wp.media({
				title: self.config.dom.$uploadButton.data('title')
						, library: {
					type: 'image'
				}
				, button: {
					text: self.config.dom.$uploadButton.data('update-text')
				}
				, multiple: true
			});

			this._frame.state('library').on('select', this.select);
			return this._frame;
		}
		
		, select: function() {
			var selection = this.get('selection');
			
			mediaMetaBox.config.dom.$uploadInput.val( selection.pluck('id') );
			mediaMetaBox.ajaxIt(selection.pluck('id'));
		}
		
		, ajaxIt: function(id) {
			if( undefined === id)
				id = null;
			
			var settings = wp.media.view.settings;
			
			wp.media.post( 'site_set_header', {
				json:true,
				post_id: settings.post.id,
				custom_header:id
			}).done(function(html) {
				mediaMetaBox.config.dom.$uploadPreview.html(html);
			});
		}
	};

	mediaMetaBox.init();
});