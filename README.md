## [`Hsl`](https://github.com/hhvm/hsl) vs [`Psl`](https://github.com/azjezz/psl) Async IO benchmarks

```
> hyperfine --warmup 2 --runs 20 "php src/main.php" "hhvm src/main.hack"
Benchmark #1: php src/main.php
  Time (mean ± σ):      7.388 s ±  2.101 s    [User: 138.2 ms, System: 44.8 ms]
  Range (min … max):    5.781 s … 11.831 s    20 runs

Benchmark #2: hhvm src/main.hack
  Time (mean ± σ):      8.621 s ±  2.511 s    [User: 1.362 s, System: 0.100 s]
  Range (min … max):    6.786 s … 12.705 s    20 runs

Summary
  'php src/main.php' ran
    1.17 ± 0.47 times faster than 'hhvm src/main.hack'
```
