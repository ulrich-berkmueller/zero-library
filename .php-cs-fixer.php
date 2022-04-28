<?php

$finder = PhpCsFixer\Finder::create()
    ->notName('README.md')
    ->notName('composer.*')
    ->notName('.scrutinizer.yml')
    ->notName('.travis.yml')
    ->notName('.php-cs-fixer.php')
    ->notName('rtl.css')
    ->notName('style.css')
    ->notName('screenshot.png')
    ->exclude('vendor')
    ->exclude('wp-content')
    ->exclude('tests')
    ->exclude('views')
    ->exclude('languages')
    ->exclude('assets')
    ->exclude('tasks')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'strict' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_use' => true,
    '-no_empty_lines_after_phpdocs' => true,
    'phpdoc_order' => true,
    ])
    ->setFinder($finder)
;
