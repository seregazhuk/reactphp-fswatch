<?php

declare(strict_types=1);

namespace Seregazhuk\ReactFsWatch\Tests;

use PHPUnit\Framework\TestCase;
use React\EventLoop\Loop;
use Seregazhuk\ReactFsWatch\Change;
use Seregazhuk\ReactFsWatch\FsWatch;

use function React\Async\delay;

final class FsWatchTest extends TestCase
{
    /** @test */
    public function it_emits_change_event_on_fs_changes(): void
    {
        $loop = Loop::get();
        $fsWatch = new FsWatch(__DIR__);
        $fsWatch->run();
        $fsWatch->onChange(
            function ($data): void {
                $this->assertInstanceOf(Change::class, $data);
            }
        );
        $tempFile = null;
        $loop->addTimer(1, function () use (&$tempFile): void {
            $tempFile = tempnam(__DIR__, '');
        });
        delay(2);
        unlink($tempFile);
        $loop->stop();
    }
}
