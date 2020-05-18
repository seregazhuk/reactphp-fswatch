<?php

declare(strict_types=1);

namespace seregazhuk\ReactFsWatch;

use Evenement\EventEmitterInterface;
use Evenement\EventEmitterTrait;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;

final class FsWatch implements EventEmitterInterface
{
    use EventEmitterTrait;

    private LoopInterface $loop;

    private Process $process;

    public function __construct(string $cmd, LoopInterface $loop)
    {
        if (!self::isAvailable()) {
            throw new \LogicException("fswatch util is required.");
        }

        $this->process = new Process("fswatch -xrn {$cmd}");
        $this->loop = $loop;
    }

    public static function isAvailable(): bool
    {
        exec('fswatch 2>&1', $output);

        return strpos(implode(' ', $output), 'command not found') === false;
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
                $event = new Change($file, (int)$bitwise);
                $this->emit('change', [$event]);
            }
        );
    }

    public function stop(): void
    {
        $this->process->close();
    }

    public function onChange(callable $callable): void
    {
        $this->on('change', $callable);
    }
}
