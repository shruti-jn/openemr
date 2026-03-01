<?php

/**
 * AgentForge Chat Card
 *
 * CardModel implementation that renders the AgentForge AI assistant chat
 * widget on the patient demographics page. The widget embeds the AgentForge
 * FastAPI frontend via an iframe, passing the current patient's pid so that
 * the agent is scoped to the active patient context.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Modules\AgentForgeChat;

use OpenEMR\Events\Patient\Summary\Card\CardModel;

class AgentForgeChatCard extends CardModel
{
    /**
     * Unique identifier for this card (must be unique across all cards
     * added to the same SectionEvent).
     */
    private const CARD_ID = 'agentforge_chat';

    /**
     * Path to the Twig template (resolved from the OpenEMR template dirs
     * and custom module template directories).
     */
    private const TEMPLATE_FILE = 'agentforge/agentforge_chat.html.twig';

    /**
     * Default URL where the AgentForge FastAPI service is running.
     * Override via $GLOBALS['agentforge_url'] if set in OpenEMR config.
     */
    private const DEFAULT_AGENT_URL = 'http://localhost:9010';

    /**
     * Default height (in pixels) for the embedded iframe.
     */
    private const DEFAULT_IFRAME_HEIGHT = 480;

    private int $pid;

    public function __construct(int $pid, array $opts = [])
    {
        $this->pid = $pid;
        $opts = $this->setupOpts($opts);
        parent::__construct($opts);
    }

    /**
     * Merge module defaults into the options array expected by CardModel.
     */
    private function setupOpts(array $opts): array
    {
        $opts['acl']                = $opts['acl'] ?? ['patients', 'demo'];
        $opts['title']              = $opts['title'] ?? xl('AI Assistant');
        $opts['identifier']         = self::CARD_ID;
        $opts['templateFile']       = self::TEMPLATE_FILE;
        $opts['initiallyCollapsed'] = $opts['initiallyCollapsed'] ?? (getUserSetting(self::CARD_ID . '_expand') == 0);
        $opts['collapse']           = true;
        $opts['edit']               = false;
        $opts['add']                = false;
        $opts['templateVariables']  = $this->buildTemplateVars();

        return $opts;
    }

    /**
     * Build the variables passed to the Twig template.
     */
    private function buildTemplateVars(): array
    {
        global $GLOBALS;

        $agentUrl = getenv('AGENTFORGE_URL') ?: ($GLOBALS['agentforge_url'] ?? self::DEFAULT_AGENT_URL);
        $iframeHeight = (int) ($GLOBALS['agentforge_iframe_height'] ?? self::DEFAULT_IFRAME_HEIGHT);

        $baseUrl = rtrim($agentUrl, '/');

        // Build the embed URL with query params so the React frontend
        // can: (a) hide the sidebar, and (b) know which patient to scope to.
        // data_source=db ensures patient lookup uses direct DB query (FHIR API
        // uses UUIDs, not integer pids, so direct /fhir/Patient/{pid} fails).
        $embedUrl = $baseUrl . '?' . http_build_query([
            'embed'       => '1',
            'pid'         => $this->pid,
            'data_source' => 'db',
        ]);

        // Standalone URL preserves patient context but omits the embed flag
        // so the full UI (sidebar, etc.) is shown when opened in a new window.
        $newWindowUrl = $baseUrl . '?' . http_build_query([
            'pid'         => $this->pid,
            'data_source' => 'db',
        ]);

        return [
            'pid'          => $this->pid,
            'agentUrl'     => $agentUrl,
            'embedUrl'     => $embedUrl,
            'newWindowUrl' => $newWindowUrl,
            'iframeHeight' => $iframeHeight,
        ];
    }
}
