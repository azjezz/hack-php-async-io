use HH\Lib\Async;
use HH\Lib\TCP;
use HH\Lib\IO;
use HH\Lib\C;
use HH\Lib\Str;
use HH\Asio;

<<__EntryPoint>>
async function main(): Awaitable<noreturn> {
    $start = microtime(true);

    $client = new Async\Semaphore(3, async (string $url): Awaitable<string> ==> {
        $client = await TCP\connect_async($url, 80);
        await $client->writeAllAsync("GET / HTTP/1.1\r\nHost: $url\r\nConnection: close\r\n\r\n");
        $response = await $client->readAllAsync();
        $client->close();

        return $response;
    });

    $awaitables = vec[];
    for ($i = 0; $i <= 50; $i++) {
        $awaitables[] = $client->waitForAsync('example.com');
    }

    $responses = await Asio\v($awaitables);
    $last_response = C\last($responses);

    await IO\request_error()->writeAllAsync($last_response."\n");

    $duration = microtime(true) - $start;

    await IO\request_output()->writeAllAsync(Str\format(
        'duration: %d%s', $duration, "\n"
    ));

    exit(0);
}
