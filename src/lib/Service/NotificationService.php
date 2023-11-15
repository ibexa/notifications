<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Service;

use Ibexa\Contracts\Notifications\Service\NotificationServiceInterface;
use Ibexa\Contracts\Notifications\Value\Notification\SymfonyNotificationAdapter;
use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Contracts\Notifications\Value\Recipent\SymfonyRecipientAdapter;
use Ibexa\Contracts\Notifications\Value\RecipientInterface;
use Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface;
use Ibexa\Notifications\Value\ChannelSubscription;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

final class NotificationService implements NotificationServiceInterface
{
    private NotifierInterface $notifier;

    private SubscriptionResolverInterface $subscriptionResolver;

    public function __construct(
        NotifierInterface $notifier,
        SubscriptionResolverInterface $subscriptionResolver
    ) {
        $this->notifier = $notifier;
        $this->subscriptionResolver = $subscriptionResolver;
    }

    /**
     * @param array<\Ibexa\Contracts\Notifications\Value\RecipientInterface> $recipients
     */
    public function send(NotificationInterface $notification, array $recipients = []): void
    {
        $channels = array_map(
            static fn (ChannelSubscription $channelSubscription): string => $channelSubscription->getChannel(),
            array_filter(iterator_to_array($this->subscriptionResolver->resolve($notification)))
        );

        if (empty($channels)) {
            return;
        }

        $symfonyRecipients = $this->getSymfonyRecipients($recipients);
        $symfonyNotification = $notification instanceof SymfonyNotificationAdapter
            ? $notification->getNotification()
            : new Notification();

        $symfonyNotification->channels($channels);

        $this->notifier->send(
            $symfonyNotification,
            ...$symfonyRecipients,
        );
    }

    /**
     * @param array<\Ibexa\Contracts\Notifications\Value\RecipientInterface> $recipients
     *
     * @return array<\Symfony\Component\Notifier\Recipient\RecipientInterface>
     */
    private function getSymfonyRecipients(array $recipients): array
    {
        $symfonyRecipients = [];
        foreach ($recipients as $recipient) {
            $symfonyRecipients[] = $recipient instanceof SymfonyRecipientAdapter
                ? $recipient->getRecipient()
                : new Recipient(
                    $recipient->getMail(),
                    $recipient->getPhone(),
                );
        }

        return $symfonyRecipients;
    }
}
