<?php
include("twig-init.php");

echo $twig->render('test.html.twig', [
    'name' => 'Bipin'
]);