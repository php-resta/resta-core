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