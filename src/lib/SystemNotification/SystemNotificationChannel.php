<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\SystemNotification;

use Ibexa\Contracts\Core\Repository\NotificationService;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Notification\CreateStruct;
use Ibexa\Contracts\Notifications\SystemNotification\SystemNotificationInterface;
use Ibexa\Contracts\Notifications\Value\Recipent\UserRecipientInterface;
use Symfony\Component\Notifier\Channel\ChannelInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;
use Throwable;

final class SystemNotificationChannel implements ChannelInterface
{
    private Repository $repository;

    private NotificationService $notificationService;

    public function __construct(Repository $repository, NotificationService $notificationService)
    {
        $this->repository = $repository;
        $this->notificationService = $notificationService;
    }

    /**
     * @param \Symfony\Component\Notifier\Notification\Notification&\Ibexa\Contracts\Notifications\SystemNotification\SystemNotificationInterface $notification
     * @param \Ibexa\Contracts\Notifications\Value\Recipent\UserRecipientInterface $recipient
     */
    public function notify(Notification $notification, RecipientInterface $recipient, ?string $transportName = null): void
    {
        $message = $notification->asSystemNotification($recipient, $transportName);
        if ($message === null) {
            return;
        }

        $createStruct = new CreateStruct();
        $createStruct->ownerId = $message->getUser()->getUserId();
        $createStruct->type = $message->getType();
        $createStruct->data = $message->getContext();

        $this->repository->beginTransaction();
        try {
            $this->notificationService->createNotification($createStruct);
            $this->repository->commit();
        } catch (Throwable $e) {
            $this->repository->rollback();
            throw $e;
        }
    }

    public function supports(Notification $notification, RecipientInterface $recipient): bool
    {
        return $notification instanceof SystemNotificationInterface && $recipient instanceof UserRecipientInterface;
    }
}
