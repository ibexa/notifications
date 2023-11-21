<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\Value\Recipent;

use Ibexa\Contracts\Notifications\Value\RecipientInterface;
use Ibexa\Core\Base\Exceptions\BadStateException;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface as SymfonyRecipentInterface;
use Symfony\Component\Notifier\Recipient\SmsRecipientInterface;

final class SymfonyRecipientAdapter implements RecipientInterface
{
    private SymfonyRecipentInterface $recipient;

    public function __construct(SymfonyRecipentInterface $recipient)
    {
        $this->recipient = $recipient;
    }

    public function getMail(): string
    {
        if (!$this->recipient instanceof EmailRecipientInterface) {
            throw new BadStateException(
                '$recipient',
                sprintf(
                    'Internal %s instance does not implement %s',
                    SymfonyRecipentInterface::class,
                    EmailRecipientInterface::class,
                ),
            );
        }

        return $this->recipient->getEmail();
    }

    public function getPhone(): string
    {
        if (!$this->recipient instanceof SmsRecipientInterface) {
            throw new BadStateException(
                '$recipient',
                sprintf(
                    'Internal %s instance does not implement %s',
                    SymfonyRecipentInterface::class,
                    SmsRecipientInterface::class,
                ),
            );
        }

        return $this->recipient->getPhone();
    }

    public function getRecipient(): SymfonyRecipentInterface
    {
        return $this->recipient;
    }
}
