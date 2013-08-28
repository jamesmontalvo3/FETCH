<?php

$application_path = "http://example.com/your/path/";

$fetch_db = array(
	'host'=>'localhost',
	'user'=>'username',
	'pass'=>'password',
	'db_name'=>'db_name',
	'links_table'=>'name-of-table', //this will be standardized, but I have two implementations that need separate names for now
	'log_table'=>false  // same as above
);

$default_wiki_info = array(
	'host'=>'localhost',
	'user'=>'username',
	'pass'=>'password'
);

$wiki_dbs = array(

	// one mediawiki
	array(
		'source'         => 'My Wiki',
		'db_name'        => 'wiki_mine',
		'wiki_http_root' => 'https://example.com/mywiki/'
	),
	
	// another mediawiki
	array(
		'source'         => 'Your Wiki',
		'db_name'        => 'wiki_yours',
		'wiki_http_root' => 'https://example.com/yourwiki/'
	)
	
);

$url_characters_displayed = 80; // number of characters of URL displayed on "browse" page
