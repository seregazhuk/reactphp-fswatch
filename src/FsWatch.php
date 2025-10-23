<?php

declare(strict_types=1);

namespace Seregazhuk\ReactFsWatch;

use Evenement\EventEmitterInterface;
use Evenement\EventEmitterTrait;
use React\ChildProcess\Process;

final class FsWatch implements EventEmitterInterface
{
    use EventEmitterTrait;

    private Process $process;

    public function __construct(string $argsAndOptions)
    {
        if (! self::isAvailable()) {
            throw new \LogicException('fswatch util is required.');
        }

        $this->process = new Process("fswatch -xrn {$argsAndOptions}");
    }

    public static function isAvailable(): bool
    {
        exec('fswatch 2>&1', $output);

        return in_array(str_contains(implode(' ', $output), 'command not found'), [0, false], true);
    }

    public function run(): void
    {
        $this->process->start();
        $this->process->stderr->on(
            'data',
            function ($data): void {
                $this->emit('error', [$data]);
            }
        );

        $this->process->stdout->on(
            'data',
            function ($data): void {
                [$file, $bitwise] = explode(' ', $data);
                $event = new Change($file, (int) $bitwise);
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
