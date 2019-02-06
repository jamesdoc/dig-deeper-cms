<?php

require __DIR__.'/../vendor/autoload.php';

// Start PHP session
session_start();

// Instantiate the app
$settings = require __DIR__.'/../../cfg/settings.php';
$app = new \Slim\App($settings);

$container = $app->getContainer();

// Register flash provider
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Register dependencies
require __DIR__ . '/../dependencies.php';
//require __DIR__.'/../classes/Week.php';
//require __DIR__.'/../classes/Author.php';
//require __DIR__.'/../classes/Study.php';

// Register middleware
require __DIR__ . '/../middleware.php';

// Register routes
require __DIR__ . '/../routes/home.php';
require __DIR__ . '/../routes/auth.php';
require __DIR__ . '/../routes/studies.php';
require __DIR__ . '/../routes/week.php';
require __DIR__ . '/../routes/author.php';

$app->run();
