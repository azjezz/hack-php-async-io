## [`Hsl`](https://github.com/hhvm/hsl) vs [`Psl`](https://github.com/azjezz/psl) Async IO benchmarks

The benchmarks are ran against a local http server ( src/server.php ), to run the server, use:

```shell
php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/server.php
```

benchmark results:

```
> hyperfine --runs 100 "php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php" "hhvm -d hhvm.jit=1 src/main.hack"
Benchmark #1: php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php
  Time (mean ± σ):      1.579 s ±  0.381 s    [User: 748.1 ms, System: 172.6 ms]
  Range (min … max):    1.191 s …  3.499 s    100 runs

Benchmark #2: hhvm -d hhvm.jit=1 src/main.hack
  Time (mean ± σ):      3.476 s ±  0.852 s    [User: 1.694 s, System: 0.310 s]
  Range (min … max):    2.151 s …  5.047 s    100 runs

Summary
  'php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php' ran
    2.20 ± 0.76 times faster than 'hhvm -d hhvm.jit=1 src/main.hack'
```