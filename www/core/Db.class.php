<?php
    namespace App\core;
	class Db
	{
		private $PDOInst = null;
		public static $connection;

		private function __construct()
		{
			try{
                $this->PDOInst = new \PDO(DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME , DBUSER , DBPWD
                , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
            }catch(\Exception $e){
                die('Error sql' . $e->getMessage());
            }
		}
		public static function connect()
		{
			if (!isset(self::$connection))
			{
				self::$connection = new Db();
			}
			return self::$connection;
		}
		public function prp($sql, $array = []){
			$stmt = $this->PDOInst->prepare($sql);
			$stmt->execute($array);
			return $stmt;
		}
	}