<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Value;

use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

final class ChannelSubscription
{
    private string $notificationType;

    private string $channel;

    public function __construct(string $notificationType, string $channel)
    {
        $this->notificationType = $notificationType;
        $this->channel = $channel;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }
}
