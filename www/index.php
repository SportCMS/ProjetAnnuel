<?php
	namespace App;
	use App\core\Security;

	// ouverture session - 
	//	TODO::
			// - mettre les données en session dans le contollers user 
			// -  créer un bouton logout avec le partial dédié pour le menu pour détruire la session et tester les différents roles 
	session_start();

	require "functional/functional.inc.php";

	require "conf.inc.php";

	function myAutoloader($class)
	{
		$class = str_ireplace('App\\',  '',$class);//On supprime "App\" de App\exemple\class.class.php
		$class = str_ireplace('\\', '/', $class);// 
		$class .= '.class.php';
		if(file_exists($class)){
			include $class;//On utilise include car plus rapide, et on a déjà vérifier son existance
		}else{
			echo "CLASS EXISTE PAS " . $class;
			abort(500);
		}
	} 

	spl_autoload_register('App\myAutoloader');

	if(!file_exists($path = 'routes.yml')){
		echo "FICHIER ROUTER EXISTE";
		abort(500);
	}
	$routes = yaml_parse_file($path);

	$uri = strtok($_SERVER['REQUEST_URI'], "?");//J'ai ajouté strtok afin de gérer les requetes get
	if(empty($routes[$uri])){
		abort(404);
	}

	if(empty($routes[$uri]['controller'])){
		echo "CONTROLLER NON SPÉCIFIÉ";
		abort(500);
	}
	if(empty($routes[$uri]['action'])){
		echo "ACTION NON SPÉCIFIÉ";
		abort(500);
	}

	$controller =  'App\\controllers\\' . ucfirst(strtolower($routes[$uri]['controller']));
	$action = strtolower($routes[$uri]['action']);

	// echo '<pre>';
	// var_dump($routes);
	// echo '</pre>';

	//ajout role - routes.yaml - control des roles via index.php
	$role = $routes[$uri]['role'];

	if(!isset($_SESSION['role'])){
		$_SESSION['role'] = 'public';
	}

	if(isset($_SESSION['role'])){
		if(!in_array($_SESSION['role'], $role) && !in_array('public',$role)){
			throw new \Exception('Vous n\'avez pas le droit d\'accéder à cette page');
		}
	}

	$objectController = new $controller();
	if(!method_exists($objectController, $action)){
		echo "MÉTHODE N'EXISTE PAS";
		abort(500);
	}
	$objectController->$action();
?>