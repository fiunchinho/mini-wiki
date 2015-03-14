<?php

$app['debug']                       = true;
$app['twig.path']                   = [__DIR__.'/../templates'];
$app['twig.options']                = ['cache' => '/tmp/cache/twig'];
$app['article_path']                = __DIR__ . '/../db.db';
$app['http_cache.cache_dir']        = '/tmp/cache/';
$app['http_cache.esi']              = null;
$app['http_cache.max_age_seconds']  = 10;
