<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
;

$header = <<<'EOF'
    @author  POSTYOU Digital- & Filmagentur
    @license MIT
    EOF;

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP74Migration' => true,
        '@PHP74Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'header_comment' => ['header' => $header],
    ])
    ->setFinder($finder)
;
