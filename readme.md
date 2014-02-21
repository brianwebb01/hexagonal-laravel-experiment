## Hexagonal Laravel Architecture

In building large scale web applications MVC seems like a good solution in the initial design phase.  However after having built a few large apps that have multiple entry points (web, cli, api etc) you start to find that MVC breaks down.  Enter Hexagonal Architecture.

One of the big tennants of the architecture presented here is SOLID principles.  Each class tries to only do a single responsibility.  This makes for much simpler testing, later extention and modification.

I won't go into the specifics of what Hexagonal Architecture **IS** in this readme, the references below do a great job of that.  Instead this readme will just outline the example of the architecture outlined in this Laravel 4.1 app.

**References:**

* [Hexagonal Architecture by Alistair Cockburn](http://alistair.cockburn.us/Hexagonal+architecture)
* [OOP Business Applications: Entity, Boundry, Interactor](http://www.whitewashing.de/2012/08/13/oop_business_applications_entity_boundary_interactor.html)
* [GoRuCon 2012 Hexagonal Rails by Matt Wynne](http://www.youtube.com/watch?v=CGN4RFkhH2M)
* [Hexagonal Architecture for Rails Developers](http://victorsavkin.com/post/42542190528/hexagonal-architecture-for-rails-developers)
* [Scenarios in Laravel PHP (Hexagonal Pattern Design)](http://www.keltdockins.com/2/post/2013/12/scenarios-in-laravel-php-hexagonal-pattern-design.html)

## File Structure

1.  The controllers, models and views live in their default out-of-the-box locations for a Laravel application.

2. A `app/lib` directory has been added and is referenced in the `classmap` entry of the **composer.json** file so it will be loaded corrrectly with no top level namespace.

3. The lib folder contains:

File | Description
--- | ---
Contracts\Instances\InstanceInterface.php | Should be implemented by your models
Contracts\Notification\CreatorInterface.php | Should be implemented by a class (controller) that creates things
Contracts\Notification\UpdaterInterface.php | Should be implemented by a class (controller) that updates things
Contracts\Notification\DestroyerInterface.php | Should be implemented by a class (controller) that destroys things
Contracts\Repositories\RepositoryInterface.php | For each entity you have you should have a repository interface for it that extends this class.  Custom methods from the class will go in there, but common methods are provided by RepositoryInterface.
Providers\RepositoriesServiceProvider.php | Each entity that you add needs an entry added to this service provider to tell Laravel which concrete implementation is needed when you inject an interface.
Repositories\DbRepository.php | Each entity that you add needs a DbRepository class added that implements the coresponding Repository interface.<br /><br /> (i.e. DbOrderRepository implements Contracts\Repositories\OrderRepositoryInterface )<br /><br />The entity specific repository and corresponding interface serve as a place to add custom repository methods.  An example for orders might be `getReturnedOrdersThisWeek()`
Services\\`PluralizedEntityName`\\`EntityName`Creator.php | Each entity will have an associated service object that will be used for **creating** instances of that object.  The namespaceing and class name is specific to the entity name.
Services\\`PluralizedEntityName`\\`EntityName`Updater.php | Each entity will have an associated service object that will be used for **updating** instances of that object.  The namespaceing and class name is specific to the entity name.
Services\\`PluralizedEntityName`\\`EntityName`Destroyer.php | Each entity will have an associated service object that will be used for **destroying** instances of that object.  The namespaceing and class name is specific to the entity name.
Validators\\`EntityName`Validator.php | Each entity will have an associated validator object that will be used for validating instances of that object.  The class name is specific to the entity name, and should extend **Validator.php**.  Each Validator class just needs to specify validation rules at a minimum.

## Request Flow

In the documentation of this request flow we'll look at the __store order__ use case

The Router (**routes.php**) receives the request and hands it off to a Controller.  

### 1.  Controller

In our Orders example the controller can create, update, and destroy orders so the class implements the `CreatorInterface`, `UpdaterInterface`, and `DestroyerInterface`.

  > **SOLID**: To adhere to the "I" of SOLID (Interface Segmentation) the 3 interests are split out into seperate interfaces.  So a controller that only created things whouldn't implement the other 2 interfaces and thus be required to implment those methods.

### 2. Action

The appropriate controller action is invoked based on the routing (`store` in our example).  

  > Note: For actions that present a view they simply do so, using a simple `View::make()`.  For constructive or destructive methods we need to hand off to a service object for it's _Single Responsibility_

### 3. Service Object (create, update, destroy)

An instance of `OrderCreator` is resolved from the IoC Container and given the necessary arguments to create the Order.  

  > Note: See that we are returning the result from the `OrderCreator::create()` function.  In the method call stack this will actually be the return value from the `creationSucceeded` or `creationFailed` controller methods.

### 4. Validation, Create and Return

The `OrderCreator` service object takes an `OrderValidator` argument in the constructor.  Because we resolved the `OrderCreator` from the IoC Container, Laravel went ahead and created an `OrderValidator` instance for us as an argument too.

In the `create()` method the `OrderCreator` hands off responsibility to the `OrderValidator` to do the validation and creates the `Order` if validation succeeds.

Based on the success or failure of the validation and subsequent creation (or not) of the Order.  The `OrderCreator` will call interface methods on the `CreatorInterface $listener`, this is actually the `OrdersController` which passed itself in to the `OrderCreator::create()` method.

### 5. Response

In the Controller's implementation of the `creationSucceeded` and `creationFailed` methods, the controller can decide what it wants to do if the service object succeded or failed at creating the order.

## Wrap up

In this architecture and example of create order we've separated concerns as follows:

### Controllers

The controllers just **request** the operation of the appropriate party and **respond** with the result.  

### CrUD Services

The service objects handle the action of create, update, and destroy independently

> Note: Typical "CRUD" is listed as CrUD above since it is really **C**reate, **U**pdate, and **D**elete only, no **R**ead.

### Repositories

When a CrUD Service or Controller needs to fetch an object or a collection it uses a Repository which implements a corresponding Repository Interface.

### Validators

Whenever a CrUD Service needs validation it hands off responsibility to a dedicated validator.

### Notification

Controllers (or any other class could too) implement Notification interfaces so that they can be updated on the success or failoure of the requested CrUD action.

### Instances

As a best practice we try to "code to an interface", which is why our Eloquent model implmements the `InstanceInterface`. That way we can type hint that interface in other functions, and later if we decide to have models that aren't Eloquent they can implment that interface too and everything should still work.


## Feedback

Please make pull requests and create issues for discussion and improvement.
