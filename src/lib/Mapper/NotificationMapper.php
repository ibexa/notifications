<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\Notification\SymfonyNotificationAdapter;
use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Symfony\Component\Notifier\Notification\Notification;

final class NotificationMapper implements NotificationMapperInterface
{
    public function mapToSymfonyNotification(NotificationInterface $notification): Notification
    {
        if (!$notification instanceof SymfonyNotificationAdapter) {
            throw new InvalidArgumentException(
                '$notification',
                sprintf('This mapper only supports %s objects', SymfonyNotificationAdapter::class),
            );
        }

        return $notification->getNotification();
    }
}
