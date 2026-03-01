<?php

/**
 * PHPUnit bootstrap for the AgentForge Chat module.
 *
 * Loads Composer's autoloader from the main OpenEMR vendor directory
 * and registers the module's own PSR-4 namespace so that the card
 * class can be resolved without a running OpenEMR instance.
 */

declare(strict_types=1);

// Tell OpenEMR's xl() to skip database translation lookups.
$GLOBALS['disable_translation'] = true;

// Stub getUserSetting() – the real implementation lives in library/user.inc.php
// which is NOT auto-loaded by Composer, so we can provide a test double here.
if (!function_exists('getUserSetting')) {
    function getUserSetting(string $label, $user = null, int $defaultUser = 0): ?string
    {
        return '0';
    }
}

// Composer autoloader (provides PHPUnit, Symfony EventDispatcher, CardModel, etc.)
$autoloader = require __DIR__ . '/../../../../../vendor/autoload.php';

// Register this module's namespace (mirrors what openemr.bootstrap.php does at runtime).
$autoloader->addPsr4(
    'OpenEMR\\Modules\\AgentForgeChat\\',
    __DIR__ . '/../src'
);

// Register the test namespace.
$autoloader->addPsr4(
    'OpenEMR\\Tests\\Modules\\AgentForgeChat\\',
    __DIR__
);
