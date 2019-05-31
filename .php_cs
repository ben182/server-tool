<?php
$finder = \PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor', 'node_modules'])
    ->name('*.php')
    ->name('_ide_helper')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,                                    // Use PSR-2 formatting by default.
        'psr0' => false,                                    // Don't do PSR-0 formatting (implicit under PSR-2).
        'not_operator_with_successor_space' => true,        // Logical NOT operators (!) should have one trailing whitespace.
        'trailing_comma_in_multiline_array' => true,        // PHP multi-line arrays should have a trailing comma.
        'ordered_imports' => ['sortAlgorithm' => 'length'], // Ordering use statements (alphabetically)
        'ordered_class_elements' => true,                   // Order class elements
        'blank_line_before_return' => true,                 // An empty line feed should precede a return statement
        'array_syntax' => ['syntax' => 'short'],            // PHP arrays should use the PHP 5.4 short-syntax.
        'short_scalar_cast' => true,                        // Cast "(boolean)" and "(integer)" should be written as "(bool)" and "(int)". "(double)" and "(real)" as "(float)".
        'single_blank_line_before_namespace' => true,       // An empty line feed should precede the namespace.
        'blank_line_after_opening_tag' => true,             // An empty line feed should follow a PHP open tag.
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'trim_array_spaces' => true,                        // Arrays should be formatted like function/method arguments, without leading or trailing single line space.
        'no_trailing_comma_in_singleline_array' => true,    // PHP single-line arrays should not have a trailing comma.
        'array_indentation' => true,
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'php_unit_construct' => true,
        'phpdoc_order' => true,
        'phpdoc_indent' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'lowercase_cast' => true,
        'magic_constant_casing' => true,
        'lowercase_static_reference' => true,
        'native_function_casing' => true,
    ])
    ->setFinder($finder);
