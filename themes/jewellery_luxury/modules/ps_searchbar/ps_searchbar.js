$(document).ready(function () {
	if( document.body.className.match('ps_171') ) {
		var $searchPlugin = $('#search_plugin');
		var $searchBox    = $searchPlugin.find('input[type=text]');
		var searchURL     = $searchPlugin.attr('data-search-controller-url');
		$.widget('prestashop.psBlockSearchAutocomplete', $.ui.autocomplete, {
			_renderItem: function (ul, product) {
				return $("<li>")	
					.append($("<a>")
						.append($("<span>").addClass("search_prawa")
							.append($("<span>").html(product.manufacturer_name).addClass("search_marka"))
							.append($("<span>").html(product.name).addClass("search_nazwa"))
							.append($("<span>").html(product.price).addClass("search_cena"))
						)
						.append($("<span>").addClass("clearfix"))
					).appendTo(ul)
				;
			}
		});
		$searchBox.psBlockSearchAutocomplete({
			source: function (query, response) {
				$.post(searchURL, {
					s: query.term,
					resultsPerPage: 9
				}, null, 'json')
				.then(function (resp) {
					response(resp.products);
				})
				.fail(response);
			},
			select: function (event, ui) {
				var url = ui.item.url;
				window.location.href = url;
			},
		});

	} 
	
	else {
		var $searchPlugin = $('#search_plugin');
		var $searchBox    = $searchPlugin.find('input[type=text]');
		var searchURL     = $searchPlugin.attr('data-search-controller-url');
		$.widget('prestashop.psBlockSearchAutocomplete', $.ui.autocomplete, {
			_renderItem: function (ul, product) {
				return $("<li>")	
					.append($("<a>")
						.append($("<span>").addClass("search_lewa")
							.append($('<img />').attr('src',product.cover.small.url))
						)

						.append($("<span>").addClass("search_prawa")
							.append($("<span>").html(product.manufacturer_name).addClass("search_marka"))
							.append($("<span>").html(product.name).addClass("search_nazwa"))
							.append($("<span>").html(product.price).addClass("search_cena"))
						)

						.append($("<span>").addClass("clearfix"))
					).appendTo(ul)
				;
			}
		});
		$searchBox.psBlockSearchAutocomplete({
			source: function (query, response) {
				$.post(searchURL, {
					s: query.term,
					resultsPerPage: 9
				}, null, 'json')
				.then(function (resp) {
					response(resp.products);
				})
				.fail(response);
			},
			select: function (event, ui) {
				var url = ui.item.url;
				window.location.href = url;
			},
		});
	}
});