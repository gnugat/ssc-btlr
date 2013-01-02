#!/usr/bin/php
<?php

$skeletonDirectory = __DIR__.'/../skeleton/';

$projectPath = $argv[1];
$projectName = end(explode('/', $argv[1]));

$virtualHostFile = file_get_contents($skeletonDirectory.'virtual-host');
$virtualHostFile = str_replace('path-to-replace', $projectPath, $virtualHostFile);
$virtualHostFile = str_replace('name-to-replace', $projectName, $virtualHostFile);

file_put_contents($skeletonDirectory.$projectName.'.local', $virtualHostFile);
