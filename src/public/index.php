<?php
include_once __DIR__ . '/../../vendor/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(realpath(__DIR__ . '/../views/'));
$twig = new Twig_Environment($loader);

$response = array(
    'title' => 'Vitutsmittari',
    'message' => array(),
    'notice' => array(),
    'error' => array(),
    'statistics' => array('count' => 0, 'average' => 0, 'sum' => 0, 'width' => 0)
);

if (isset($_POST['pissedOff']))
{
    $statisticsFile = '/tmp/vitutuslaskuri.json';
    $today = date('Y-m-d');
    $statistics = file_exists($statisticsFile)
        ? json_decode(file_get_contents($statisticsFile), true)
        : array($today => array('count' => 0, 'average' => 0, 'sum' => 0, 'width' => 0));

    if (isset($_POST['nope'])) $pissedOffValue = 0;
    elseif (isset($_POST['bit'])) $pissedOffValue = 1;
    elseif (isset($_POST['more'])) $pissedOffValue = 2;
    elseif (isset($_POST['lot'])) $pissedOffValue = 4;

    $statistics[$today] = array(
        'count' => $count = $statistics[$today]['count'] + 1,
        'sum' => $sum = $statistics[$today]['sum'] + $pissedOffValue,
        'average' => $average = $sum / $count,
        'width' => (int)floor($average * 3)
    );

    file_put_contents($statisticsFile, json_encode($statistics));
    $response['message'][] = 'Vitutuksesi aste huomioitu';
    $response['hide_submit'] = true;

    $response['statistics'] = $statistics[$today];
    echo $twig->render('result.html.twig', $response);
}
else
{
    echo $twig->render('page.html.twig', $response);
}


