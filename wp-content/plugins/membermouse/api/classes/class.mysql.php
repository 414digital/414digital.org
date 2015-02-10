<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
class MYSQL
{
	private $host = "";
	private $database = "";
	private $username = "";
	private $password = "";

	public function __construct($host="", $username="", $password="", $database="")
	{
		$this->host = ($host!="")?$host:$this->host;
		$this->database = ($database!="")?$database:$this->database;
		$this->username = ($username!="")?$username:$this->username;
		$this->password = ($password!="")?$password:$this->password;
	}

	public function get_results($sql)
	{
		$resource = $this->query($sql);
		if(mysql_num_rows($resource)>0)
		{
			$results = array();
			while($row = mysql_fetch_object($resource))
			{
				$results[] = $row;
			}
			return $results;
		}
		return false;
	}

	public function get_row($sql)
	{
		if($conn = $this->connect())
		{
			$res = @mysql_query($sql);
			if(!$res)
			{
				echo mysql_error();
				return false;
			}
		
			if(mysql_num_rows($res)>0)
			{
				return mysql_fetch_object($res);
			}
		}
		return false;
	}
	
	public function query($sql)
	{
		if($conn = $this->connect())
		{
			$res = @mysql_query($sql);
			if(!$res)
			{
				return false;
			}
			return $res;
		}
	}

	private function connect()
	{
		if ( $conn = mysql_connect ($this->host, $this->username, $this->password) )
		{
			if ( mysql_select_db ($this->database, $conn) )
			{
				return $conn;
			}
		}
		return false;
	}

	public function disconnect()
	{
		@mysql_close();
	}

	public function backup($table_arr)
	{
		foreach($table_arr as $table)
		{
			$path = "/tmp/{$table}_".Date("Y_m_d_h_i").".sql";
			$cmd = "mysqldump -acf -u ".DB_USERNAME." --password=".DB_PASSWORD."  ".DB_DATABASE." {$table}> {$path}";
			shell_exec($cmd);

			echo $cmd;

			if(!file_exists($path))
			{
				return $path ." could not be backed up. ";
			}
			else if(filesize($path)<=0)
			{
				return $path ." could not be backed up. ";
			}
		}
		return "SUCCESS";
	}

}
?>