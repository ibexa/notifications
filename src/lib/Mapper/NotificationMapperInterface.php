<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;

interface NotificationMapperInterface
{
    /**
     * Produces Symfony Notifier's compatible Notification object.
     *
     * @throws \Ibexa\Contracts\Core\Exception\InvalidArgumentException
     */
    public function mapToSymfonyNotification(NotificationInterface $notification): Notification;
}
