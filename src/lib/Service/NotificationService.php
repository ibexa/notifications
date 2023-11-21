<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Service;

use Ibexa\Contracts\Notifications\Service\NotificationServiceInterface;
use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Notifications\Mapper\NotificationMapperInterface;
use Ibexa\Notifications\Mapper\RecipientMapperInterface;
use Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface;
use Ibexa\Notifications\Value\ChannelSubscription;
use Symfony\Component\Notifier\NotifierInterface;

final class NotificationService implements NotificationServiceInterface
{
    private NotifierInterface $notifier;

    private SubscriptionResolverInterface $subscriptionResolver;

    private NotificationMapperInterface $notificationMapper;

    private RecipientMapperInterface $recipientMapper;

    public function __construct(
        NotifierInterface $notifier,
        SubscriptionResolverInterface $subscriptionResolver,
        NotificationMapperInterface $notificationMapper,
        RecipientMapperInterface $recipientMapper
    ) {
        $this->notifier = $notifier;
        $this->subscriptionResolver = $subscriptionResolver;
        $this->notificationMapper = $notificationMapper;
        $this->recipientMapper = $recipientMapper;
    }

    /**
     * @param array<\Ibexa\Contracts\Notifications\Value\RecipientInterface> $recipients
     */
    public function send(NotificationInterface $notification, array $recipients = []): void
    {
        $channels = array_map(
            static fn (
                ChannelSubscription $channelSubscription
            ): string => $channelSubscription->getChannel(),
            array_filter(
                iterator_to_array($this->subscriptionResolver->resolve($notification))
            )
        );

        if (empty($channels)) {
            return;
        }

        $symfonyRecipients = array_map(
            [$this->recipientMapper, 'mapToSymfonyRecipient'],
            $recipients
        );

        $symfonyNotification = $this->notificationMapper->mapToSymfonyNotification($notification);
        $symfonyNotification->channels($channels);

        $this->notifier->send($symfonyNotification, ...$symfonyRecipients);
    }
}
