<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Notifications\SubscriptionResolver;

use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Notifications\SubscriptionResolver\ChainSubscriptionResolver;
use Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface;
use PHPUnit\Framework\TestCase;

final class ChainSubscriptionResolverTest extends TestCase
{
    /**
     * @dataProvider provideForTestResolve
     *
     * @param array<\Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface> $resolvers
     * @param array<string|null> $expectedChannels
     */
    public function testResolve(array $resolvers, array $expectedChannels): void
    {
        $subscriptionResolver = new ChainSubscriptionResolver($resolvers);

        $notification = $this->createMock(NotificationInterface::class);
        $channels = $subscriptionResolver->resolve($notification);

        self::assertSame($expectedChannels, iterator_to_array($channels));
    }

    /**
     * @return iterable<string, array{
     *     array<\Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface|null>,
     *     array<string>,
     * }>
     */
    public function provideForTestResolve(): iterable
    {
        yield 'returns all results' => [
            [
                $this->mockResolver(['sms', 'mail']),
                $this->mockResolver(['push']),
            ],
            ['sms', 'mail', 'push'],
        ];

        yield 'skips empty channels' => [
            [
                $this->mockResolver(['sms', null, null]),
                $this->mockResolver([null, 'push', null]),
            ],
            ['sms', 'push'],
        ];
    }

    /**
     * @param array<string|null> $channels
     *
     * @return \Ibexa\Notifications\SubscriptionResolver\SubscriptionResolverInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockResolver(array $channels): SubscriptionResolverInterface
    {
        $subscriptionResolver = $this->createMock(SubscriptionResolverInterface::class);
        $subscriptionResolver
            ->method('resolve')
            ->willReturn($channels);

        return $subscriptionResolver;
    }
}
