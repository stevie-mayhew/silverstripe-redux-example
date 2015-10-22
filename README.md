# SilverStripe Redux Example
Just a small toy example showing React + Redux in tandem with SilverStripe.

## Setup
### SilverStripe Setup
You'll need to set up a `_ss_environment.php` file, it might look something like this:

```php
<?php
define('SS_ENVIRONMENT_TYPE', 'dev');

/* Database connection */
define('SS_DATABASE_SERVER', '127.0.0.1');
define('SS_DATABASE_USERNAME', 'root');
define('SS_DATABASE_PASSWORD', '');
define('SS_DATABASE_NAME', 'redux_example'); // add a database name here

/* Configure a default username and password to access the CMS on all sites in this environment. */
define('SS_DEFAULT_ADMIN_USERNAME', 'demo@demo.com');
define('SS_DEFAULT_ADMIN_PASSWORD', 'password');
```

Run `composer install` from the root of the site.

### Seeding data
You can run `./framework/sake seed` to populate some data.

### Run project
If you've seeded the data, you should be able to visit `/events` to view the project in action.

### Build JS
From `themes/base/` - you'll need webpack installed globally and to run `npm install` prior to running the build. You
can then build the javascript with `make build`.

