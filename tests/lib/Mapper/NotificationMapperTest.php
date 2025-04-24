<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\Notification\SymfonyNotificationAdapter;
use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Ibexa\Notifications\Mapper\NotificationMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Notification\Notification;

final class NotificationMapperTest extends TestCase
{
    private NotificationMapper $mapper;

    public function setUp(): void
    {
        $this->mapper = new NotificationMapper();
    }

    public function testMapToSymfonyNotificationForSymfonyAdapter(): void
    {
        $notification = $this->createMock(Notification::class);

        $symfonyNotification = $this->mapper->mapToSymfonyNotification(
            new SymfonyNotificationAdapter($notification)
        );

        $this->assertSame($notification, $symfonyNotification);
    }

    public function testMapToSymfonyNotificationForNativeNotification(): void
    {
        $notification = new NativeNotification();

        $symfonyNotification = $this->mapper->mapToSymfonyNotification($notification);

        $this->assertSame($notification, $symfonyNotification);
    }

    public function testMapToSymfonyNotificationThrowsExceptionOnInvalidNotification(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->mapper->mapToSymfonyNotification(
            $this->createMock(NotificationInterface::class)
        );
    }
}
