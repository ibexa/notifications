<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;

/**
 * Example notification class for testing purposes.
 *
 * @internal
 */
final class NativeNotification extends Notification implements NotificationInterface
{
    public function getType(): string
    {
        return self::class;
    }
}
