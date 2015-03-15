<?php

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use MiniWiki\WikiServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new WikiServiceProvider());
$app->register(new HttpCacheServiceProvider());

return $app;
