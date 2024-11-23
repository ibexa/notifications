<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Notifications\Channel;

use Ibexa\Contracts\Core\Repository\NotificationService;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Notification\CreateStruct;
use Ibexa\Contracts\Core\Repository\Values\Notification\Notification as SystemNotification;
use Ibexa\Contracts\Core\Repository\Values\User\UserReference;
use Ibexa\Contracts\Notifications\SystemNotification\SystemMessage;
use Ibexa\Contracts\Notifications\SystemNotification\SystemNotificationInterface;
use Ibexa\Contracts\Notifications\Value\Recipent\UserRecipientInterface;
use Ibexa\Notifications\SystemNotification\SystemNotificationChannel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

final class SystemNotificationChannelTest extends TestCase
{
    private const EXAMPLE_USER_ID = 12;

    /** @var \Ibexa\Contracts\Core\Repository\Repository&\PHPUnit\Framework\MockObject\MockObject */
    private Repository $repository;

    /** @var \Ibexa\Contracts\Core\Repository\NotificationService&\PHPUnit\Framework\MockObject\MockObject */
    private NotificationService $notificationService;

    private SystemNotificationChannel $channel;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(Repository::class);
        $this->notificationService = $this->createMock(NotificationService::class);

        $this->channel = new SystemNotificationChannel($this->repository, $this->notificationService);
    }

    /**
     * @dataProvider dataProviderForTestSupports
     */
    public function testSupports(Notification $notification, RecipientInterface $recipient, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, $this->channel->supports($notification, $recipient));
    }

    /**
     * @return iterable<string, array{\Symfony\Component\Notifier\Notification\Notification, \Symfony\Component\Notifier\Recipient\RecipientInterface, bool}>
     */
    public function dataProviderForTestSupports(): iterable
    {
        yield 'supported' => [
            $this->createSupportedNotification(),
            $this->createSupportedRecipient(),
            true,
        ];

        yield 'unsupported recipient' => [
            $this->createSupportedNotification(),
            $this->createMock(RecipientInterface::class),
            false,
        ];

        yield 'unsupported notification' => [
            $this->createMock(Notification::class),
            $this->createSupportedRecipient(),
            false,
        ];
    }

    public function testNotify(): void
    {
        $expectedCreateStruct = new CreateStruct();
        $expectedCreateStruct->ownerId = self::EXAMPLE_USER_ID;
        $expectedCreateStruct->data = ['foo' => 'bar'];
        $expectedCreateStruct->type = 'example';

        $this->notificationService
            ->expects(self::once())
            ->method('createNotification')
            ->with($expectedCreateStruct)
            ->willReturn(new SystemNotification());

        $user = $this->createMock(UserReference::class);
        $user->method('getUserId')->willReturn(self::EXAMPLE_USER_ID);

        $message = new SystemMessage($user, ['foo' => 'bar']);
        $message->setType('example');

        $this->channel->notify(
            $this->createSupportedNotification($message),
            $this->createSupportedRecipient(self::EXAMPLE_USER_ID)
        );
    }

    /**
     * @return \Symfony\Component\Notifier\Notification\Notification&\Ibexa\Contracts\Notifications\SystemNotification\SystemNotificationInterface
     */
    private function createSupportedNotification(?SystemMessage $message = null): Notification
    {
        return new class($message) extends Notification implements SystemNotificationInterface {
            private ?SystemMessage $message;

            public function __construct(?SystemMessage $message)
            {
                parent::__construct('example');

                $this->message = $message;
            }

            public function asSystemNotification(
                UserRecipientInterface $recipient,
                ?string $transport = null
            ): ?SystemMessage {
                return $this->message;
            }
        };
    }

    private function createSupportedRecipient(?int $userId = null): UserRecipientInterface
    {
        $recipient = $this->createMock(UserRecipientInterface::class);
        if ($userId !== null) {
            $user = $this->createMock(UserReference::class);
            $user->method('getUserId')->willReturn($userId);

            $recipient->method('getUser')->willReturn($user);
        }

        return $recipient;
    }
}
