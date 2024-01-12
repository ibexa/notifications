<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\SubscriptionResolver;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Notifications\Value\ChannelSubscription;

final class ConfigBasedSubscriptionResolver implements SubscriptionResolverInterface
{
    private ConfigResolverInterface $configResolver;

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function resolve(NotificationInterface $notification, array $context = []): iterable
    {
        $config = $this->configResolver->getParameter('notifications.subscriptions');
        $notificationType = $notification->getType();

        if (!array_key_exists($notificationType, $config)) {
            return;
        }

        foreach ($config[$notificationType]['channels'] as $channel) {
            yield new ChannelSubscription($notificationType, $channel);
        }
    }
}
