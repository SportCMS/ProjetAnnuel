<?php
	namespace App;
	use App\core\Security;

	// ouverture session - 
	//	TODO::
			// - mettre les données en session dans le contollers user 
			// -  créer un bouton logout avec le partial dédié pour le menu pour détruire la session et tester les différents roles 
	session_start();

	require "conf.inc.php";

	function myAutoloader($class)
	{
		$class = str_ireplace('App\\',  '',$class);//On supprime "App\" de App\exemple\class.class.php
		$class = str_ireplace('\\', '/', $class);// 
		$class .= '.class.php';
		if(file_exists($class)){
			include $class;//On utilise include car plus rapide, et on a déjà vérifier son existance
		}else{
			ini_get('display_errors') == 1 ? die('404 not found : la classe n\'existe pas') : header('Location:/page-non-trouvee');

		}
	} 

	spl_autoload_register('App\myAutoloader');

	$path = 'routes.yml';
	if (file_exists($path)) {
		$routes = yaml_parse_file($path);
	} else {
		ini_get('display_errors')  == 1 ? die('404 not found fichier yml') : header('Location:/page-non-trouvee');
	}

	$uri = strtok($_SERVER['REQUEST_URI'], "?");//J'ai ajouté strtok afin de gérer les requetes get
	if(empty($routes[$uri]) || empty($routes[$uri]['controller']) || empty($routes[$uri]['action'])){
		ini_get('display_errors') == 1 ? die('404 not found prbl d\'ecriture yml') : header('Location:/page-non-trouvee');
	}
	if(!Security::checkRoute($uri)){
		ini_get('display_errors') == 1 ? die('404 not found : La route n\'est pas trouvée') : header('Location:/page-non-trouvee');
	}
	$controller =  'App\\controllers\\' . ucfirst(strtolower($routes[$uri]['controller']));
	$action = strtolower($routes[$uri]['action']);

	// echo '<pre>';
	// var_dump($routes);
	// echo '</pre>';

	//ajout role - routes.yaml - control des roles via index.php
	$role = $routes[$uri]['role'];

	if(isset($_SESSION['role'])){
		if(!in_array($_SESSION['role'], $role) && !in_array('public',$role)){
			throw new \Exception('Vous n\'avez pas le droit d\'accéder à cette page');
		}
	}

	$objectController = new $controller();
	if(!method_exists($objectController, $action)){
		ini_get('display_errors') == 1 ? die('404 not found controller') : header('Location:/page-non-trouvee');
	}
	$objectController->$action();
?>