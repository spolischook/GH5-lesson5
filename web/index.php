<?php

require_once '../src/App/app.php';

$db = initDb();
$users = $db->users->find();

$loader = new Twig_Loader_Filesystem('../src/App/Templates');
$twig = new Twig_Environment($loader, [
//    'cache' => '../cache',
]);

echo $twig->render('index.html.twig', ['users' => $users]);
