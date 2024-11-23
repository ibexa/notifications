<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\Value\Recipent;

use Ibexa\Contracts\Core\Repository\Values\User\UserReference;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

interface UserRecipientInterface extends RecipientInterface
{
    public function getUser(): UserReference;
}
