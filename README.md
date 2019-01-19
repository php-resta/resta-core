[![Build Status](https://travis-ci.com/restapix/resta.svg?branch=master)](https://travis-ci.com/restapix/resta)
[![Total Downloads](https://poser.pugx.org/restapix/resta/downloads)](https://packagist.org/packages/restapix/resta)
[![License](https://poser.pugx.org/restapix/resta/license)](https://packagist.org/packages/restapix/resta)
[![Latest Unstable Version](https://poser.pugx.org/restapix/resta/v/unstable)](//packagist.org/packages/restapix/resta)

# Resta - A Great Php Api Designer

> **Note:** The repository contains core of the resta.
The resta core is a fully open source and will be continuously developed.
So that we will work with the help of your pull requests to make the core more stable.

## Code of Conduct
Please review [Code of Conduct](CODE_OF_CONDUCT.md).
The resta core we will work together as much as we can to bring it to a better structure.
the resta is very nice and very comfortable to write a better api code. We are pleased to present this to the community.

## How to work the resta core?
> **Note:** The resta core works in complete harmony by means of the skeleton repository.
If you want,you can create your api structure with keep track of the core application box.


Resta core consists of a stable box. This box is defined as [Application Box](src/resta/Foundation/Application.php).
This class works with the same determination all over the place.The application box is in the role of igniter for running a set of kernel classes.
The skeleton repository integrates this magical box perfectly into itself and forms the resta structure.
> <ins> For example, you can write as follows. </ins>

```php

require_once '../vendor/autoload.php';
use Resta\Foundation\Application

$app = Application(true);

```

As you can see above, the application object will return itself in a super-fast way without error.
One issue you should be aware of is that the resta kernel is directly dependent on the composer.json file.
<ins> Therefore, you must install its dependencies via composer before using the application object. </ins>

> Now let's make a small example to show what can be done with this magic box.
In this example,lets install an array as a configuration,then let's read these values.

```php

require_once '../vendor/autoload.php';
use Resta\Foundation\Application

$app = Application(true);

//set array values via loadConfig method
$app->loadConfig(function()
{
    return ['test' => ['value1' => 'foo','value2'=>['nested1' => 'nestedValue1']]];
});

//let's read these values via config helper method
config('test.value1);
config('test.value2.nested1');

```

## How to learn resta?

The resta has a perfectly written documentation.If you have a php framework habit depending on your quick learning ability.
Do not worry. You will be able to learn  very easily the resta and use it with peace of mind.

## Security Vulnerabilities
If you discover a security vulnerability within Resta, 
please send an e-mail to Ali Gürbüz via [galiant781@gmail.com](mailto:galiant781@gmail.com). All security vulnerabilities will be promptly addressed.

## License
The resta api designer is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).