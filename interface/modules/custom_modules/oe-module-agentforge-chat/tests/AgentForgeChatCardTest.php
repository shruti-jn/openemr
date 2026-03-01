<?php

/**
 * AgentForgeChatCard Unit Tests
 *
 * Verifies that the AgentForge chat card builds correct template variables,
 * in particular the URLs passed to the Twig template for the iframe embed
 * and the "Open in new window" link.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

declare(strict_types=1);

namespace OpenEMR\Tests\Modules\AgentForgeChat;

use OpenEMR\Modules\AgentForgeChat\AgentForgeChatCard;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AgentForgeChatCardTest extends TestCase
{
    private function makeCard(int $pid, array $globals = []): AgentForgeChatCard
    {
        // Seed $GLOBALS so buildTemplateVars() picks them up.
        foreach ($globals as $k => $v) {
            $GLOBALS[$k] = $v;
        }

        // Supply a mock dispatcher to avoid OEGlobalsBag dependency.
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        return new AgentForgeChatCard($pid, ['dispatcher' => $dispatcher]);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['agentforge_url'], $GLOBALS['agentforge_iframe_height']);
        parent::tearDown();
    }

    // -- embed URL -----------------------------------------------------------

    public function testEmbedUrlContainsPidAndEmbedFlag(): void
    {
        $card = $this->makeCard(42);
        $vars = $card->getTemplateVariables();

        $parts = parse_url($vars['embedUrl']);
        parse_str($parts['query'], $query);

        $this->assertSame('1', $query['embed']);
        $this->assertSame('42', $query['pid']);
    }

    // -- new-window URL ------------------------------------------------------

    public function testNewWindowUrlContainsPid(): void
    {
        $card = $this->makeCard(16);
        $vars = $card->getTemplateVariables();

        $parts = parse_url($vars['newWindowUrl']);
        parse_str($parts['query'], $query);

        $this->assertSame('16', $query['pid']);
    }

    public function testNewWindowUrlDoesNotContainEmbedFlag(): void
    {
        $card = $this->makeCard(16);
        $vars = $card->getTemplateVariables();

        $parts = parse_url($vars['newWindowUrl']);
        parse_str($parts['query'], $query);

        $this->assertArrayNotHasKey('embed', $query);
    }

    // -- custom agentforge_url global ----------------------------------------

    public function testCustomAgentUrlIsUsed(): void
    {
        $card = $this->makeCard(7, [
            'agentforge_url' => 'https://agent.example.com',
        ]);
        $vars = $card->getTemplateVariables();

        $this->assertSame('https://agent.example.com', $vars['agentUrl']);
        $this->assertStringStartsWith('https://agent.example.com?', $vars['embedUrl']);
        $this->assertStringStartsWith('https://agent.example.com?', $vars['newWindowUrl']);
    }

    public function testTrailingSlashIsTrimmedFromAgentUrl(): void
    {
        $card = $this->makeCard(1, [
            'agentforge_url' => 'https://agent.example.com/',
        ]);
        $vars = $card->getTemplateVariables();

        // The embed and new-window URLs should not have a double-slash before '?'.
        $this->assertStringStartsWith('https://agent.example.com?', $vars['embedUrl']);
        $this->assertStringStartsWith('https://agent.example.com?', $vars['newWindowUrl']);
    }

    // -- default URL ---------------------------------------------------------

    public function testDefaultUrlIsLocalhost9010(): void
    {
        $card = $this->makeCard(1);
        $vars = $card->getTemplateVariables();

        $this->assertSame('http://localhost:9010', $vars['agentUrl']);
    }

    // -- pid template variable -----------------------------------------------

    public function testPidTemplateVariableMatchesConstructorArg(): void
    {
        $card = $this->makeCard(99);
        $vars = $card->getTemplateVariables();

        $this->assertSame(99, $vars['pid']);
    }
}
