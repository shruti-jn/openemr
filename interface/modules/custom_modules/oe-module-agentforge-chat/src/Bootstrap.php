<?php

/**
 * AgentForge Chat Module – Bootstrap
 *
 * Subscribes to OpenEMR's patient-summary card events so the AgentForge
 * chat widget appears on the demographics page. Also registers the module's
 * Twig template directory so the card template can be resolved.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Modules\AgentForgeChat;

use OpenEMR\Events\Core\TwigEnvironmentEvent;
use OpenEMR\Events\Patient\Summary\Card\SectionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Loader\FilesystemLoader;

class Bootstrap
{
    private EventDispatcherInterface $eventDispatcher;

    /** Absolute path to this module's templates/ directory. */
    private string $templateDir;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->templateDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates';
    }

    /**
     * Register all event listeners for this module.
     */
    public function subscribeToEvents(): void
    {
        // Register our template directory with Twig
        $this->eventDispatcher->addListener(
            TwigEnvironmentEvent::EVENT_CREATED,
            [$this, 'onTwigCreated']
        );

        // Inject the chat card into the patient demographics page
        $this->eventDispatcher->addListener(
            SectionEvent::EVENT_HANDLE,
            [$this, 'onSectionRender']
        );
    }

    /**
     * Prepend this module's templates/ directory so Twig can resolve
     * agentforge/agentforge_chat.html.twig.
     */
    public function onTwigCreated(TwigEnvironmentEvent $event): void
    {
        $twig = $event->getTwigEnvironment();
        $loader = $twig->getLoader();
        if ($loader instanceof FilesystemLoader) {
            $loader->prependPath($this->templateDir);
        }
    }

    /**
     * Inject the AgentForge chat card into the "secondary" section of the
     * patient demographics dashboard (right-hand column).
     */
    public function onSectionRender(SectionEvent $event): void
    {
        // Only add the card to the secondary (right-column) section
        if ($event->getSection() !== 'secondary') {
            return;
        }

        $pid = $_SESSION['pid'] ?? null;
        if (empty($pid)) {
            return;
        }

        $card = new AgentForgeChatCard((int) $pid);
        // Position 0 = prepend (top of the secondary column)
        $event->addCard($card, 0);
    }
}
