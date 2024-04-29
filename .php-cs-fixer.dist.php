<?php

$header = <<<'EOF'
This file is part of the Particle project.

(c) Qsomazzi

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony'                               => true,
        'array_indentation'                      => true,
        'header_comment'                         => ['header' => $header],
        'binary_operator_spaces'                 => ['operators' => ['=>' => 'align', '=' => 'align']],
        'linebreak_after_opening_tag'            => true,
        'ordered_imports'                        => true,
        'phpdoc_summary'                         => false,
        'array_syntax'                           => ['syntax' => 'short'],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'phpdoc_align'                           => true,
        'yoda_style'                             => false,
        'no_useless_else'                        => true,
        'no_useless_return'                      => true,
        'phpdoc_order'                           => true,
        'no_extra_blank_lines'                   => ['tokens' => ['break', 'continue', 'curly_brace_block', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'throw', 'use']],
        'phpdoc_to_comment'                      => false,
    ])
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/var/cache/php-cs-fixer.cache')
    ->setFinder($finder)
;
