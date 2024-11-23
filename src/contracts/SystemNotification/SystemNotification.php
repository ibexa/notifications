<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\Notifications\SystemNotification;

use Ibexa\Contracts\Notifications\Value\Recipent\UserRecipientInterface;
use Ibexa\Core\MVC\Symfony\Routing\RouteReference;
use Symfony\Component\Notifier\Notification\Notification;

final class SystemNotification extends Notification implements SystemNotificationInterface
{
    private ?string $icon = null;

    private ?RouteReference $route = null;

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    public function getRoute(): ?RouteReference
    {
        return $this->route;
    }

    public function setRoute(?RouteReference $route): void
    {
        $this->route = $route;
    }

    public function setContent(string $content): void
    {
        $this->content($content);
    }

    public function asSystemNotification(UserRecipientInterface $recipient, ?string $transport = null): SystemMessage
    {
        $context = [
            'subject' => $this->getSubject(),
            'content' => $this->getContent(),
        ];

        if ($this->icon !== null) {
            $context['icon'] = $this->icon;
        }

        if ($this->route !== null) {
            $context['route_name'] = $this->route->getRoute();
            $context['route_params'] = $this->route->getParams();
        }

        return new SystemMessage($recipient->getUser(), $context);
    }
}
