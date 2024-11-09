<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\SystemNotification;

use Ibexa\Contracts\Notifications\Value\Recipent\UserRecipientInterface;

interface SystemNotificationInterface
{
    public function asSystemNotification(
        UserRecipientInterface $recipient,
        ?string $transport = null
    ): ?SystemMessage;
}
