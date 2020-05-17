<?php

declare(strict_types=1);

namespace seregazhuk\FsWatch;

use Evenement\EventEmitterInterface;
use Evenement\EventEmitterTrait;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;

final class FsWatch implements EventEmitterInterface
{
    use EventEmitterTrait;

    private LoopInterface $loop;

    private Process $process;

    private array $watch;

    public function __construct(LoopInterface $loop, string $path, string $options = null)
    {
        $cmd = "fswatch -xrn {$path} {$options}";
        $this->loop = $loop;
        $this->process = new Process($cmd);
    }

    public function watch(): void
    {
        $this->process->start($this->loop);
        $this->process->stderr->on(
            'data',
            function ($data) {
                $this->emit('error', [$data]);
            }
        );

        $this->process->stdout->on(
            'data',
            function ($data) {
                [$file, $bitwise] = explode(' ', $data);
                $event = new WatchEvent($file, (int)$bitwise);
                $this->emit('change', [$event]);
            }
        );
    }

    public function stop(): void
    {
        $this->process->close();
    }
}
