<?php

declare(strict_types=1);

namespace FrankVerhoeven\Expressive\Swoole;

use FrankVerhoeven\Expressive\Swoole\Event\ConnectionClosed;
use FrankVerhoeven\Expressive\Swoole\Event\ConnectionOpened;
use FrankVerhoeven\Expressive\Swoole\Event\MessageReceived;
use Prooph\ServiceBus\EventBus;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Swoole\Http\Request as SwooleHttpRequest;
use Swoole\Websocket\Frame;
use Swoole\Websocket\Server as SwooleWebsocketServer;
use Zend\Expressive\Swoole\RequestHandlerSwooleRunner;
use Zend\Expressive\Swoole\StdoutLogger;

final class RequestHandlerSwooleWebsocketRunner extends RequestHandlerSwooleRunner
{
    /**
     * @var SwooleWebsocketServer
     */
    private $swooleWebsocketServer;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var null|LoggerInterface
     */
    private $logger;

    /**
     * @param RequestHandlerInterface $handler
     * @param callable $serverRequestFactory
     * @param callable $serverRequestErrorResponseGenerator
     * @param SwooleWebsocketServer $swooleWebsocketServer
     * @param EventBus $eventBus
     * @param array $config
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        RequestHandlerInterface $handler,
        callable $serverRequestFactory,
        callable $serverRequestErrorResponseGenerator,
        SwooleWebsocketServer $swooleWebsocketServer,
        EventBus $eventBus,
        array $config,
        LoggerInterface $logger = null
    ) {
        parent::__construct(
            $handler,
            $serverRequestFactory,
            $serverRequestErrorResponseGenerator,
            $swooleWebsocketServer,
            $config,
            $logger
        );

        $this->swooleWebsocketServer = $swooleWebsocketServer;
        $this->eventBus = $eventBus;
        $this->logger = $logger ?: new StdoutLogger();
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        $this->swooleWebsocketServer->on('start', [$this, 'onStart']);
        $this->swooleWebsocketServer->on('request', [$this, 'onRequest']);
        $this->swooleWebsocketServer->on('open', [$this, 'onOpen']);
        $this->swooleWebsocketServer->on('close', [$this, 'onClose']);
        $this->swooleWebsocketServer->on('message', [$this, 'onMessage']);
        $this->swooleWebsocketServer->start();
    }

    /**
     * @param SwooleWebsocketServer $server
     * @param SwooleHttpRequest $request
     */
    public function onOpen(SwooleWebsocketServer $server, SwooleHttpRequest $request): void
    {
        $this->logger->info('{ts} - {remote_addr} - {request_method} {request_uri} {fd}', [
            'ts'             => date('Y-m-d H:i:sO', time()),
            'remote_addr'    => $request->server['remote_addr'],
            'request_method' => $request->server['request_method'],
            'request_uri'    => $request->server['request_uri'],
            'fd'             => $request->fd
        ]);

        $this->eventBus->dispatch(new ConnectionOpened($server, $request));
    }

    /**
     * @param SwooleWebsocketServer $server
     * @param int $clientId
     */
    public function onClose(SwooleWebsocketServer $server, int $clientId): void
    {
        $this->eventBus->dispatch(new ConnectionClosed($server, $clientId));
    }

    /**
     * @param SwooleWebsocketServer $server
     * @param Frame $frame
     */
    public function onMessage(SwooleWebsocketServer $server, Frame $frame): void
    {
        $this->eventBus->dispatch(new MessageReceived($server, $frame));
    }
}
