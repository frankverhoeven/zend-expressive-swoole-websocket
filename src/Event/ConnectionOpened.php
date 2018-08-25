<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole\Event;

use Swoole\Http\Request;
use Swoole\Websocket\Server;

final class ConnectionOpened
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Server $server
     * @param Request $request
     */
    public function __construct(Server $server, Request $request)
    {
        $this->server = $server;
        $this->request = $request;
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->server;
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return $this->request;
    }
}
