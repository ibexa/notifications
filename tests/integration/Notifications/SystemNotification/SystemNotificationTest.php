<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Integration\Notifications\Notifications\SystemNotification;

use Ibexa\Contracts\Notifications\SystemNotification\SystemMessage;
use Ibexa\Contracts\Notifications\SystemNotification\SystemNotification;
use Ibexa\Contracts\Notifications\Value\Recipent\UserRecipientInterface;
use Ibexa\Core\MVC\Symfony\Routing\RouteReference;
use Ibexa\Core\Repository\Values\User\UserReference;
use PHPUnit\Framework\TestCase;

final class SystemNotificationTest extends TestCase
{
    private const EXAMPLE_USER_ID = 1;

    public function testAsSystemNotification(): void
    {
        $notification = new SystemNotification('Hello World', ['ibexa']);
        $notification->setIcon('icon');
        $notification->setContent('It works!');
        $notification->setRoute(new RouteReference('example', ['foo' => 'bar', 'baz' => 'qux']));

        $user = new UserReference(self::EXAMPLE_USER_ID);

        $recipient = $this->createMock(UserRecipientInterface::class);
        $recipient->method('getUser')->willReturn($user);

        $systemMessage = $notification->asSystemNotification($recipient);

        self::assertNotNull($systemMessage);
        self::assertSame($user, $systemMessage->getUser());
        self::assertEquals(SystemMessage::DEFAULT_TYPE, $systemMessage->getType());
        self::assertEquals([
            'icon' => 'icon',
            'subject' => 'Hello World',
            'content' => 'It works!',
            'route_name' => 'example',
            'route_params' => ['foo' => 'bar', 'baz' => 'qux'],
        ], $systemMessage->getContext());
    }
}
