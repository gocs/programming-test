<?php 
	session_start();
	require_once( 'inc/db.php' );
	require_once( 'inc/functions.php' );
	
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="assets/css/normalize.css">
		<link rel="stylesheet" href="assets/js/tailwind.js">
		<script src="assets/js/tailwind.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
		<script>
		function addDarkmodeWidget() {
			new Darkmode({
			time: '0.5s', // default: '0.3s'
			mixColor: '#fff', // default: '#fff'
			backgroundColor: '#fff',  // default: '#fff'
			buttonColorDark: '#100f2c',  // default: '#100f2c'
			buttonColorLight: '#fff', // default: '#fff'
			label: '🌓', // default: ''
			autoMatchOsTheme: true // default: true
			}).showWidget();
		}
		window.addEventListener('load', addDarkmodeWidget);
		</script>
	</head>
	<body class="h-screen">
		<?php 
			if( ! isset( $_SESSION['userID'] ) ):
		?>
			<section id = "login_form" class = "login-container">
				<div class = "login-container__inner w-[600px] mx-auto">
					<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "POST" class = "field-container mt-16">
						<div class = "field flex flex-col bg-green-400/80 py-20 gap-2 rounded">
							<h3 class="text-3xl font-extrabold m-auto pb-10">Login to Articleator</h3>
							<label for = "txtUsername" class="m-auto">
								Username:
								<input type = "text" id = "txtUsername" name = "txtUsername" placeholder = "Username" class = "field-text field-username border rounded-md p-2">
							</label>
							<label for = "txtPwd" class="m-auto">
								Password:
								<input type = "password" id = "txtPwd" name = "txtPwd" placeholder = "Password" class = "field-text field-pwd border rounded-md p-2">
							</label>
							
							<input type = "submit" name = "btnLogin" class = "btn btnLogin darkmode-ignore m-auto bg-blue-400/80 text-white py-2 px-10 rounded cursor-pointer" value = "Login">
						</div>
					</form>
				</div>			
			</section><!-- end of Login -->
		<?php else: ?>
			<section id = "login_form" class = "login-container flex flex-col bg-red-600/80 text-white">
				<div class = "login-container__inner w-full flex justify-between">
					<div class="px-5 py-3 flex">
						<div id="formSearch" class="flex gap-2">
							<input type="search" id="txtSearch" class="w-full border rounded px-3" placeholder="Search..">
							<input type="button" value="Search" class="btnSearch darkmode-ignore rounded bg-green-400/80 px-3 text-white cursor-pointer">
						</div>
					</div>
					<div class = "field flex p-1 items-center">
						<div class="flex flex-col">
							<label for = "txtUsername" class=" darkmode-ignore">
								Username: <?php echo $_SESSION['userName']; ?> 
							</label>
							<label for = "txtFullName" class=" darkmode-ignore">
								Full Name:
								<?php echo $_SESSION['userFullName']; ?>
							</label>
						</div>
						<div class="flex flex-col gap-1 px-1">
							<button id="logout" class="rounded bg-green-400/80 p-2 darkmode-ignore text-white">Log out</button>
						</div>
					</div>					
				</div>			
			</section><!-- end of Login -->
		<?php endif; //end of SESSION UserID not set  ?>
		
		<?php if( isset( $_SESSION['userID'] ) ): ?>
			<section id = "articles" class = "article-container"> 
				<div class = "article-container__inner flex flex-col px-5 pt-5 gap-2">
					<div class = "article__view">
						<table width = "100%" cellpadding = "5" cellspacing = "5" border = "1" style = "border-collapse: collapse;" class="table-auto">
							<thead>
								<tr class="bg-slate-500/40">
									<th>Article ID</th>
									<th>Title</th>
									<th>Excerpt</th>
									<th>Description</th>
									<th>Date Created</th>
									<th>Date Modified</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody class="overflow-auto">
								<?php echo ViewArticles( 0 ); ?>
							</tbody>
						</table>
					</div><!-- end of Article View -->
					<div class = "article__insert border-2 rounded-md">
						<h3 class="p-2 text-lg font-bold">CREATE ARTICLE</h3>
						<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "POST" id = "formInsert" class = "form_article_insert">
							<div class = "field flex flex-wrap p-2 gap-2">
								<label for = "txtPostTitle" class="flex items-center gap-2">
									Title:
									<input type = "text" required id = "txtPostTitle" name = "txtPostTitle" placeholder = "Title" class = "field-text field-posttitle border rounded-md p-2">
								</label>
								<label for = "txtExcerpt" class="flex items-center gap-2">
									Excerpt:
									<input type = "text" id = "txtExcerpt" name = "txtExcerpt" placeholder = "Excerpt" class = "field-text field-excerpt border rounded-md p-2">
								</label>
								<label for = "txtDescription" class="flex items-center gap-2">
									Description:
									<textarea id = "txtDescription" name = "txtDescription" placeholder = "Description" class = "field-text field-description border rounded-md p-2 h-11"></textarea>
								</label>
								<input type = "submit" class = "btn btnSubmit darkmode-ignore rounded p-2 bg-green-400/80 my-auto text-white" name = "btnInsert" value = "Add Article">
							</div>
						</form>
					</div><!-- end of Article View -->
				</div>
			</section><!-- end of Article -->				
			<section class = "articleView hidden">
				<div class = "article__view flex flex-col px-5 pt-5 gap-2">
					<h3 class="p-2 text-lg font-bold">View Article</h3>
					<table width = "100%" cellpadding = "5" cellspacing = "5" border = "1" style = "border-collapse: collapse;">
						<thead>
							<tr class="bg-slate-500/40">
								<th>Article ID</th>
								<th>Title</th>
								<th>Excerpt</th>
								<th>Description</th>
								<th>Date Created</th>
								<th>Date Modified</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div><!-- end of Article View -->
			</section>
			<div class = "article__update mx-5 mt-2 hidden border-2 rounded-md">
				<h3 class="p-2 text-lg font-bold">UPDATE ARTICLE</h3>
				<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "POST" id = "formInsert" class = "form_article_insert">
					<input type = "hidden" name = "txtArticleID" id = "txtArticleID" value = "">
					<div class = "field flex flex-wrap p-2 gap-2">
						<label for = "txtPostTitle" class="flex items-center gap-2">
							Title:
							<input type = "text" required id = "txtPostTitle" name = "txtPostTitle" placeholder = "Title" class = "field-text field-posttitle border rounded-md p-2">
						</label>
						<label for = "txtExcerpt" class="flex items-center gap-2">
							Excerpt:
							<input type = "text" id = "txtExcerpt" name = "txtExcerpt" placeholder = "Excerpt" class = "field-text field-excerpt border rounded-md p-2">
						</label>
						<label for = "txtDescription" class="flex items-center gap-2">
							Description:
							<textarea id = "txtDescription" name = "txtDescription" placeholder = "Description" class = "field-text field-description border rounded-md p-2 h-11"></textarea>
						</label>
						<input type = "submit" class = "btn btnSubmit rounded darkmode-ignore p-2 bg-green-400/80 my-auto text-white" name = "btnUpdate" value = "Update Article">
					</div>
				</form>
			</div><!-- end of Article View -->
		<?php endif; //end of SessionID ?>
		<script type="text/javascript" src="assets/js/script.js"></script>
	</body>
</html>