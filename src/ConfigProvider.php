<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole;

use FrankVerhoeven\Expressive\Swoole\Event\ConnectionClosed;
use FrankVerhoeven\Expressive\Swoole\Event\ConnectionOpened;
use FrankVerhoeven\Expressive\Swoole\Event\MessageReceived;
use Prooph\ServiceBus\Container\EventBusFactory;
use Prooph\ServiceBus\EventBus;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Websocket\Server as SwooleWebsocketServer;
use Zend\Expressive\Swoole\ServerRequestSwooleFactory;
use Zend\HttpHandlerRunner\RequestHandlerRunner;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    ServerRequestInterface::class => ServerRequestSwooleFactory::class,
                    RequestHandlerRunner::class => RequestHandlerSwooleWebsocketRunnerFactory::class,
                    SwooleWebsocketServer::class => SwooleWebsocketServerFactory::class,
                    EventBus::class => EventBusFactory::class,
                ],
            ],
            'prooph' => [
                'service_bus' => [
                    'event_bus' => [
                        'router' => [
                            'routes' => [
//                                ConnectionOpened::class => [],
//                                ConnectionClosed::class => [],
//                                MessageReceived::class => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
