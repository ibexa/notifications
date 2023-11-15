<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\Value\Recipent;

use Ibexa\Contracts\Notifications\Value\RecipientInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class SymfonyRecipientAdapter implements RecipientInterface
{
    private Recipient $recipient;

    public function __construct(Recipient $recipient)
    {
        $this->recipient = $recipient;
    }

    public function getMail(): string
    {
        return $this->recipient->getEmail();
    }

    public function getPhone(): string
    {
        return $this->recipient->getPhone();
    }

    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }
}
