var CRUD = CRUD || {};

CRUD.Form = CRUD.Form || {

		init: function (form_element_id) {

			CRUD.Form.validator.init(form_element_id);
			CRUD.Form.isNull(form_element_id);
		},

		validator: {
			init: function (form_element_id) {
				var $form = $('#' + form_element_id);
				CRUD.Form.validator.required(form_element_id);
				$form.on('submit', function (e) {
					if (CRUD.Form.validator.check(form_element_id) == true) {
					} else {
						e.preventDefault();
						CRUD.Form.validator.errors(CRUD.Form.validator.check(form_element_id));
					}
				});
			},

			required: function (form_element_id) {
				var $form = $('#' + form_element_id);
				var required_class = 'required-class';
				$form.find('[required]').each(function () {
					var $this = $(this);
					var $field = ($this.data('field')) ? $('#' + $this.data('field')) : $this;
					$this.on('change keyup blur', function () {
						if (CRUD.Form.validator.check(form_element_id, $this) == true) {
							if ($this.attr('type') != 'radio') {
								$field.removeClass(required_class);
							} else {
								var radio_name = $this.attr('name');
								$form.find('[name="' + radio_name + '"]').removeClass(required_class);
							}
						} else {
							if ($this.attr('type') != 'radio') {
								$field.addClass(required_class);
							} else {
								var radio_name = $this.attr('name');
								$form.find('[name="' + radio_name + '"]').addClass(required_class);
							}
						}
					}).trigger('change');
				});
			},

			check: function (form_element_id, $required_elem) {
				var $form = $('#' + form_element_id);
				var $required = $required_elem || '[required]';
				var errors = [];
				$form.find($required).each(function () {
					var $this = $(this);
					if ($this.attr('type') != 'radio') {
						if ($this.val() == '') {
							errors.push($this.attr('name'));
						}
					} else {
						var radio_name = $this.attr('name');
						if ($form.find('[name="' + radio_name + '"]:checked').length == 0) {
							if ($.inArray($this.attr('name'), errors) < 0) {
								errors.push($this.attr('name'));
							}
						}
					}
				});
				if (errors.length == 0) {
					return true;
				} else {
					return errors;
				}
			},

			errors: function (errors) {
				alert('Нужно заполнить поля:\n - ' + errors.join('\n - '));
			}
		},

		isNull: function (form_element_id) {
			var $form = $('#' + form_element_id);
			var default_func_null = function ($this) {
				var field_name = $this.data('nulled-field');
				$('[name="'+field_name+'"]').on('change keydown', function () {
					$this.prop('checked',false);
				});

				$this.on('change', function () {
					if ($(this).is(':checked')) {
						$('[name="'+field_name+'"]').val('').prop('checked',false);
					}
				});
			};

			$form.find('[name$="___is_null"]').each(function () {
				var $this = $(this);
				var func_null_name = $this.data('func-null-name');
				var func_null = window[func_null_name] || default_func_null;

				func_null($this);
			});
		}
	};