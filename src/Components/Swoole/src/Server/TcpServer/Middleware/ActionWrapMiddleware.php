<?php

declare(strict_types=1);

namespace Imi\Swoole\Server\TcpServer\Middleware;

use Imi\Bean\Annotation\Bean;
use Imi\RequestContext;
use Imi\Swoole\Server\TcpServer\IReceiveHandler;
use Imi\Swoole\Server\TcpServer\Message\IReceiveData;
use Imi\Swoole\Server\TcpServer\ReceiveHandler;

/**
 * @Bean("TCPActionWrapMiddleware")
 */
class ActionWrapMiddleware implements IMiddleware
{
    /**
     * 动作中间件.
     *
     * @var string
     */
    protected string $actionMiddleware = ActionMiddleware::class;

    /**
     * 处理方法.
     *
     * @param IReceiveData    $data
     * @param IReceiveHandler $handler
     *
     * @return void
     */
    public function process(IReceiveData $data, IReceiveHandler $handler)
    {
        // 获取路由结果
        $result = RequestContext::get('routeResult');
        if (null === $result)
        {
            return $handler->handle($data);
        }
        $middlewares = $result->routeItem->middlewares;
        $middlewares[] = $this->actionMiddleware;
        $handler = new ReceiveHandler($middlewares);

        return $handler->handle($data);
    }
}