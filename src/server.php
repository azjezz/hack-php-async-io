<?php

declare(strict_types=1);

namespace Psl\Example\TCP;

use Exception;
use Psl\Async;
use Psl\IO;
use Psl\Network\Exception\AlreadyStoppedException;
use Psl\Str;
use Psl\TCP;
use function gc_collect_cycles;

require __DIR__ . '/../vendor/autoload.php';

Async\main(static function (): int {
    $server = TCP\Server::create('localhost', 3030);

    IO\write_error_line('Server is listening on http://localhost:3030');

    Async\Scheduler::unreference(Async\Scheduler::repeat(2, function() { gc_collect_cycles(); }));
    $watcher = Async\Scheduler::onSignal(SIGINT, $server->close(...));
    Async\Scheduler::unreference($watcher);

    $semaphore = new Async\Semaphore(200, static function ($connection) {
        try {
            $request = $connection->read();

            $header_line = Str\split($request, "\n");

            IO\write_error_line('-> %s', $header_line[0] ?? '*malformed request*');

            $connection->writeAll("HTTP/1.1 200 OK\n");
            $connection->writeAll("Server: PHP-Standard-Library TCP Server - https://github.com/azjezz/psl\n");
            $connection->writeAll("Connection: close\n");
            $connection->writeAll("Content-Type: text/plain; charset=utf-8\n\n");
            $connection->writeAll("Hello, World!");
            $connection->close();

            unset($request, $header_line, $connection); // free memory.
        } catch (Exception $e) {
            IO\write_error_line('[%s]: %s ( %s:%d )', $e::class, $e->getMessage(), $e->getFile(), $e->getLine());
        }
    });

    try {
        while (true) {
            $connection = $server->nextConnection();

            Async\Scheduler::defer(static fn() => $semaphore->waitFor($connection));
        }
    } catch (AlreadyStoppedException) {
        IO\write_error_line('');
        IO\write_error_line('Goodbye 👋');
    }

    return 0;
});
