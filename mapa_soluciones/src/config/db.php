<?php 

	class DB {
		private $dbHost = 'localhost';
		
		private $dbUser = 'root';
		private $dbPass = '12345678';
		
		public function connection ($dbName) {
			$conec = new mysqli ($this->dbHost , $this->dbUser , $this->dbPass , $dbName);
			return $conec;
		}
		public function consultaSinParametros($dbName, $sql){
			$conec = new mysqli ($this->dbHost , $this->dbUser , $this->dbPass , $dbName);
			$resultado = $conec->prepare($sql);
    		$resultado->execute();
    		$resultado = $resultado->get_result();
			$array = $resultado->fetch_all(MYSQLI_ASSOC);
			return [$array, $resultado];
		}
	}

 ?>