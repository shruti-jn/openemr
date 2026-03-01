<?php

/**
 * AgentForge Chat Module Bootstrap
 *
 * Registers the AgentForge AI Chat widget on the patient demographics page.
 * The widget embeds the AgentForge FastAPI chat UI via an iframe, scoped to the
 * current patient's pid.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

/**
 * Variables in scope from ModulesApplication::loadCustomModule():
 *   $classLoader      – OpenEMR\Core\ModulesClassLoader
 *   $eventDispatcher   – Symfony\Component\EventDispatcher\EventDispatcherInterface
 */

$classLoader->registerNamespaceIfNotExists(
    'OpenEMR\\Modules\\AgentForgeChat\\',
    __DIR__ . DIRECTORY_SEPARATOR . 'src'
);

$bootstrap = new \OpenEMR\Modules\AgentForgeChat\Bootstrap($eventDispatcher);
$bootstrap->subscribeToEvents();
