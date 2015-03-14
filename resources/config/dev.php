<?php

$app['debug']                       = true;
$app['twig.path']                   = [__DIR__.'/../templates'];
$app['twig.options']                = ['cache' => '/tmp/cache/twig'];
$app['article_path']                = __DIR__ . '/../db.db';