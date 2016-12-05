<?php

use PhpCsFixer\Config;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Finder;

return Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'combine_consecutive_unsets' => true,
        'array_syntax' => array('syntax' => 'short'),
        'no_extra_consecutive_blank_lines' => array('break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'),
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => false,
        'php_unit_strict' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'psr4' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'braces' => true,
        'elseif' => true,
        'encoding' => true,
        'function_declaration' => true,
        'include' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'method_argument_space' => true,
        'no_blank_lines_after_class_opening' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_no_access' => true,
        'phpdoc_scalar' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_var_without_name' => true,
        'self_accessor' => true,
        'single_blank_line_before_namespace' => true,
        'single_line_after_imports' => true,
        'single_quote' => true,
        'trim_array_spaces' => false,
        'blank_line_after_opening_tag' => true,
        'concat_space' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_empty_statement' => true,
        'simplified_null_return' => true,
        'single_blank_line_at_eof' => true,
        'no_extra_consecutive_blank_lines' => true,
        'no_spaces_after_function_name' => true,
        'no_alias_functions' => true,
        'blank_line_after_namespace' => true,
        'line_ending' => true,
        'no_trailing_comma_in_list_call' => true,
        'not_operator_with_successor_space' => true,
        'trailing_comma_in_multiline_array' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'single_import_per_statement' => true,
        'no_leading_namespace_whitespace' => true,
        'no_blank_lines_after_phpdoc' => true,
        'object_operator_without_whitespace' => true,
        'binary_operator_spaces' => true,
        'no_spaces_inside_parenthesis' => true,
        'phpdoc_no_package' => true,
        'phpdoc_summary' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => false,
        'no_leading_import_slash' => true,
        'no_extra_consecutive_blank_lines' => true,
        'blank_line_before_return' => true,
        'no_short_echo_tag' => true,
        'full_opening_tag' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'cast_spaces' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'no_trailing_whitespace' => true,
        'binary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        'no_unused_imports' => false,
        'visibility_required' => true,
        'no_whitespace_in_blank_line' => true,
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests/Fixtures')
            ->exclude('bootstrap/cache')
            ->exclude('storage')
            ->exclude('vendor')
            ->exclude('resources')
            ->in(__DIR__)
    )
    ;
