<?php

declare(strict_types=1);

use Psl\Async;
use Psl\IO;
use Psl\Iter;
use Psl\TCP;

require __DIR__ . '/../vendor/autoload.php';

Async\main(static function(): int {
    $start = microtime(true);

    $client = new Async\Semaphore(100, static function(int $_i): string {
        $client = TCP\connect('localhost', 3030);
        $client->writeAll("GET / HTTP/1.1\r\nHost: localhost\r\nConnection: close\r\n\r\n");
        $response = $client->readAll();
        $client->close();

        return $response;
    });

    $awaitables = [];
    for ($i = 0; $i <= 1000; $i++) {
        $awaitables[] = Async\run(static fn() => $client->waitFor($i));
    }

    $responses = Async\all($awaitables);
    $last_response = Iter\last($responses);

    IO\write_error_line($last_response);

    $duration = microtime(true) - $start;

    IO\write_line('duration: %d', $duration);

    return 0;
});
