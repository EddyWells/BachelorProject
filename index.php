<?php

require_once "vendor/autoload.php";

 // Spot2 ORM Configuration
function spot() 
  {
    static $spot;
    if ($spot === null) {
      $cfg = new \Spot\Config();
      $cfg->addConnection('mysql', [
          'dbname' => 'etuproject',
          'user' => 'root',
          'password' => '',
          'host' => 'localhost',
          'driver' => 'pdo_mysql',
      ]);
      $spot = new \Spot\Locator($cfg);
    }

    return $spot;
  }

session_start();
ob_start(); // Démarre la temporisation de sortie.

$get = filter_input_array(INPUT_GET);

if (isset($get['c']) && isset($get['t'])){
	$class = "Controllers\\" . $get['c'] . 'Controller';
	$target = $get['t'];
	if (class_exists($class, true)) {
		$class = new $class();
		if (in_array($target, get_class_methods($class))) {
			call_user_func([$class, $target]);
		}
	} else {
		header("Location: index.php");
	}
}

if (isset($_SESSION['id'])){
		if (isset($get['page'])) {
            include "views/" . $get['page'] . ".php";
        } else {
            include "views/home.php"; 
        }
} else {
    header("Location: views/login.php");
}

$content = ob_get_clean(); // Lit le contenu courant du tampon de sortie puis l'efface

include "views/layout/base.php"; // Template de la page  
