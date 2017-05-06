<?php

$containerBuilder = include __DIR__ . '/../bootstrap.php';

$containerBuilder->get('app.response.loader')
    ->loadResponse(\Symfony\Component\HttpFoundation\Request::createFromGlobals())
    ->send();
