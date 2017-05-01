$('i.glyphicon-refresh-animate').hide();
function updateItems(r) {
	_opts.items.available = r.available;
	_opts.items.assigned = r.assigned;
	search('available');
	search('assigned');
}

$('.btn-assign').click(function () {
	var $this = $(this);
	var target = $this.data('target');
	var items = $('select.list[data-target="' + target + '"]').val();

	if (items && items.length) {
		$this.children('i.glyphicon-refresh-animate').show();
		$.post($this.attr('href'), {items: items}, function (r) {
			updateItems(r);
		}).always(function () {
			$this.children('i.glyphicon-refresh-animate').hide();
		});
	}
	return false;
});

$('.search[data-target]').keyup(function () {
	search($(this).data('target'));
});

function search(target) {
	var $list = $('select.list[data-target="' + target + '"]');
	$list.html('');
	var q = $('.search[data-target="' + target + '"]').val().toLowerCase();

	var groups = {
		1: [$('<optgroup label="' + lajax.t('Roles', false) + '">'), false],
		2: [$('<optgroup label="' + lajax.t('Permissions', false) + '">'), false]
	};
	$.each(_opts.items[target], function (index, value) {
		if (value.name_t.toLowerCase().indexOf(q) >= 0) {
			$('<option>').text(value.name_t).val(index).appendTo(groups[value.type][0]);
			groups[value.type][1] = true;
		}
	});
	$.each(groups, function () {
		if (this[1]) {
			$list.append(this[0]);
		}
	});
}

// initial
search('available');
search('assigned');
