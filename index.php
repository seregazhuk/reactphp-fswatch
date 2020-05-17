<?php
declare(strict_types=1);

use seregazhuk\FsWatch\WatchEvent;

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();
$watcher = new \seregazhuk\FsWatch\FsWatch($loop, __DIR__, '-e ".php" -I');
$watcher->watch();
$watcher->on('change', static function (WatchEvent $event) {
    var_dump($event->isFile());
    var_dump($event->updated());
});

$loop->run();
