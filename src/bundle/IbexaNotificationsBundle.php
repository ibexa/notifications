<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\Notifications;

use Ibexa\Bundle\ConnectorActito\DependencyInjection\Configuration\Parser\ConnectorConfigParser;
use Ibexa\Bundle\Notifications\DependencyInjection\Configuration\Parser\NotificationsConfigParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class IbexaNotificationsBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        /** @var \Ibexa\Bundle\Core\DependencyInjection\IbexaCoreExtension $core */
        $core = $container->getExtension('ibexa');

        $core->addConfigParser(new NotificationsConfigParser());
    }
}
