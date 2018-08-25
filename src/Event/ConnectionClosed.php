<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole\Event;

use Swoole\Websocket\Server;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class ConnectionClosed
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var int
     */
    private $clientId;

    /**
     * @param Server $server
     * @param int $clientId
     */
    public function __construct(Server $server, int $clientId)
    {
        $this->server = $server;
        $this->clientId = $clientId;
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function clientId(): int
    {
        return $this->clientId;
    }
}
