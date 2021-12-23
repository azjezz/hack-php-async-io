<?php

declare(strict_types=1);

use Psl\Async;
use Psl\IO;
use Psl\Iter;
use Psl\TCP;

require __DIR__ . '/../vendor/autoload.php';

Async\main(static function(): int {
    $start = microtime(true);

    $client = new Async\Semaphore(3, static function(string $url): string {
        $client = TCP\connect($url, 80);
        $client->writeAll("GET / HTTP/1.1\r\nHost: $url\r\nConnection: close\r\n\r\n");
        $response = $client->readAll();
        $client->close();

        return $response;
    });

    $awaitables = [];
    for ($i = 0; $i <= 50; $i++) {
        $awaitables[] = Async\run(fn() => $client->waitFor('example.com'));
    }

    $responses = Async\all($awaitables);
    $last_response = Iter\last($responses);

    IO\write_error_line($last_response);

    $duration = microtime(true) - $start;

    IO\write_line('duration: %d', $duration);

    return 0;
});
