<?php

declare(strict_types=1);

namespace Seregazhuk\ReactFsWatch\Tests;

use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use Seregazhuk\ReactFsWatch\Change;
use Seregazhuk\ReactFsWatch\FsWatch;

use function Clue\React\Block\sleep;

final class FsWatchTest extends TestCase
{
    /** @test */
    public function it_emits_change_event_on_fs_changes(): void
    {
        $loop = Factory::create();
        $fsWatch = new FsWatch(__DIR__, $loop);
        $fsWatch->run();
        $fsWatch->onChange(
            function ($data) {
                $this->assertInstanceOf(Change::class, $data);
            }
        );
        $tempFile = tempnam(__DIR__, '');

        sleep(1, $loop);
        unlink($tempFile);
    }

    /** @test */
    public function it_detects_file_change(): void
    {
        $loop = Factory::create();
        $fsWatch = new FsWatch(__DIR__, $loop);
        $fsWatch->run();
        $fsWatch->onChange(
            function (Change $event) {
                $this->assertTrue($event->isFile());
            }
        );
        $tempFile = tempnam(__DIR__, '');

        sleep(1, $loop);
        unlink($tempFile);
    }

    /** @test */
    public function it_detects_dir_change(): void
    {
        $loop = Factory::create();
        $fsWatch = new FsWatch(__DIR__, $loop);
        $fsWatch->run();
        $fsWatch->onChange(
            function (Change $event) {
                $this->assertTrue($event->isDir());
            }
        );
        $tempDirPath = __DIR__ . '/temp';
        mkdir($tempDirPath);

        sleep(1, $loop);
        unlink($tempDirPath);
    }
}
