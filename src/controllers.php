<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/article/{slug}', 'controller.wiki:get')->bind('article_get');
$app->get('/admin/{slug}', 'controller.wiki:admin')->bind('article_admin_get');
$app->post('/admin/{slug}', 'controller.wiki:post')->bind('article_admin_post');

$app->after(function (Request $request, Response $response) use ($app) {
    $response->setCache([
        'etag'          => md5($response->getContent()),
        'max_age'       => $app['http_cache.max_age_seconds'],
        's_maxage'      => $app['http_cache.max_age_seconds'],
        'public'        => true
    ]);
    $response->isNotModified($request);
});