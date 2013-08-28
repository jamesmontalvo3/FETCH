<?php

class MySQLQuery {

    public $db;

    public function __construct ($db_info=false) {

		$this->connect_to_db($db_info);

    }

    public function exe ($query, $errorMessage="") {

        $result = mysql_query($query, $this->db) or die ($errorMessage . mysql_error($this->db));

        $output = array();

        if (is_resource($result))
            while( $row = mysql_fetch_assoc($result) ) $output[] = $row;
        
        return $output;

    }
	
    public function connect_to_db ($db_info) {

		if ( ! is_array($db_info) )
			die("connect_to_db expects array");
		
        $link = mysql_connect($db_info['host'], $db_info['user'], $db_info['pass']) or
            die ('Unable to connect. Check you connection parameters.');

        mysql_select_db($db_info['db_name'], $link) 
            or die(mysql_error($link));

        $this->db = $link;

    }

}