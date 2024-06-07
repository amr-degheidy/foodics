<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;


$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('storage');

$config = new Config();
return $config->setRules([
    // Define your rules here
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => true,
    // Add more rules as needed
])->setFinder($finder);
