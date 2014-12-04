<?php
include_once __DIR__ . '/../../vendor/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(realpath(__DIR__ . '/../views/'));
$twig = new Twig_Environment($loader);

$response = array(
    'message' => array(),
    'notice' => array(),
    'error' => array(),
    'statistics' => array($today => array('count' => 0, 'average' => 0, 'sum' => 0))
);

if (isset($_POST['pissedOff']))
{
    if (isset($_POST['nope'])) $pissedOffValue = 0;
    elseif (isset($_POST['bit'])) $pissedOffValue = 1;
    elseif (isset($_POST['more'])) $pissedOffValue = 3;
    elseif (isset($_POST['lot'])) $pissedOffValue = 5;

    $statisticsFile = '/tmp/vitutuslaskuri.json';
    $today = date('Y-m-d');
    $statistics = file_exists($statisticsFile)
        ? json_decode(file_get_contents($statisticsFile), true)
        : array($today => array('count' => 0, 'average' => 0, 'sum' => 0));

    $statistics[$today] = array(
        'count' => $count = $statistics[$today]['count'] + 1,
        'sum' => $sum = $statistics[$today]['sum'] + $pissedOffValue,
        'average' => $average = $sum / $count
    );

    file_put_contents($statisticsFile, json_encode($statistics));
    $response['message'][] = 'Vitutuksesi aste huomioitu';
    $response['statistics'] = $statistics;
}

echo $twig->render('page.html.twig', $response);

