<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\SubscriptionResolver;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;

final class ChainSubscriptionResolver implements SubscriptionResolverInterface
{
    /** @var iterable<\Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface> */
    private iterable $resolvers;

    /**
     * @param iterable<\Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface> $resolvers
     */
    public function __construct(iterable $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(NotificationInterface $notification, array $context = []): iterable
    {
        foreach ($this->resolvers as $resolver) {
            $channelSubscriptions = $resolver->resolve($notification, $context);
            foreach ($channelSubscriptions as $channelSubscription) {
                if (null === $channelSubscription) {
                    continue;
                }

                yield $channelSubscription;
            }
        }
    }
}
