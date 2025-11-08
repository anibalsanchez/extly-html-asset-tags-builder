<?php

declare(strict_types=1);

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2024 Extly, CB. All rights reserved.
 * @license     https://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

use Rector\Config\RectorConfig;
use Utils\Rector\Rector\LegacyCallToJClassToJModernRector;

require_once '/home/anibalsanchez/7_Projects/Platform/rector-rule-joomla-legacy-to-joomla-modern/src/Rector/LegacyCallToJClassToJModernRector.php';

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        '*/vendor/*',
        '*/node_modules/*',
        '*Legacy*',
    ])
    ->withPhpSets(php74: true)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        earlyReturn: true,
        instanceOf: true,
        naming: true,
        // TODO: Enable typed properties
        typeDeclarations: false,
    )
    ->withRules([
        LegacyCallToJClassToJModernRector::class,
    ]);
