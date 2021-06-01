<?php

declare(strict_types=1);

namespace Imi\Swoole\Server\Listener;

use Imi\Bean\Annotation\Listener;
use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Swoole\Server\Server;
use Imi\Worker;

/**
 * 发送给指定连接-请求
 *
 * @Listener(eventName="IMI.PIPE_MESSAGE.sendToGroupsRequest")
 */
class OnSendToGroupsRequest implements IEventListener
{
    /**
     * 事件处理方法.
     */
    public function handle(EventParam $e): void
    {
        $eData = $e->getData();
        $workerId = $eData['workerId'];
        $data = $eData['data'];
        $result = Server::sendRawToGroup($data['groups'], $data['data'], $data['serverName'], false);
        if (($data['needResponse'] ?? true) && !Worker::isWorkerIdProcess($workerId))
        {
            Server::sendMessage('sendToGroupsResponse', [
                'messageId' => $data['messageId'],
                'result'    => $result,
            ], $eData['workerId']);
        }
    }
}
