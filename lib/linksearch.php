<?php

date_default_timezone_set('America/Chicago');
require_once "MySQLQuery.php";

require_once "../LocalSettings.php";

class LinkSearch {

	public $db;
	
	public function __construct () {
		global $fetch_db;
		$this->db = new MySQLQuery($fetch_db);
	}

	
	public function get_matching_items ($terms) {
		global $fetch_db;

		// number of links to send to client
		$MAXROWS = 6;
		
		$this->full_string = $terms;
		$terms = explode(" ", trim($terms) );		

		$table = $fetch_db['links_table']; // from localsettings
		
		$rows = $this->db->exe("
			SELECT * 
			FROM $table
			WHERE title LIKE '%" . implode("%' AND title LIKE '%", $terms) . "%' 
			LIMIT $MAXROWS
		");

		$morerows = $MAXROWS - count($rows);
			
		if ($morerows > 0) {
		
			$existingIds = array();
			foreach ($rows as $row)
				$existingIds[] = $row['id'];
		
			#
			# implode() below is used as a tricky way to create a list of keywords
			# that ends up looking like:
			# ... keywords LIKE '%term1%' AND keywords LIKE '%term2%' ...
			#
			$morerows = $this->db->exe("
				SELECT * 
				FROM $table
				WHERE
					keywords LIKE '%" . implode("%' AND keywords LIKE '%", $terms) . "%' 
					AND id != '" . implode("' AND id != '", $existingIds) . "' 
				LIMIT $morerows
			");

			$rows = array_merge($rows, $morerows);
				
		}
		
		$rows = array_merge($rows, $this->get_wiki_rows() );
				
		return $rows;

	}
	
	public function get_wiki_rows () {
		global $default_wiki_info, $wiki_dbs;

		// there is a better, cleaner way to do this...
		$namespaces = array(
			1 => "Talk",
			2 => "User",
			3 => "User_talk",
			4 => "Project",
			5 => "Project_talk",
			6 => "File",
			7 => "File_talk",
			8 => "MediaWiki",
			9 => "MediaWiki_talk",
			10 => "Template",
			11 => "Template_talk",
			12 => "Help",
			13 => "Help_talk",
			14 => "Category",
			15 => "Category_talk"
		);
		
		$addrows = array();

		foreach ($wiki_dbs as $wiki) {
		
			$conf = $wiki;
			if ( ! isset($conf['host']) )
				$conf['host'] = $default_wiki_info['host'];
				
			if ( ! isset($conf['user']) ) {
				$conf['user'] = $default_wiki_info['user'];
				$conf['pass'] = $default_wiki_info['pass'];
			}
				
			// include wiki
			$wiki_sql = new MySQLQuery($conf);
			
			$this->full_string = strtoupper($this->full_string);
			
			$wikirows = $wiki_sql->exe("
				SELECT page.page_title,page.page_namespace 
				FROM page, titlekey 
				WHERE
					titlekey.tk_key LIKE '%{$this->full_string}%'
					AND 
					page.page_id = titlekey.tk_page
				LIMIT 5
			");
			
			foreach($wikirows as $row) {
				
				if($row['page_namespace'] > 0)
					$ns = $namespaces[ $row['page_namespace'] ] . ":";
				else
					$ns = "";
				$title = $ns . str_replace("_"," ",$row["page_title"]);
				$url = $conf['wiki_http_root'] . "index.php?title=" . $ns . $row["page_title"];
			
				$addrows[] = array(
					"id" => "wiki",
					"title" => $title,
					"url" => $url,
					"source" => $conf['source']
				);
			}
		
		}
		
		return $addrows;
	
	}
	
	public function format_row_json ($rows) {
		global $application_path;
	
		$array = array();
		$c = 0;

		//I think i need to make this not a resource...
		foreach ($rows as $row) {

			foreach ($row as $colname => $colval) {

				if ($colname == "id")
					$array[$c]["id"] = $colval;
				else if ($colname == "title") {
					$array[$c]["label"] = $colval;
					$array[$c]["value"] = $colval;
				} else if ($colname == "url")
					$array[$c]["url"] = $colval;
					
				if ($colname == "source")
					$array[$c]["source"] = $colval;

			}

			$c++;

		}
		
		$array[$c]["label"] = "Didn't find your link? Click here to add it.";
		$array[$c]["url"] = $application_path . "fetch.php?v=row";
		$array[$c]["id"] = "0";

		return json_encode($array);

	}
	
	// this was in a separate file, but it was very similar to log_event below so 
	// I pulled it in. In order for this to work probably this class will have to be
	// pulled into a separate file or the logic after the class will have to have a 
	// if($_GET[...]) type statement in it...
	public function log_click () {
	
	
		if ($_POST[linkid] > 0) {
			$clicked = $this->db->exe("SELECT url,title FROM links WHERE id='{$_POST[linkid]}'");
			$clicked = $clicked[0];
		}
		else if ($_POST[linkid] == 0) {
			$clicked = array("title" => "Clicked 'Add Link'", "url" => "managelinks.php");
		}
		
		$info = "link_id:{$_POST[linkid]};title:{$clicked[title]};url:{$clicked[url]};";
			
			
		$this->log_event('click', $info);

	}
	
	public function log_event ($type, $info) {
		global $fetch_db;

		$log_table = $fetch_db['log_table'];

		if ( isset($_SERVER['LOGON_USER']) )
			$user_info = ($_SERVER['LOGON_USER']) . " (" . $_SERVER["REMOTE_ADDR"] . ")";
		else if ( isset($_SERVER["REMOTE_USER"]) )
			$user_info = $_SERVER["REMOTE_USER"];
		else if ( isset($_SERVER["AUTH_USER"]) )
			$user_info = $_SERVER["AUTH_USER"];
		else if ( isset($_SERVER["REMOTE_ADDR"]) )
			$user_info = $_SERVER["REMOTE_ADDR"];
		else
			$user_info = "unknown user";

		$user_info = mysql_real_escape_string($user_info);
		$info = mysql_real_escape_string($info);
		
		$timestamp = date("Y-m-d H:i:s",time());
		
		$this->db->exe("
			INSERT INTO $log_table
			(type, timestamp, user_ip, info) 
			VALUES ('$type', '$timestamp', '$user_info', '$info')
		");
				
		return true;

	}

}


$ls = new LinkSearch();

if ($fetch_db['log_table'])
	$ls->log_event( 'query', 'query was:' . $_GET['term']  );

echo $ls->format_row_json( $ls->get_matching_items( $_GET['term'] ) );
