HatimeriaRemotelogBundle
=========================

Monolog compatible logging handler posting array of parameters to configured host as application/json data

## Installation

### Step1: Add HatimeriaRemotelogBundle to you vendors as well as remotelog

**Using vendors script**

Add these code to your Symfony 2 `deps` file:

```
[HatimeriaRemotelogBundle]
  git=git://github.com/hatimeria/HatimeriaRemotelogBundle.git
  target=/bundles/Hatimeria/RemotelogBundle

[HatimeriaRemotelog]
  git=git://github.com/hatimeria/remotelog.git
  target=/remotelog
```

and run symfony's vendor script to download it automatically:

``` bash
$ php bin/vendors install
```

**Using submodules**

Use following commands:

``` bash
$ git submodule add git://github.com/hatimeria/HatimeriaRemotelogBundle.git vendor/bundles/Hatimeria/RemotelogBundle
$ git submodule add git://github.com/hatimeria/remotelog.git vendor/remotelog
$ git submodule update --init
```

### Step2: Add Hatimeria and Remotelog namespace to your autoloader configuration

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Hatimeria' => __DIR__.'/../vendor/bundles',
    'Remotelog' => __DIR__.'/../vendor/remotelog/src',
));
```

### Step3: Enable bundle in your AppKernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Hatimeria\RemotelogBundle\HatimeriaRemotelogBundle(),
    );
}
```

### Step4: Bundle configuration

Example of bundle configuration:

``` yaml
# app/config/config.yml
hatimeria_remotelog:
    host: 'http://remotelogserver.localhost/app_dev.php' # (required) remotelog application server hos
    place: 'Remotelog test enviroment'                   # (required) Some information about project to identify log
    route: '/api/monitoring'                             # If you need you can provide more spcific path (concept)
    level: ERROR                                         # You can define logging level same way as you configure your monolog handler
```

### Step5: Configure your monolog

Remotelog is compatible with monolog handlers. You can configure it to log information using remotelog. Here is example setup:

``` yaml
# app/config/config_prod.yml
monolog:
    handlers:
        main:
            type:         fingers_crossed
            action_level: error
            handler:      group
        group:
            type: group
            members:
                - stream
                - remote_buffer
        remote_buffer:
            type: buffer
            handler: remotelog
        remotelog:
            type: service
            id: remotelog
        stream:
            type:  stream
            path:  %kernel.logs_dir%/%kernel.environment%.log
            level: debug
```

`main` handler will buffer until an `error` level error occur. Then it will send them to `group` handler. It will send errors to `strem` and `remote_buffer`
simultaneously. `stream` handler will work as expected streaming all errors, in these case to log file. `remote_buffer` will gather all errors.
When `remote_buffer` is destroyed it will send all batched errors to `remotelog` service which will POST each error of certain level to configured host.

## Usage

You can use remotelog as service.

``` php
<?php
    $remotelog = $this->getContainer()->get('remotelog');

    // log an exception object
    $remotelog->addLog(new \Exception('Very important exception!'));

    // Or you can log an array of parameters
    $remotelog->addLog(array(
        'type'    => CRITICAL,
        'message' => 'Very important exception!'
    ));
    // place parameter is automatically added from bundle configuration

```
