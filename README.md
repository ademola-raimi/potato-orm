**Potato ORM**
================
----------
Potato-ORM is based on concepts borrowed from the Laravel framework. It is a package that can perform the basic CRUD (create, read, update and delete) operations.

**Installation**
-------
To install this package, PHP 5.5+ and Composer are required

  `$ composer require Demo/potato-orm`

----------
**Usage**
-----
----------
To use this package, all you need to do is to simply extend the base class. The base class is an abstract class called "DataBaseModel". Take for instance, you wish to perform the CRUD operations on the users table. Create a corresponding user class which should look like this:

    
    use Demo;
    
    class User extends DataBaseModel
    {
    }
**

 - **Saving a new record to the table**

         $user               = new User();
      
         $user->name         = "Prosper Otemuyiwa";
         $user->sex          = "m";   
         $user->occupation   = "Trainer";
         $user->organisation = "Andela";
         $user->year         = 2009;
        
         $user->save();  

 - **Read all record from the table**
 

         $users = User::getAll();
         
         print_r($users);

 - **Read from a particular record in the table**

        $user = User::findById(3);
        
        print_r($user->getById());


 - **Update a record in the table. For example, update the name of the tenth record in the users table:**
 
        $user       = User::findById(10);
         
        $user->name = "Gbolohan Kuti";
         
        $user->save();

 - **Delete a record in the table. For example, delete the eighth record in the users table:**
 

        $users = User::destroy(8);

 - **Exception Handling**
 
To make this package degrade gracefully, It has to be wrapped under try and catch in order for all exceptions to be caught. 

    

     - Catching exception on save new record:

            try {
                 $user               = new User();
          
             $user->name         = "Prosper Otemuyiwa";
             $user->sex          = "m";   
             $user->occupation   = "Trainer";
             $user->organisation = "Andela";
             $user->year         = 2009;
            
                 $user->save(); 
             } catch(Exception $e) {
                 print($e->getMessage());
             } 
   

     - Catching exception on reading from the table:

    
            /**
             * Read all record
             * / 
            try {
                $users = User::getAll();
                print_r($users);
            } catch(Exception $e) {
                print($e->getMessage());
            } 
            
            /**
             * Read from a particular record
             * / 
            try {
                $user = User::findById(3);
                print_r($user->getById());
            } catch(Exception $e) {
                print($e->getMessage());
            } 

 

     - Catching exception on updating the table:

            try {
                $user       = User::findById(10);
                $user->name = "Gbolohan Kuti";
                $user->save();
             } catch(Exception $e) {
                print($e->getMessage());
             } 

     - Catching exception on deleting from the table:
    
             try {
                 $users = User::destroy(8);
             } catch(Exception $e) {
                 print($e->getMessage());
             } 

**Testing**
-------
----------


Run the following command in the potato-orm directory:

    ~ phpunit tests


**Change log**
----------


----------


Please check out [CHANGELOG](https://github.com/andela-araimi/Checkpoint-one/blob/master/CHANGELOG.md/%22CHANGELOG%22) file for information on what has changed recently.

**Contributing**
------------


----------


Please check out [CONTRIBUTING](https://github.com/andela-araimi/Checkpoint-one/edit/master/CONTRIBUTING.md/%22CONTRIBUTING%22) file for detailed contribution guidelines.

**Security**
--------


----------
If you discover any issue, kindly contact ademola.raimi@andela.com

**Credits**
-------


----------


Potato-ORM is maintained by Raimi Ademola.

**License**
-------


----------


UrbanDictionary is released under the [MIT Licence](https://github.com/andela-araimi/Checkpoint-one/blob/master/LICENSE.md/%22MIT%20License%22). See the bundled LICENSE file for more details.

