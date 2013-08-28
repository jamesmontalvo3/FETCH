<!DOCTYPE html>
<html>
	<head>
		<?php require_once "html-head.php"; ?>
	</head>
	<body>
		
		<div id="wrapper">
			
			<img src='images/fetch.png' title='Find Every Tedious Convoluted Hyperlink' id="logo" />
			<input type='text' name='fetchbox' id='fetchbox' />
			<div id="link-wrapper">
				<a href='?v=row'>add</a> |
				<a href='?v=db'>browse</a> |
				<a id='opener' href=''>about</a>
			</div>
			
		</div>
	
		<div id='dialog' title='About' style="display:none;">
			<p>FETCH is designed to make it easier to store and find all your 
			favorite links. Type keywords into the textbox (try "home" or "jsc")
			and then click on the links that appear.</p>
			
			<p>Browse existing links by clicking "browse". You can also edit the
			links through this page if you have privileges. Improve FETCH by adding 
			keywords or making titles more accurate or descriptive.</p>
			
			<p>FETCH also now searches the EVA Wiki, and will search other wikis in
			the future</p>
		</div>
	
	</body>
</html>