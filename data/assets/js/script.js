(function ($) {

	$('body').on('click', '.btnDelete', function (e) {
		e.preventDefault();

		let articleID = $(this).attr('attrArticleID'),
			data = {
				onDelete: 'onDeleteOpt',
				ArticleID: articleID
			};

		$.post('index.php', { data }, function (data, status) {
			console.log("Data: " + data + "\nStatus: " + status);

			location.reload();
		});
	});



	$('body').on('click', '.btnView', function (e) {
		e.preventDefault();
		$(".articleView").removeClass("hidden");
		$(".article__update").addClass("hidden");

		let articleID = $(this).attr('attrArticleID'),
			data = {
				onViewArticle: 'onViewArticle',
				ArticleID: articleID
			};

		$.post('index.php', { data }, function (data, status) {
			$('.articleView .article__view tbody').html(data);
		});
	});

	$('body').on('click', '.btnEdit', function (e) {
		e.preventDefault();
		$(".articleView").addClass("hidden");
		$(".article__update").removeClass("hidden");

		let articleID = $(this).attr('attrArticleID'),
			data = {
				onViewArticle: 'onViewUpdate',
				ArticleID: articleID
			};

		$.post('index.php', { data }, function (data, status) {
			let articles = JSON.parse(data);

			$('.article__update #txtArticleID').val(articles.data.ArticleID);
			$('.article__update #txtPostTitle').val(articles.data.Title);
			$('.article__update #txtExcerpt').val(articles.data.Excerpt);
			$('.article__update #txtDescription').val(articles.data.Description);

		});

	});

	function search() {
		let keywords = $("#txtSearch").val(),
			data = {
				onSearch: 'onSearch',
				keywords: `${keywords}`.trim()
			};

		$.post('index.php', { data }, function (data, status) {
			$('#articles tbody').html(data);
		});
	}

	$('body').on('keypress', '#txtSearch', function (e) {
		if (e.key !== "Enter") return;
		e.preventDefault();

		search();
	});

	$('body').on('click', '.btnSearch', function (e) {
		e.preventDefault();

		search();
	});

	$('body').on("click", "#logout", function (e) {
		const data = {
			onLogout: 'onLogout',
		};
		$.post('index.php', { data }, function (data, status) {
			location.reload(true);
		});
	});


})(jQuery);
