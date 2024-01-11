<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\Value\Recipent;

use Ibexa\Contracts\Core\Repository\Values\User\User;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

final class UserRecipient implements EmailRecipientInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getEmail(): string
    {
        return $this->user->email;
    }
}
