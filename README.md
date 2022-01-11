## [`Hsl`](https://github.com/hhvm/hsl) vs [`Psl`](https://github.com/azjezz/psl) Async IO benchmarks

The benchmarks are ran against a local http server ( src/server.php ), to run the server, use:

```shell
php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/server.php
```

benchmark results:

```
> php -v
PHP 8.1.1 (cli) (built: Jan 11 2022 14:39:53) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.1, Copyright (c) Zend Technologies

> hhvm --version
HipHop VM 4.140.1 (rel) (non-lowptr)
Compiler: 1639780718_704029463
Repo schema: 05349a1136367893ac89597528b7a3308aecbe6d

> hyperfine --version
hyperfine 1.10.0

> hyperfine --runs 100 "php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php" "php src/main.php" "hhvm -d hhvm.jit=1 src/main.hack"
Benchmark #1: php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php
  Time (mean ± σ):      1.366 s ±  0.090 s    [User: 264.5 ms, System: 191.8 ms]
  Range (min … max):    1.150 s …  1.619 s    100 runs

Benchmark #2: php src/main.php
  Time (mean ± σ):      1.430 s ±  0.277 s    [User: 280.7 ms, System: 197.5 ms]
  Range (min … max):    1.147 s …  3.279 s    100 runs

Benchmark #3: hhvm -d hhvm.jit=1 src/main.hack
  Time (mean ± σ):      3.468 s ±  0.820 s    [User: 2.545 s, System: 0.542 s]
  Range (min … max):    2.257 s …  6.800 s    100 runs

Summary
  'php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php' ran
    1.05 ± 0.21 times faster than 'php src/main.php'
    2.54 ± 0.62 times faster than 'hhvm -d hhvm.jit=1 src/main.hack'
```
