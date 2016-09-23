<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir
        // $config->application->dtoDir
    )
)->register();

$loader->registerNamespaces(
	array(
		// "Ranking"=>__DIR__."/../../lib"
		"Dto" => $config->application->dtoDir,
		"Ranking"=>$config->application->rankingDir
	)	
)->register();
