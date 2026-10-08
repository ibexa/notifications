<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\SubscriptionResolver;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Notifications\Value\ChannelSubscription;

interface SubscriptionResolverInterface
{
    /**
     * @param array<string, mixed> $context
     *
     * @return \Iterator<ChannelSubscription|null>
     */
    public function resolve(
        NotificationInterface $notification,
        array $context = []
    ): iterable;
}
