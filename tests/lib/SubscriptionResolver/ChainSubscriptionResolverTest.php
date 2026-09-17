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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ChainSubscriptionResolverTest extends TestCase
{
    /**
     * @param array<array<string|null>> $resolverChannels
     * @param array<string|null> $expectedChannels
     */
    #[DataProvider('provideForTestResolve')]
    public function testResolve(array $resolverChannels, array $expectedChannels): void
    {
        $resolvers = array_map(
            fn (array $channels): SubscriptionResolverInterface => $this->mockResolver($channels),
            $resolverChannels
        );

        $subscriptionResolver = new ChainSubscriptionResolver($resolvers);

        $notification = $this->createStub(NotificationInterface::class);
        $channels = $subscriptionResolver->resolve($notification);

        self::assertSame($expectedChannels, iterator_to_array($channels));
    }

    /**
     * @return iterable<string, array{
     *     array<array<string|null>>,
     *     array<string>,
     * }>
     */
    public static function provideForTestResolve(): iterable
    {
        yield 'returns all results' => [
            [
                ['sms', 'mail'],
                ['push'],
            ],
            ['sms', 'mail', 'push'],
        ];

        yield 'skips empty channels' => [
            [
                ['sms', null, null],
                [null, 'push', null],
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
