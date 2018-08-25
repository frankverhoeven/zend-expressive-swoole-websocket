<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole;

use Prooph\ServiceBus\EventBus;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Swoole\Websocket\Server as SwooleWebsocketServer;
use Zend\Expressive\ApplicationPipeline;
use Zend\Expressive\Response\ServerRequestErrorResponseGenerator;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
class RequestHandlerSwooleWebsocketRunnerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RequestHandlerSwooleWebsocketRunner
     */
    public function __invoke(ContainerInterface $container) : RequestHandlerSwooleWebsocketRunner
    {
        $config = $container->get('config');
        $logger = $container->has(LoggerInterface::class)
            ? $container->get(LoggerInterface::class)
            : null;

        return new RequestHandlerSwooleWebsocketRunner(
            $container->get(ApplicationPipeline::class),
            $container->get(ServerRequestInterface::class),
            $container->get(ServerRequestErrorResponseGenerator::class),
            $container->get(SwooleWebsocketServer::class),
            $container->get(EventBus::class),
            $config['zend-expressive-swoole']['swoole-http-server'] ?? [],
            $logger
        );
    }
}
