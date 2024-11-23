<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\SystemNotification;

use Ibexa\Contracts\Core\Repository\Values\User\UserReference;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\MessageOptionsInterface;

final class SystemMessage implements MessageInterface
{
    public const DEFAULT_TYPE = 'system';

    private UserReference $user;

    private string $type = self::DEFAULT_TYPE;

    /** @var array<string, mixed> */
    private array $context;

    private string $subject = '';

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(UserReference $user, array $context = [])
    {
        $this->user = $user;
        $this->context = $context;
    }

    public function getUser(): UserReference
    {
        return $this->user;
    }

    public function setUser(UserReference $user): void
    {
        $this->user = $user;
    }

    public function getRecipientId(): ?string
    {
        return (string) $this->user->getUserId();
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getTransport(): ?string
    {
        return null;
    }

    public function getOptions(): ?MessageOptionsInterface
    {
        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
