<?php
	include_once "AppConfig.php";

	Class DatabaseConnection extends PDO{
		private $dbName;
		
		public function __construct($file = 'settings.ini'){	//Para alterar alguma informação, acesse
			/*
			if(AppConfig::AMBIENTE_QAS){						//o arquivo de configuração "settings.ini".
				$db = 'QAS';
			}else{
				$db = 'DEV';
			}*/
			$db = 'LOCAL';
			if(!$settings = parse_ini_file($file, TRUE))
				throw new Exception('Não foi possível abrir o arquivo '.$file.'.');
			$this->dbName = $settings[$db]['schema'];	
			$dns = $settings[$db]['driver'].															
			':host'.$settings[$db]['host']. 															
			((!empty($settings[$db]['port'])) ? (';port:'.$settings[$db]['port']) : '').		
			';dbname:'.$settings[$db]['schema'];													
			parent::__construct($dns, $settings[$db]['username'], $settings[$db]['password']);
		}

		public function getDbName(){
			return $this->dbName;
		}
	}
?>