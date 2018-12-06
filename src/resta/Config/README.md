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