<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\Notifications\DependencyInjection\Configuration\Parser;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\AbstractParser;
use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

final class NotificationsConfigParser extends AbstractParser
{
    public const MAPPED_SETTINGS = ['subscriptions'];

    public function addSemanticConfig(NodeBuilder $nodeBuilder): void
    {
        /*
         * Node name has been changed from "notifications" to "notifier" due to
         * collision with Admin UI notifications parser.
         *
         * TODO: Change the name back to "notifications" in the next major version.
         */
        $nodeBuilder
            ->arrayNode('notifier')
                ->children()
                    ->arrayNode('subscriptions')
                        ->info('Mandatory system notifications. Users cannot opt-out from below subscriptions.')
                        ->useAttributeAsKey('notification_type')
                        ->arrayPrototype()
                            ->children()
                                ->arrayNode('channels')
                                    ->cannotBeEmpty()
                                    ->scalarPrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param array<string, array<string, mixed>> $scopeSettings
     */
    public function mapConfig(array &$scopeSettings, $currentScope, ContextualizerInterface $contextualizer): void
    {
        if (empty($scopeSettings['notifier'])) {
            return;
        }

        $settings = $scopeSettings['notifier'];

        foreach (self::MAPPED_SETTINGS as $key) {
            if (!isset($settings[$key])) {
                continue;
            }

            $contextualizer->setContextualParameter(
                sprintf('notifications.%s', $key),
                $currentScope,
                $settings[$key]
            );
        }
    }

    /**
     * @param array<string, mixed> $config
     */
    public function postMap(array $config, ContextualizerInterface $contextualizer): void
    {
        foreach (self::MAPPED_SETTINGS as $setting) {
            $contextualizer->mapConfigArray(sprintf('notifications.%s', $setting), $config);
        }
    }
}
