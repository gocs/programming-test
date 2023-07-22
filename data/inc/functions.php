<?php

if( isset( $_POST['btnInsert'] ) ){
	
	$conn			= SQLConnection( 'localhost','root','','programming_test' );
	
	$title			= sanitizeData( $_POST['txtPostTitle'] ) ?? "";
	$excerpt		= sanitizeData( $_POST['txtExcerpt'] ) ?? "";
	$description	= sanitizeData( $_POST['txtDescription'] ) ?? "";
	
	
	if( empty( $title ) ){		
		return;
	}		
		
	$sqlInsertArticle = "INSERT INTO `articles` ( `user_id`, `title`, `excerpt`, `description` ) 
						VALUES ( '1', '$title', '$excerpt', '$description' )";
						
						$conn->query( $sqlInsertArticle );
						
	if( $conn->query( $sqlInsertArticle ) === TRUE ){
		echo "New Record Added";
		
		$title			= "";
		$excerpt		= "";
		$description	= "";
		
		header("Location: " . $_SERVER["REQUEST_URI"]);
		exit;
		
	}else{
		echo "Error: " . $sqlInsertArticle . "<br>" . $conn->error;
	}
	
}

if( isset( $_POST['btnLogin'] ) ){
	$conn			= SQLConnection( 'localhost','root','','programming_test' );
	
	$txtUsername	= sanitizeData( $_POST['txtUsername'] ) ?? "";
	$txtPassword	= sanitizeData( $_POST['txtPwd'] ) ?? "";
	
	if( ! empty( $txtUsername ) && ! empty( $txtPassword ) ){
		$sql	= "SELECT * FROM `users` WHERE username = '$txtUsername' AND password = '$txtPassword'";	
		$result = $conn->query($sql);
	
		
		if ( $result->num_rows > 0 ) {
			while($row = $result->fetch_assoc()) {
				$_SESSION['userID']			= $row['user_id'];
				$_SESSION['userName']		= $row['username'];
				$_SESSION['userFullName']	= $row['fullname'];
			}
			
			header("Location: " . $_SERVER["REQUEST_URI"]);
			exit;
		} else {
			echo "Logged In Failed: ".  $conn->error;
		}
		
	}
	
}

if( isset( $_POST['data']['onDelete'] ) ){	
	
	$conn		= SQLConnection( 'localhost','root','','programming_test' );	
	$articleID	= $_POST['data']['ArticleID'] ?? '';	
	$sql		= "DELETE FROM `articles` WHERE article_id='$articleID'";
		
	if ($conn->query($sql) === TRUE) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . $conn->error;
	}	
	
	die();
	
}

if( isset( $_POST['data']['onViewArticle'] ) ){	
	
	$articleID			= $_POST['data']['ArticleID'] ?? '';	
	$data				= "";	
	
	if( $_POST['data']['onViewArticle'] === 'onViewUpdate' ){
		$data = ViewArticles( $articleID, 1 );			
		echo json_encode( $data );
		
	}else{
		$data = ViewArticles( $articleID );		
		echo $data;
	}	
	
	die();	
}

if (isset($_POST['data']['onSearch'])) {
	$keywords = sanitizeData( $_POST['data']['keywords'] ) ?? "";

	$data = SearchInArticles($keywords);
	echo $data;
	die();
}

if (isset($_POST['data']['onLogout'])) {
	session_destroy();
	header('Location: /');
	die();
}

if( isset( $_POST['btnUpdate'] ) ){
	$articleID		= sanitizeData( $_POST['txtArticleID'] ) ?? "";
	$title			= sanitizeData( $_POST['txtPostTitle'] ) ?? "";
	$excerpt		= sanitizeData( $_POST['txtExcerpt'] ) ?? "";
	$description	= sanitizeData( $_POST['txtDescription'] ) ?? "";
	$date			= date("Y-m-d h:i:s");
	
	$conn	= SQLConnection( 'localhost','root','','programming_test' );
	
	$sql	= "UPDATE `articles` SET `title` = '$title', `excerpt` = '$excerpt', `description` = '$description', `date_modified` = '$date' WHERE `articles`.`article_id` = '$articleID';";
	
	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
}

function sanitizeData($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  
  return $data;
}

function SearchInArticles($keywords) {
	$keywords_arr = explode(" ", $keywords);
	$text_fields = ["title", "excerpt", "description"];
	$conditions_arr = [];
	$conditions = "";
	if ($keywords !== "") {
		foreach ($keywords_arr as $keyword) {
			$or = [];
			foreach ($text_fields as $f) 
				$or[] = "`$f` like \"$keyword\"";
	
			$conditions_arr[] = implode(" or ", $or);
		}
		$conditions = "where " . implode(" or ", $conditions_arr);
	}

	$conn		= SQLConnection( 'localhost','root','','programming_test' );
	$sql		= "SELECT * FROM `articles` $conditions";
	$data		= "";
	
	$results	= $conn->query($sql);
	if( $results->num_rows > 0 ){
		$i = 0;
		while($row = $results->fetch_assoc()) {
			$tr_bg = "bg-slate-300/40";
			if ($i % 2 === 0) $tr_bg = "bg-slate-400/40";

			$data .= '<tr class="'. $tr_bg .'" data-index="'. $i .'">';
				$data .= "<td>". $row['article_id'] ."</td>";
				// $data .= "<td>". $row['user_id'] ."</td>";
				$data .= "<td>". $row['title'] ."</td>";
				$data .= "<td>". $row['excerpt'] ."</td>";
				$data .= "<td>". $row['description'] ."</td>";
				$data .= "<td>". $row['date_created'] ."</td>";
				$data .= "<td>". $row['date_modified'] ."</td>";
				$data .= "<td class='flex gap-1'>";
					$data .= "<button attrArticleID = '". $row['article_id'] ."' class = 'btn btnEdit bg-green-400/80 text-white rounded px-1 darkmode-ignore'>Edit</button>";
					$data .= "<button attrArticleID = '". $row['article_id'] ."' class = 'btn btnView bg-green-400/80 text-white rounded px-1 darkmode-ignore'>View</button>";
					$data .= "<button attrArticleID = '". $row['article_id'] ."' class = 'btn btnDelete bg-red-400/80 text-white rounded px-1 darkmode-ignore'>Delete</button>";
				$data .= "</td>";
			$data .= "</tr>";
		}
	}
	return $data;
}

function ViewArticles( $articleID, $articleUpdate = 0 ){
		
	$conn		= SQLConnection( 'localhost','root','','programming_test' );	
	$sql		= "SELECT * FROM `articles`";	
	$data		= "";
	$dataArray	= array();
	
	if( $articleID  !== 0 ){
		$sql = "SELECT * FROM `articles` WHERE `article_id` = '$articleID'";	
	}
	
	$results	= $conn->query($sql);
		
	if( $results->num_rows > 0 ){
		$i = 0;
		while($row = $results->fetch_assoc()) {
			
			if( $articleUpdate !== 0 ){
				$dataArray['data'] = array(
					'ArticleID'		=> $row['article_id'],
					'Title'			=> $row['title'],
					'Excerpt'		=> $row['excerpt'],
					'Description' 	=> $row['description']
				);						
			}else{
				$tr_bg = "bg-slate-300/40";
				if ($i % 2 === 0) $tr_bg = "bg-slate-400/40";

				$data .= '<tr class="'. $tr_bg .'" data-index="'. $i .'">';
					$data .= "<td>". $row['article_id'] ."</td>";
					// $data .= "<td>". $row['user_id'] ."</td>";
					$data .= "<td>". $row['title'] ."</td>";
					$data .= "<td>". $row['excerpt'] ."</td>";
					$data .= "<td>". $row['description'] ."</td>";
					$data .= "<td>". $row['date_created'] ."</td>";
					$data .= "<td>". $row['date_modified'] ."</td>";
					$data .= "<td class='flex gap-1'>";
						$data .= "<button attrArticleID = '". $row['article_id'] ."' class = 'btn btnEdit bg-green-400/80 text-white rounded px-1 darkmode-ignore'>Edit</button>";
						
						if( $articleID === 0 )
							$data .= "<button attrArticleID = '". $row['article_id'] ."' class = 'btn btnView bg-green-400/80 text-white rounded px-1 darkmode-ignore'>View</button>";
						
						$data .= "<button attrArticleID = '". $row['article_id'] ."' class = 'btn btnDelete bg-red-400/80 text-white rounded px-1 darkmode-ignore'>Delete</button>";
					$data .= "</td>";
				$data .= "</tr>";
			}
			$i++;
		}
		
		if( $articleUpdate !== 0 ){
			return $dataArray;
		}else{
			echo $data;
		}
		
		
	}else{		
		echo "<tr><td>No Records</td></tr>";
	}
}