<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/article/{slug}', function (Request $request, $slug) use ($app) {
    return $app['twig']->render(
        'article.html',
        ['text' => $app['wiki']->getArticle($slug)]
    );
})
->bind('article_get');

$app->get('/admin/{slug}', function (Request $request, $slug) use ($app) {    
    return $app['twig']->render(
        'admin.html',
        ['slug' => $slug, 'text' => $app['wiki']->getArticle($slug)]
    );
})
->bind('article_admin_get');

$app->post('/admin/{slug}', function (Request $request, $slug) use ($app) {
    if ($app['wiki']->writeArticle($slug, $request->request->get('text'))) {
        return $app->redirect($app["url_generator"]->generate("article_get", ['slug' => $slug]));
    }else{
        throw new \RunTimeException("Error writing the article");
    }
})
->bind('article_admin_post');

$app->after(function (Request $request, Response $response) use ($app) {
    $response->setCache([
        'max_age'       => $app['http_cache.max_age_seconds'],
        's_maxage'      => $app['http_cache.max_age_seconds'],
        'public'        => true
    ]);
    $response->headers->set('ETag', md5($response->getContent()));
    $response->isNotModified($request);
});