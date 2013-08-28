<?php

function get_url ($url) {
	global $url_characters_displayed;
	
	if (strlen($url) > $url_characters_displayed)
		return substr($url, 0, $url_characters_displayed) . " . . .";
	else
		return $url;
}

?><!DOCTYPE html>
<html>
	<head>
		<?php require_once "html-head.php"; ?>
	</head>
	<body>
		
		<div id="browse-wrapper">
			<div style="margin:5px;">
				<img src="images/fetch-mini.png" /> <span style="margin-left:20px; font-size:48px; font-weight:bold;">Links</span>
				<br />
				<a href='?v=row'>Add FETCH Link</a>
			</div>
		<?php foreach ($rows as $index => $columns) { ?>

			<div class='fetch-db-row'>
				<a class='fetch-title-link' href='<?php echo $columns['url']; ?>'><?php echo $columns['title']; ?></a>
				<a class='fetch-edit-link' href='?v=row&id=<?php echo $columns['id']; ?>'>edit</a>
				<div>Keywords: <?php echo $columns['keywords']; ?></div>
				<div class='fetch-url'><?php echo get_url($columns['url']); ?></div>
			</div>
		
		<?php } ?>
		
		</div>
	
	</body>
</html>