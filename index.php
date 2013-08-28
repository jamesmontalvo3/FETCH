<?php
date_default_timezone_set('America/Chicago');

#
#	BROWSE
#
if ( isset($_GET['v']) && $_GET['v'] == 'db' ) {

	require_once "LocalSettings.php";
	require_once "lib/MySQLQuery.php";
	
	$db = new MySQLQuery( $fetch_db );

	$rows = $db->exe("SELECT * FROM " . $fetch_db['links_table']);
	
	include "view/browse.php";
	
}


#
#	ADD/EDIT LINKS (currently not possible)
#
else if ( isset($_GET['v']) && $_GET['v'] == 'row' ) {

	echo "Sorry, editing FETCH is currently not possible. If you need a link added 
	or modified ask the administrator.
	<br />
	<br />
	<a href='.'>Go back to FETCH</a>";

}

#
#	USE FETCH!
#
else {
	include "view/main.php";
}