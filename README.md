<h1>Potato ORM</h1>
​
[![Coverage Status](https://coveralls.io/repos/github/andela-araimi/potato-orm/badge.svg?branch=master)](https://coveralls.io/github/andela-araimi/potato-orm?branch=master) [![Build Status](https://travis-ci.org/andela-araimi/potato-orm.svg?branch=master)](https://travis-ci.org/andela-araimi/potato-orm) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andela-araimi/potato-orm/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andela-araimi/potato-orm/?branch=master)
​
​
<hr />
​
<p>
Potato-ORM is based on concepts borrowed from the Laravel framework. It is a package that lets you perform basic <b>Create Read Update Delete (CRUD)</b> operations on your database.
​
</p>
​
<h1>Installation</h1>
<hr />
<p>To install this package, PHP 5.5+ and Composer are required</p>
​
  <pre> $ composer require Demo/potato-orm </pre>
​
<hr />
​
<h1>Usage</h1>
​
<p>
​
To use this package, you will need to extend the base class. The base class is an abstract  class called "DataBaseModel". Let's say you wish to perform CRUD operations on the users table. You will create a corresponding users class which should look like this:
</p>
    <pre>
    use Demo;
    
    class User extends DataBaseModel
    {
    
    }
</pre>
​
 <h3>Saving a new record to the table</h3>
<pre>
         $user               = new User();   
         $user->name         = "Prosper Otemuyiwa";
         $user->sex          = "m";   
         $user->occupation   = "Trainer";
         $user->organisation = "Andela";
         $user->year         = 2009;
        
         $user->save();  
</pre>
​
 <b>Read all record from the table</b>
 
<pre>
         $users = User::getAll();
         
         print_r($users);
​
</pre>
​
<b>Read from a particular record in the table</b>
​
<pre>
        $user = User::findById(3);
        
        print_r($user->getById());
</pre>
​
<b>Update a record in the table. For example, update the name of the tenth record in the users table:</b>
 
 <pre>
        $user       = User::findById(10);
         
        $user->name = "Gbolohan Kuti";
         
        $user->save();
</pre>
​
<b>Delete a record in the table. For example, delete the eighth record in the users table:</b>
 
​
        $users = User::destroy(8);
​
<b>Exception Handling</b>
​
<p>
To make this package degrade gracefully, It has to be wrapped under try and catch in order for all exceptions to be caught.</p>
​
    
<p>Catching exception on save new record:</p>
​
       
    try {
                     
          $user                 = new User();     
          $user->name           = "Prosper Otemuyiwa";
          $user->sex            = "m";   
          $user->occupation     = "Trainer";
          $user->organisation   = "Andela";
          $user->year           = 2009;
                
          $user->save(); 
                
        }  catch(Exception $e) {
             print($e->getMessage());
       } 
​
​
 <b>Catching exception on reading from the table:</b>
​
​
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
​
 
​
<b>Catching exception on updating the table:</b>
​
​
            try {
                
                $user       = User::findById(10);
                $user->name = "Gbolohan Kuti";
                $user->save();
             } catch(Exception $e) {
                print($e->getMessage());
             } 
​
​
​
<b>Catching exception on deleting from the table:</b>
​
          try {
          
               $users = User::destroy(8);
               
             } catch(Exception $e) {
               print($e->getMessage());
             } 
​
​
​
<h1>Testing</h1>
<hr />
​
​
Run the following command in the potato-orm directory:
​
  <pre>  ~ phpunit tests </pre>
​
​
<h2>Change log</h2>
​
<hr />
​
Please check out [CHANGELOG](https://github.com/andela-araimi/potato-orm/blob/master/CHANGELOG.md) file for information on what has changed recently.
​
<h2>Contributing</h2>
​
​
​
Please check out [CONTRIBUTING](https://github.com/andela-araimi/potato-orm/blob/master/CONTRIBUTING.md) file for detailed contribution guidelines.
​
<h2>Security</h2>
​
​
If you discover any issue, kindly contact ademola.raimi@andela.com
​
<h2>Credits</h2>
​
Potato-ORM is maintained by Raimi Ademola.
​
<h2>License</h2>
​
​
Potato-ORM is released under the [MIT Licence](https://github.com/andela-araimi/potato-orm/blob/master/LICENSE.md). See the bundled LICENSE file for more details.
