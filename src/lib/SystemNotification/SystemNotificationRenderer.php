<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\SystemNotification;

use Ibexa\Contracts\Core\Repository\Values\Notification\Notification;
use Ibexa\Core\Notification\Renderer\NotificationRenderer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class SystemNotificationRenderer implements NotificationRenderer
{
    private const KEY_ICON = 'icon';
    private const KEY_ROUTE_NAME = 'route_name';
    private const KEY_ROUTE_PARAMS = 'route_params';
    private const KEY_CONTENT = 'content';
    private const KEY_SUBJECT = 'subject';

    private Environment $twig;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function render(Notification $notification): string
    {
        return $this->twig->render(
            '@ibexadesign/notification/system_notification.html.twig',
            [
                'notification' => $notification,
                'icon' => $notification->data[self::KEY_ICON] ?? null,
                'content' => $notification->data[self::KEY_CONTENT] ?? null,
                'subject' => $notification->data[self::KEY_SUBJECT] ?? null,
                'created_at' => $notification->created,
            ]
        );
    }

    public function generateUrl(Notification $notification): ?string
    {
        if (!isset($notification->data[self::KEY_ROUTE_NAME])) {
            return null;
        }

        return $this->urlGenerator->generate(
            $notification->data[self::KEY_ROUTE_NAME],
            $notification->data[self::KEY_ROUTE_PARAMS] ?? []
        );
    }
}
