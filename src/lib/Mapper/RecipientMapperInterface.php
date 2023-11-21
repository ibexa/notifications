<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\RecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface as SymfonyRecipientInterface;

interface RecipientMapperInterface
{
    /**
     * Produces Symfony Notifier's compatible RecipientInterface object.
     *
     * @throws \Ibexa\Contracts\Core\Exception\InvalidArgumentException
     */
    public function mapToSymfonyRecipient(RecipientInterface $recipient): SymfonyRecipientInterface;
}
