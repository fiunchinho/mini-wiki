<?php

require_once __DIR__.'/../vendor/autoload.php';

\Symfony\Component\Debug\Debug::enable();

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../resources/config/dev.php';
require __DIR__.'/../src/controllers.php';
$app['http_cache']->run();