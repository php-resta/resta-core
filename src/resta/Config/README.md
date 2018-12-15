# Configuration definitor for application
it is designed to get as easy configurations of all user applications in system box.
It is a loader including a helper method.

* ConfidLoader object is a kernel loader.
* ConfigProcess is a config definitor.

### Configuration process inside the application

- To get all file namespaces and path that in the configuration directory
```sh
use Resta\Config\ConfigProcess;
(new ConfigProcess())->get();
```
- To get all keys through the specified file (example app.php) that in the configuration directory
```sh
use Resta\Config\ConfigProcess;
(new ConfigProcess('app'))->get();
```

- To get a single key (example response) through the specified file (example app.php) that in the configuration directory
```sh
use Resta\Config\ConfigProcess;
(new ConfigProcess('app.response'))->get();
```
- .(dot) is a seperator then you can get key in key recursively.(example app.foo.bar etc..)

### Helper method

```sh
config('app.response');
```
- You can get all configuration keys in the application box

### Config facade object

```sh
use Resta\Config\Config;
Config::make('app.response')->get();
```

### Custom Config Loader


You can include your configuration files that in the directory 
that you specify as the user to the configuration installer.
For example,
Your configuration values ​​contained within the controller endpoint directory as defined in appProvider class in the application kernel
is automatically included in the config installer.

```sh
// while developing in the controller,
// in addition to the config variables
// it is added your config files in the controller directory
$this->app->loadConfig(function()
{
    if(defined('endpoint')){
        return path()->controller(endpoint,true).'/Config';
    }
    return null;
});
```