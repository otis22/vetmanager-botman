# Vetmanager Botman

![GitHub CI](https://github.com/otis22/vetmanager-botman/workflows/CI/badge.svg)

[heroku app](https://vetmanager-botman.herokuapp.com/)
[telegram](https://t.me/vetmanager_bot)

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

## Telegram 

[how to create bot](https://unnikked.ga/getting-started-with-telegram-bots-9e467d922d69)
[how to debug with ngrok](https://unnikked.ga/make-your-telegram-bot-with-laravel-and-botman-b8199e58461d)

### For contributors 

`
1. Go to @BotFather in Telegram
1. Press /newbot
1. Enter bot data, for devs vetmanager-botman-$username
1. Run server `make serve`
1. Start tunnelling with [ngrok](https://otis22.github.io/ngrok,/utils/2021/02/03/ngrok-is-pretty-cool.html) `ngrok http 8080`
1. https://api.telegram.org/bot<token>/setWebhook?url=https://5e86b344.ngrok.io/botman

Where token is authtoken from @GodFather
`
