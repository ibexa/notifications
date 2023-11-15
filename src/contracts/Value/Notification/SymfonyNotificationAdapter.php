<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\Value\Notification;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;

final class SymfonyNotificationAdapter implements NotificationInterface
{
    private Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function getType(): string
    {
        return get_class($this->notification);
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
