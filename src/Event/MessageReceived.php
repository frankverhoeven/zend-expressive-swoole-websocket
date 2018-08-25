<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole\Event;

use Swoole\Websocket\Frame;
use Swoole\Websocket\Server;

/**
 * @author Frank Verhoeven <hi@frankverhoeven.me>
 */
final class MessageReceived
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var Frame
     */
    private $frame;

    /**
     * @param Server $server
     * @param Frame $frame
     */
    public function __construct(Server $server, Frame $frame)
    {
        $this->server = $server;
        $this->frame = $frame;
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->server;
    }

    /**
     * @return Frame
     */
    public function frame(): Frame
    {
        return $this->frame;
    }
}
