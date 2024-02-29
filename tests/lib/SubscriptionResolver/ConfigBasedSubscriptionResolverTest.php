<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Notifications\SubscriptionResolver;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Ibexa\Contracts\Notifications\Value\NotificationInterface;
use Ibexa\Notifications\SubscriptionResolver\ConfigBasedSubscriptionResolver;
use Ibexa\Notifications\Value\ChannelSubscription;
use PHPUnit\Framework\TestCase;

/**
 * @phpstan-type TSubscriptionsConfig array<string, array{channels: array<string>}>
 */
final class ConfigBasedSubscriptionResolverTest extends TestCase
{
    private ConfigBasedSubscriptionResolver $resolver;

    /** @var \Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface|\PHPUnit\Framework\MockObject\MockObject */
    private ConfigResolverInterface $configResolver;

    public function setUp(): void
    {
        $this->configResolver = $this->createMock(ConfigResolverInterface::class);
        $this->resolver = new ConfigBasedSubscriptionResolver($this->configResolver);
    }

    /**
     * @dataProvider provideForTestResolve
     *
     * @phpstan-param TSubscriptionsConfig $subscriptions
     *
     * @param array<string> $expectedChannels
     */
    public function testResolve(
        array $subscriptions,
        string $notificationType,
        array $expectedChannels
    ): void {
        $this->configResolver
            ->method('getParameter')
            ->willReturn($subscriptions);

        $notification = $this->createMock(NotificationInterface::class);
        $notification
            ->method('getType')
            ->willReturn($notificationType);

        $subscriptions = $this->resolver->resolve($notification);
        $subscriptionsArray = iterator_to_array($subscriptions);

        $this->assertCount(count($expectedChannels), $subscriptionsArray);
        $this->assertContainsOnlyInstancesOf(ChannelSubscription::class, $subscriptionsArray);

        $i = 0;
        foreach ($subscriptionsArray as $channelSubscription) {
            $this->assertSame($notificationType, $channelSubscription->getNotificationType());
            $this->assertSame($expectedChannels[$i++], $channelSubscription->getChannel());
        }
    }

    /**
     * @return iterable<string, array{
     *      TSubscriptionsConfig,
     *      string,
     *      array<string>,
     *  }>
     */
    public function provideForTestResolve(): iterable
    {
        $config = [
            NotificationInterface::class => [
                'channels' => ['sms', 'mail', 'push'],
            ],
            '\\Vendor\\Package\\Notification\\ActionSuccess' => [
                'channels' => ['browser'],
            ],
        ];

        yield 'NotificationInterface type' => [
            $config,
            NotificationInterface::class,
            ['sms', 'mail', 'push'],
        ];

        yield '\\Vendor\\Package\\Notification\\ActionSuccess type' => [
            $config,
            '\\Vendor\\Package\\Notification\\ActionSuccess',
            ['browser'],
        ];

        yield 'no subscriptions when not configured' => [
            $config,
            '\\Vendor\\Package\\Notification\\NotConfigured',
            [],
        ];
    }
}
