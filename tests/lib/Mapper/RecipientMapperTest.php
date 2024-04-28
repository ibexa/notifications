<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Notifications\Mapper;

use Ibexa\Contracts\Notifications\Value\Recipent\SymfonyRecipientAdapter;
use Ibexa\Contracts\Notifications\Value\RecipientInterface;
use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Ibexa\Notifications\Mapper\RecipientMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Recipient\Recipient;

final class RecipientMapperTest extends TestCase
{
    private RecipientMapper $mapper;

    public function setUp(): void
    {
        $this->mapper = new RecipientMapper();
    }

    public function testMapToSymfonyRecipient(): void
    {
        $recipient = $this->createMock(Recipient::class);

        $symfonyRecipient = $this->mapper->mapToSymfonyRecipient(
            new SymfonyRecipientAdapter($recipient)
        );

        self::assertSame($recipient, $symfonyRecipient);
    }

    public function testMapToSymfonyRecipientThrowsExceptionOnInvalidRecipient(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->mapper->mapToSymfonyRecipient(
            $this->createMock(RecipientInterface::class)
        );
    }
}
