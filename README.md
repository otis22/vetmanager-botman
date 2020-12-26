<p align="center"><img height="188" width="198" src="https://botman.io/img/botman.png"></p>
<h1 align="center">Vetmanager Botman</h1>

## About BotMan Studio

While BotMan itself is framework agnostic, BotMan is also available as a bundle with the great [Laravel](https://laravel.com) PHP framework. This bundled version is called BotMan Studio and makes your chatbot development experience even better. By providing testing tools, an out of the box web driver implementation and additional tools like an enhanced CLI with driver installation, class generation and configuration support, it speeds up the development significantly.

## Documentation

You can find the BotMan and BotMan Studio documentation at [http://botman.io](http://botman.io).

## Functions 

Now only Auth converstation works

## For contributors 

Run docker 

```shell
docker-composer build
docker-compose up
```

Other commands
```shell
#connecte to php
docker exec -it vetmanager-botman-php-fpm /bin/bash
#connect to redis from php
redis-cli -h redis -a 123456 -p 6379
```

