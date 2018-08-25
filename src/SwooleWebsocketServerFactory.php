<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole;

use Psr\Container\ContainerInterface;
use Swoole\Websocket\Server as SwooleWebsocketServer;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class SwooleWebsocketServerFactory
{
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 8080;

    /**
     * @param ContainerInterface $container
     * @return SwooleWebsocketServer
     */
    public function __invoke(ContainerInterface $container): SwooleWebsocketServer
    {
        $config = $container->get('config');
        $swooleConfig = $config['zend-expressive-swoole']['swoole-websocket-server'] ?? null;
        $host = $swooleConfig['host'] ?? static::DEFAULT_HOST;
        $port = $swooleConfig['port'] ?? static::DEFAULT_PORT;
        $mode = $swooleConfig['mode'] ?? null;
        $protocol = $swooleConfig['sock_type'] ?? null;

        $server = new SwooleWebsocketServer($host, $port);
        if (isset($swooleConfig['options'])) {
            $server->set($swooleConfig['options']);
        }
        return $server;
    }
}
