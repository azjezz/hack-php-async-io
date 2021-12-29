server:
	php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes -dmemory_limit=-1 src/server.php

php:
	php src/main.php

php-cold-jit:
	php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php

hhvm:
	hhvm -vEval.Jit=false -d hhvm.jit=0 src/main.hack

hhvm-cold-jit:
	hhvm -d hhvm.jit=1 src/main.hack

benchmark:
	hyperfine --runs 5 "php src/main.php" "php -dopcache.jit=1235 -dopcache.enable_cli=yes -dopcache.enable=yes src/main.php" "hhvm -vEval.Jit=false -d hhvm.jit=0 src/main.hack" "hhvm -d hhvm.jit=1 src/main.hack"
