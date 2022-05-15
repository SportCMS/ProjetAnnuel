<?php
	namespace App;
	use App\core\Security;

	require "conf.inc.php";

	function myAutoloader($class)
	{
		$class = str_ireplace('App\\',  '',$class);//On supprime "App\" de App\exemple\class.class.php
		$class = str_ireplace('\\', '/', $class);// 
		$class .= '.class.php';
		if(file_exists($class)){
			include $class;//On utilise include car plus rapide, et on a déjà vérifier son existance
		}else{
			die('la classe existe pas : ' . $class);
		}
	}

	spl_autoload_register('App\myAutoloader');

	$path = 'routes.yml';
	if(file_exists($path)){
		$routes = yaml_parse_file($path);
	}else{
		die("fichier existe pas");
	}
	$uri = strtok($_SERVER['REQUEST_URI'], "?");//J'ai ajouté strtok afin de gérer les requêtes get avec des paramètres

	if(empty($routes[$uri]) || empty($routes[$uri]['controller']) || empty($routes[$uri]['action'])){
		die("Page 404");
	}
	if(!Security::checkRoute($uri)){
		die('Root n\'existe pas');
	}
	$controller =  'App\\controllers\\' . ucfirst(strtolower($routes[$uri]['controller']));
	$action = strtolower($routes[$uri]['action']);

	$objectController = new $controller();
	if(!method_exists($objectController, $action)){
		die('la method nexiste pas');
	}
	$objectController->$action();
?>