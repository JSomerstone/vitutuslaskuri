<?php
include_once __DIR__ . '/../../vendor/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(realpath(__DIR__ . '/../views/'));
$twig = new Twig_Environment($loader);
echo $twig->render('page.html.twig', array('text' => 'Hello world!'));

