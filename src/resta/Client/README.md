# Client Resolver

Client resolver is defined as an object for client requests and it is a very useful package that can be designed and used as annotation rules.
The benefit of this package to your application is that all client requests can be read and managed as objects.Resta application is included in this package and has a cli generator for you to use it very easily.

# Create a client.
Creating a client is very simple. First of all, you should be able to comprehend how the cli generator creates a structure.
Cli clients first create a name directory into the client directory. We can describe this as follows. Suppose you have a user table as database.
You will receive requests to this user table as create, update, and delete.The concept defined here as 'name' is the user table.Concepts such as create, update, or delete are defined as clients.

- ##### Now run the following command on the terminal in the directory where your application is located.


```code

$ php api client create [projectName] name:user client:create

```

This code will place a directory named user in your application's client directory and 3 files (Client, ClientGenerator, ClientProvider) in the same directory.
The first part we will deal with is the user directory that we created as name.In this directory there is a 'create' directory which we created as a client and a file named 'clientManager'.
In the Create directory, there is a 'create' file that meets the actual requests and a file named 'createGenerator'.

# How to Receive Requests
The Create.php file in the create directory created as a client is the file that receives requests directly.

```php

class Create extends ClientProvider implements ClientContract
{
    //request and request generator
    use Client,CreateGenerator,ClientGenerator;

    /**
     * @var string
     */
     public $clientName = 'Create_client';

    /**
     * @var array
    */
    protected $capsule = [];

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = [];

    /**
     * remove the specified key from client real request
     *
     * @var array
     */
    protected $requestExcept = [];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http = [];


}

```

You can make the above 'user: create' client request in any controller file with the following code.

```code

$this->client->user->create->all()

```

The 'all' method in the controller will reflect the entire request directly to your output.
As an annotation, the client object will be annotated and written by resta into your automatic 'annotationManager' file.

* The main topic is your definitions in the Create.php file.

# Define Request Sanctions.
Identifying request objects is very important.
By default, the above Create.php file processes all requests directly and does not impose any sanctions.

* ###### All request sanctions must be defined as 'protected property' and 'protected function'.

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @return string
     */
    protected function username()
    {
        return $this->username;
    }

```

With the above code, a sanction was applied to username data. But this sanction did not request a change to username data.
The direct username value is assigned as the client value.A sanction requires two main rules. The first is a 'protected property' and the second is a 'property function'.
These two rules are mandatory. These two definitions are called request value sanctions.

* ###### Change the client request sanction.

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @return string
     */
    protected function username()
    {
        return md5($this->username);
    }

```

It encrypts the username data sent by the client with md5. You will see this in the response data. Here you can use a method as you want.

# Define rules for request sanctions.
Defining rules for request sanctions is actually 'validating' incoming data.
Writing request rules is very simple.
Simply use the annotation for the 'property function'.

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @regex([a-zA-z]+)
     * @return string
     */
    protected function username()
    {
        return md5($this->username);
    }

```

The rule defined above enforces the letter constraint for username data.
If the value specified as regex is not sent, the client resolver will throw an exception with 400 error code

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @rule(integer)
     * @return string
     */
    protected function username()
    {
        return md5($this->username);
    }

```

If you want, you can use defined rules. A rule is used in the annotation statement above and requires an integer.
These rules are defined in the trait class defined as Client.php. You can see the rules by looking at that traite.

```php

        /**
         * request rules
         *
         * @var array
         */
        protected $rules = [
            'capital'          => '[A-Z]',
            'min6Char'         => '.{6,}',
            'max6Char'         => '^.{0,6}$',
            'integer'          => '^[0-9]+$',
            'string'           => '^[a-zA-Z]+$',
            'alphaNumeric'     => '^[a-zA-Z]+[a-zA-Z0-9._\-\s]+$',
            'days'             => '^monday$|^sunday$|^tuesday$|^wednesday$|^thursday$|^friday$|^saturday$',
            'clock'            => '^(([0-1][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$',
            'float'            => '^[0-9]+(\\.[0-9]+)?$',
            'dontStartSpace'   => '^(?=[^ ]).+(?<=\S)$',
        ];

```
In the above rules variable you can define the rules you want and you can use them very easily.

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @rule(integer:min6Char)
     * @return string
     */
    protected function username()
    {
        return md5($this->username);
    }

```

If you want, you can apply multiple rules in sequence with the (:) character.First, the username entry is integer
and then apply the rule defined as min6Char, if applicable.

# Use special exceptions for request rules.
Exception is one of the most important key elements for your system.
Client resolver allows you to define custom exceptions.You can direct your users by feeding them with the most accurate exception string.

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @regex([a-zA-z]+)
     * @exception(name:username params:key=username)
     * @return string
     */
    protected function username()
    {
        return md5($this->username);
    }

```

The exception to be applied to the regex rule defined above is defined for key defined as username in exception.yml.
If the 'username' key is defined in the exception.yml file, this message is directly reflected in the output.

```php

    username : (key) is not valid

```

You can send variables to the exception.yml file with the value specified as the parameter.
'username is not valid' is sent to the output.

```php

    @exception(name:username params:key=username,key2=value)

```

To send multiple values ​​to your exceptions for the regex method, use the comma method in the format above.

- ###### Note : In this way, the use of exception with annotation only applies to regex. Since the value used for Rule is already specified as alias, the exception.yml file will automatically search for that alias value.

```php

    /**
     * @var string
     */
    protected $username;

    /**
     * @rule(integer)
     * @return string
     */
    protected function username()
    {
        return md5($this->username);
    }

```

exception.yml 

```php

    integer : (key) is not integer

```

# Add custom definitions for the client solver.
Except for the annotation method for custom request sanctions, one of the methods can be the data you expect in the data sent by the user.
In fact, this feature is one of the most widely used methods.The user must send the requested data to the server.
This package has the default value defined for it.

```php

    /**
     * The values ​​expected by the server.
     * @var array
     */
     protected $expected = ['username'];

```

The user must send you the values ​​written in the array defined for the expected property. This is one of the important features for your requests.
and this package offers you a very convenient way.

##### Expected Rule:

```php

    /**
     * The values ​​expected by the server.
     * @var array
     */
     protected $expected = ['username|email'];

```

In the separations written with a single key (|) character for the array as expected, one of the two keys must be sent.

exception.yml 
```php

   clientExpected : (username) is not valid 

```

or

exception.yml 
```php

   clientExpected : (username) or (email) is not valid 

```

When you want to write a special exception for the request expected method,
In the exception.yml file, you can type the above key value relationship that corresponds to the clientExpected key.

##### Capsule Rule:

Capsule method means limiting client shipments.This means that; The user cannot send you any data other than the keys specified in the capsule array.
Capsule method; 'protected property' and 'public function can be written as capsuleMethod ()'.


```php

    /**
     * @var array
     */
    protected $capsule = ['user_id'];

```

it is available data in capsule array the above.Client cannot send any input to the server except user_id.
If the client sends an entry other than user_id, the client resolver will throw a exception.As can be seen, it is quite simple to specify the conditions for the client with the capsule method.

```php

    /**
     * when we use the method for capsule,
     * the capsuleMethod method is executed and the results must be array.
     *
     * @return array
     */
    public function capsuleMethod() : array
    {
        return [];
    }

```
You have to use a method for capsule data that you don't want to write again and again.
You may want the Capsule data to be the same as your model's fillable array.It's annoying to write them over and over again.
Therefore, the client resolver will merge your capsule data, provided that a method called 'capsuleMethod' is output to the array.

```php

    /**
     * @var array
     */
    protected $auto_capsule = ['page'];

```

One of the most important points to consider when using Capsule is the repeated and general client inputs.
For example, your api can continually pagination for data.So you have to add the page key to the capsule data for each client.
That's not a good thing either.To do this, client resolver offers a 'protected property' property called auto_capsule in Client.php.
The keys specified in auto_capsule are automatically added to all your client capsule modeling.

exception.yml 

```php

   clientCapsule : (data) data cannot be sent.

```
You can give your custom exception messages with 'clientCapsule' keyi in the exception.yml file for Capsule.
Each capsule key will be added to this file as a variable.


# Auto Generators:
Auto generators are one of the best ways to use this package.
Auto generators are entries that are automatically added to the client data without having to be in the client input.
There are two auto generator systems.
The auto_generator system designed for that client, the first of which is in the client directory,
another is the auto genator system which is located in the ClientGenerator.php file which is designed for all clients.

```php

    /**
     * auto generator for inputs
     * @var array
     */
    protected $generators = [];
    
    /**
     * generators dont overwrite
     *
     * @var array
     */
    protected $generators_dont_overwrite = [];

```

The first one is the auto_generator in the createGenerator.php file we created.
There are two protected properties within the system.Keys written as an array with the first feature will be automatically added to the client input.

```php

    /**
     * auto generator for inputs
     * @var array
     */
    protected $generators = ['code'];
    
    /**
     * generators dont overwrite
     *
     * @var array
     */
    protected $generators_dont_overwrite = [];
    
    /**
     * @return string
     */
    public function codeGenerator()
    {
        return 'code';
    }

```
