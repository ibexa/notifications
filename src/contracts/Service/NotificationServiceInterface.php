<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\Service;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;

interface NotificationServiceInterface
{
    /**
     * @param array<\Ibexa\Contracts\Notifications\Value\RecipientInterface> $recipients
     */
    public function send(NotificationInterface $notification, array $recipients = []): void;
}
