# Pheanstalk Worker class for Laravel #

This class processes your Beanstalk job queues in Laravel in a simple way. It requires the Laravel bundle [Pheanstalk](https://github.com/mikelbring/Pheanstalk).

## Usage ##

Copy the class into your `tasks` folder and implement the function `exampletube`. This function will be called whenever a new job comes to the queue.

Put your jobs in the queue. Use either serialize or json_encode to (I like to use serialize to preserve all the object properties of ORM models and be able to use them in the worker, but this will depend on the architecture of your app.
```php
<?php
Pheanstalk::useTube( 'example-tube' )->put( serialize( array( 'data' => $data ) ) );

?>
```

After that you can start the worker it with `php /webservers/my-laravel-app/artisan beanstalk`.  Normally you would run this command every minute using a cronjob.



