<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\Recipent\SymfonyRecipientAdapter;
use Ibexa\Contracts\Notifications\Value\RecipientInterface;
use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Symfony\Component\Notifier\Recipient\RecipientInterface as SymfonyRecipientInterface;

final class RecipientMapper implements RecipientMapperInterface
{
    public function mapToSymfonyRecipient(RecipientInterface $recipient): SymfonyRecipientInterface
    {
        if (!$recipient instanceof SymfonyRecipientAdapter) {
            throw new InvalidArgumentException(
                '$recipient',
                sprintf('This mapper only supports %s objects', SymfonyRecipientAdapter::class),
            );
        }

        return $recipient->getRecipient();
    }
}
