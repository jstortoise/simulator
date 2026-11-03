$(function () {
	function csrfToken() {
		var meta = document.querySelector('meta[name="csrf-token"]');
		return meta ? meta.getAttribute('content') : '';
	}

	$.ajaxSetup({
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-CSRF-Token', csrfToken());
		}
	});

	function withCsrf(data) {
		data.csrf_token = csrfToken();
		return data;
	}

	function parseResponse(res) {
		return typeof res === 'string' ? JSON.parse(res) : res;
	}

	function appendValueItem(value) {
		var span = $('<span class="item" style="margin-right:10px;"></span>');
		span.attr('itemvalue', value);
		span.text(value);
		span.append('<i class="fa fa-times-circle delete_value" style="margin-left:10px;"></i>');
		$('.value_container').append(span);
	}

	function init() {
		$('#medit').find('input').each(function () {
			$(this).val('');
		});

		$('.fieldtype').val('0');
		$('.fieldeditable').val('0');
		$('.add_value_box').css('display', 'none');
		$('.value_container').css('display', 'none');
	}

	function initvaluecontainer(value) {
		if (value == '0') {
			$('.add_value_box').css('display', 'none');
			$('.value_container').css('display', 'none');
		} else {
			$('.add_value_box').css('display', '');
			$('.value_container').css('display', '');
		}
	}

	$('.fieldtype').change(function () {
		var value = $(this).val();

		initvaluecontainer(value);
		$('.value_container').html('');
	});

	$('.add_value').click(function () {
		var value = $('.value_edit').val();

		if (!value) {
			return;
		}
		var value_array = [];
		$('.value_container').find('.item').each(function () {
			value_array.push($(this).attr('itemvalue'));
		});

		if (value_array.indexOf(value) == -1) {
			appendValueItem(value);
		}

		$('.value_edit').val('');
	})

	$('.change').click(function () {
		var fieldid = $(this).attr('attr_id');
		var action = 'Update';
		if (!fieldid) {
			fieldid = $('.field_id').val();
			action = 'Add';
		}

		name = $('.name_edit').val();
		type = $('.fieldtype').val();
		editable = $('.fieldeditable').val();
		unit = $('.unit_edit').val();
		description = $('.description_edit').val();
		value = "";
		if (type == '0') {
			value = $('.value_edit').val();
		} else {
			value = [];
			$('.value_container').find('.item').each(function () {
				value.push($(this).attr('itemvalue'));
			});

			value = value.join(',');
		}

		$.ajax({
			url: 'editsave.php',
			type: 'POST',
			data: withCsrf({
				action: action,
				data: {
					field_label: name,
					field_id: fieldid,
					unit: unit,
					type: type,
					editable: editable,
					description: description,
					value: value
				}
			}),
			success: function (res) {
				res = parseResponse(res);
				if (res.success) {
					window.location.reload();
				}
			}
		});
	});

	$(document).on('click', '.delete_value', function () {
		$(this).parent().remove();
	});

	$('.add_new').click(function () {
		$.ajax({
			url: 'getdata.php',
			type: 'POST',
			data: withCsrf({ action: 'autofieldid' }),
			success: function (res) {
				res = parseResponse(res);
				init();
				$('.field_id').val(res.fieldid);
				$('.change').attr('attr_id', '');
				$('#medit').modal('show');
			}
		});
	});

	$(".save_add").click(function () {
		$.ajax({
			url: 'data.php',
			type: 'post',
			data: withCsrf({ name_add: $('.name_add').val(), value_add: $('.value_add').val(), unit_add: $('.unit_add').val(), type_add: $('.type_add').val(), description_add: $('.description_add').val() }),
			success: function (res) {
				window.location.reload();
			}
		});
	});

	$(document).on("click", ".medit", function () {
		$.ajax({
			url: 'getdata.php',
			type: 'post',
			data: withCsrf({ edit_id: $(this).attr('attr_id') }),
			success: function (res) {
				var data = parseResponse(res);

				init();
				$('.value_container').html('');
				initvaluecontainer(data.type);

				$('.field_id').val(data.field_id);
				$('.name_edit').val(data.field_label);
				$('.unit_edit').val(data.unit);
				$('.fieldtype').val(data.type);
				$('.fieldeditable').val(data.editable);
				$('.description_edit').val(data.description);

				if (data.type == '0') {
					$('.value_edit').val(data.value);
				} else {
					var valuearray = String(data.value || '').split(',');

					for (var itemIndex = 0; itemIndex < valuearray.length; itemIndex++) {
						appendValueItem(valuearray[itemIndex]);
					}
				}

				$('.change').attr('attr_id', data.field_id);
				$('#medit').modal('show');
			}
		});
	});

	$(document).on('click', '.editformula', function () {
		var fieldid = $(this).attr('attr_id');
		$('.save_formula').attr('fieldid', fieldid);

		$.ajax({
			url: 'getdata.php',
			type: 'POST',
			data: withCsrf({ action: 'getformular', fieldid: fieldid }),
			success: function (res) {
				if (res) {
					res = parseResponse(res);
					if (res && res.formula) {
						$('.formula_desc').val(res.formula);
					} else {
						$('.formula_desc').val("");
					}
				} else {
					$('.formula_desc').val("");
				}

				$('#formula').modal('show');
			}
		});
	});

	$('.save_formula').click(function () {
		var fieldid = $(this).attr('fieldid');
		var formula = $('.formula_desc').val();

		$.ajax({
			url: 'editsave.php',
			type: 'POST',
			data: withCsrf({ action: 'updateformula', field_id: fieldid, formula: formula }),
			success: function (res) {
				res = parseResponse(res);
				if (res.success) {
					$('#formula').modal('hide');
				} else if (res.error) {
					window.alert(res.error);
				}
			}
		});
	});

	$('.delete').click(function () {
		var id = $(this).attr('attr_id');
		$('.delete_save').attr('attr_id', id);
	});

	$('.delete_save').click(function () {
		$.ajax({
			url: 'delete.php',
			type: 'post',
			data: withCsrf({ delete_id: $(this).attr('attr_id') }),
			success: function (res) {
				window.location.reload();
			}
		});
	});
});
