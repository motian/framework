<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Framework\Bootstrap;

use Hyperf\Framework\Event\OnTask;
use Psr\EventDispatcher\EventDispatcherInterface;
use Swoole\Server;
use Swoole\Server\Task;

class TaskCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->dispatcher = $eventDispatcher;
    }

    public function onTask(Server $serv, Task $task)
    {
        $event = $this->dispatcher->dispatch(new OnTask($serv, $task));
        if ($event instanceof OnTask && ! is_null($event->result)) {
            $task->finish($event->result);
        }
    }
}
