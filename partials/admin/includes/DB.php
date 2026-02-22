<?php

  class DB
  {
    public $DBconnect;
    private $servername = "localhost";
  	private $username  = "root";
  	private $password = "";
  	private $dbname= "mutty_fem";

    public function __construct()
    {
      $this->DbConnection();
    }

    protected function DbConnection()
  	{
  		try {
  			$this->DBconnect = new PDO("mysql:host=".$this->servername.";dbname=".$this->dbname,$this->username, $this->password);

  			$this->DBconnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  			return $this->DBconnect;

  		} catch (PDOException $e) {
  			echo "connection failed: ".$e->getMessage();
  		}
  	}


  }



?>
