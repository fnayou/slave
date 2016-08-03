# Slave

Slave is a minimalistic application base on `symfony/console` and exclusively for [MasterCli] micro framework. it use :

  - Symfony components (config, event dispatcher, finder, etc.)
  - Pimple
  - Monolog

Slave have some pre registered providers :

  - Configuration : 
    - initiate Pimple Container
    - look for `app/config.yml` and load default/custom configuration parameters
  - EventDispatcher : initiate an instance of the event dispatcher
  - Logger : initiate (or not) Monlog according to the given parameters within `app/config`

> Better use Slave with [MasterCli] micro framework

### Version
  - 08/2016 : v1.0.0

### Tech

Slave uses a number of open source projects to work properly:

* [Symfony Components] - bunch of Symfony components.
* [Pimple] - PHP Dependency Injection Container.
* [Monolog] - Logging for PHP.

### Installation

You need composer:

```sh
$ php composer require fnayou/slave
```

### Todos

 - lot of things

License
----

MIT


© 2015 [Aymen Fnayou Inc.]


   [Aymen Fnayou Inc.]: <https://aymen-fnayou.com>
   [MasterCli]: <https://gitlab.com/u/fnayou>
   [Symfony Components]: <http://symfony.com/fr/components>
   [Pimple]: <http://pimple.sensiolabs.org/>
   [Monolog]: <https://github.com/Seldaek/monolog>
