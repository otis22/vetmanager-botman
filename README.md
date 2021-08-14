# Vetmanager Botman

![GitHub CI](https://github.com/otis22/vetmanager-botman/workflows/CI/badge.svg)

* [for bugs](https://forms.gle/TYdpek6jYDzkTPBP8)
* [bot statistics](http://vetmanager-botman.herokuapp.com/stats)
* [telegram](https://t.me/vetmanager_bot)

<p align="center"><img height="188" width="198" src="https://botman.io/img/botman.png"></p>
<h1 align="center">Vetmanager Botman</h1>

## About BotMan Studio

While BotMan itself is framework agnostic, BotMan is also available as a bundle with the great [Laravel](https://laravel.com) PHP framework. This bundled version is called BotMan Studio and makes your chatbot development experience even better. By providing testing tools, an out of the box web driver implementation and additional tools like an enhanced CLI with driver installation, class generation and configuration support, it speeds up the development significantly.

## Documentation

You can find the BotMan and BotMan Studio documentation at [http://botman.io](http://botman.io).

## Functions 

- Authorization
- Check own admissions
- Check doctors timesheets
- Notifications
- Rating and review

## For contributors 

Run docker 

```shell
docker-composer build
docker-compose up
```

Make commands
```shell
#run conversations tests
make botman-tests

#run unit tests
make unit

#build docker containers
make build

#start the server
make serve

#enter into php-fpm
make exec

#stop the server
make down
```

Other commands
```shell
#connecte to php
make exec
```

## Heroku 

1. Add clear db addon
```shell
heroku addons:add cleardb:ignite –a(my_app_name_goes_here)
```
After you can use CLEARDB_DATABASE_URL in envs, and you can connect remotely

CLEARDB_DATABASE_URL => mysql://[username]:[password]@[host]/[database name]?reconnect=true

2. Run migrations
```shell
heroku run -a(my_app_name_goes_here)  bash 
#after
php artisan migrate
#than yes
```

3. Set up scheduler

```shell
heroku addons:create scheduler:standard
#enter into sheduller
heroku addons:open scheduler
```

And set up command `php artisan send_schedule` on 17.00 MSK

## Telegram 

* [how to create bot](https://unnikked.ga/getting-started-with-telegram-bots-9e467d922d69)
* [how to debug with ngrok](https://unnikked.ga/make-your-telegram-bot-with-laravel-and-botman-b8199e58461d)

### For contributors 

`
1. Go to @BotFather in Telegram
1. Press /newbot
1. Enter bot data, for devs vetmanager-botman-$username
1. Run server `make serve`
1. Start tunnelling with [ngrok](https://otis22.github.io/ngrok,/utils/2021/02/03/ngrok-is-pretty-cool.html) `ngrok http 8080`
1. php artisan botman:telegram:register --output and pass your url https://5e86b344.ngrok.io/botman

Where token is authtoken from @GodFather


## Contributing 

1. We are not using default params in constructor. It is wrong code `__construct($id = null)` 
We are using secondary constructors instead
```php
class Student
{
    public function __construct() {
        // allocate your stuff
    }

    public static function withID( $id ) {
        $instance = new self();
        $instance->loadByID( $id );
        return $instance;
    }

    public static function withRow( array $row ) {
        $instance = new self();
        $instance->fill( $row );
        return $instance;
    }
}
```
2. We are not using `compact`. We are using plain arrays instead
