<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Notifications\SystemNotification;

use Ibexa\Contracts\Core\Repository\Values\Notification\Notification;
use Ibexa\Core\Notification\Renderer\NotificationRenderer;
use Ibexa\Core\Notification\Renderer\TypedNotificationRendererInterface;
use JMS\TranslationBundle\Annotation\Desc;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class SystemNotificationRenderer implements NotificationRenderer, TypedNotificationRendererInterface
{
    private const KEY_ICON = 'icon';
    private const KEY_ROUTE_NAME = 'route_name';
    private const KEY_ROUTE_PARAMS = 'route_params';
    private const KEY_CONTENT = 'content';
    private const KEY_SUBJECT = 'subject';

    private Environment $twig;

    private UrlGeneratorInterface $urlGenerator;

    private RequestStack $requestStack;

    private TranslatorInterface $translator;

    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack,
        TranslatorInterface $translator
    ) {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function render(Notification $notification): string
    {
        $templateToExtend = '@ibexadesign/account/notifications/list_item.html.twig';

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest !== null && $currentRequest->attributes->getBoolean('render_all')) {
            $templateToExtend = '@ibexadesign/account/notifications/list_item_all.html.twig';
        }

        return $this->twig->render(
            '@ibexadesign/notification/system_notification.html.twig',
            [
                'notification' => $notification,
                'icon' => $notification->data[self::KEY_ICON] ?? null,
                'content' => $notification->data[self::KEY_CONTENT] ?? null,
                'subject' => $notification->data[self::KEY_SUBJECT] ?? null,
                'created_at' => $notification->created,
                'template_to_extend' => $templateToExtend,
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

    public function getTypeLabel(): string
    {
        return /** @Desc("System notification") */
            $this->translator->trans(
                'notifications.notification.system.label',
                [],
                'ibexa_notifications'
            );
    }
}
