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


