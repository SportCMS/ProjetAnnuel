<?php
	namespace App;
	use App\core\Security;

	require ".env";

	require 'autoloader/autoloader.php';

	$path = 'routes.yml';
	if(file_exists($path)){
		$routes = yaml_parse_file($path);
	}else{
		die("fichier existe pas");
	}
	$uri = strtok($_SERVER['REQUEST_URI'], "?");//J'ai ajouté strtok afin de gérer les requetes get
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