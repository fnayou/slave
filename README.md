Slave
=====

Slave is a minimalistic application base on `symfony/console` and exclusively for [MasterCli][link-master-cli] micro framework. it use :

  - Symfony components (config, event dispatcher, finder, etc.)
  - Pimple
  - Monolog

Slave have some pre registered providers :

  - Configuration : 
    - initiate Pimple Container
    - look for `app/config.yml` and load default/custom configuration parameters
  - EventDispatcher : initiate an instance of the event dispatcher
  - Logger : initiate (or not) Monlog according to the given parameters within `app/config`

> Better use Slave with [MasterCli][link-master-cli] micro framework

## Technologies

Slave uses a number of open source projects to work properly:

* [Symfony Components][link-symfony-component] - bunch of Symfony components.
* [Pimple][link-pimple] - PHP Dependency Injection Container.
* [Monolog][link-monolog] - Logging for PHP.

## Installation

You need composer:

```sh
$ php composer require fnayou/slave
```

## Credits

- [Aymen FNAYOU][link-author] - [GitLab][link-author-gitlab] - [GitHub][link-author-github]

## License

![license](https://img.shields.io/badge/license-MIT-lightgrey.svg) Please see [License File](LICENSE.md) for more information.


[link-author]: https://aymen-fnayou.com
[link-author-gitlab]: https://gitlab.com/fnayou
[link-author-github]: https://github.com/fnayou
[link-master-cli]: https://github.com/fnayou/master-cli
[link-symfony-component]: http://symfony.com/fr/components
[link-pimple]: http://pimple.sensiolabs.org/
[link-monolog]: https://github.com/Seldaek/monolog
