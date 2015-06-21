# Introduction

Using the framework is very easy. There are no complex routing engines, regular expressions, ORMs or advanced controllers you have to learn.

# Controllers

Controllers should extend HF_Controller (which should already be included).

The controller constructor has a few parameters that you will be interested in.

   public function __construct($config, $core, $tpl = null)

$config is a copy of the config array. This will contain values such as site name and SMTP config. Currently you cannot change the values of config at runtime.

$core is a connection to HF_Core which contains useful methods such as mail_admin and mail_user

$tpl is a created instance of the H2o template engine. It has configs set to point to the right directories to store cache files and where to look for template files.

You could extend HF_Controller to include site specific information such as user information.

Controllers should be in the folder controller with lowercase names. Inside of the file it should contain a class name with the name of the file.

For example:

test.php

    class test extends HF_Controller
    {
        public function index()
        {

        }
    }

You can have other content in the file or include other files - but it must contain at least a class with the name of the file.

# Filesystem

The file system plays an important role in serving your site.

    | controllers
    ---- main.php
    ---- dashboard.php

Let's take an example:

http://yoursite.com/

The default route is to invoke the index method of the main controller.

http://yoursite.com/dashboard/

This will invoke the index method of the dashboard controller.

Let's say you had this folder structure instead:

    | controllers
    ---- dashboard
    ---- ---- user.php
    ---- ---- main.php

http://yoursite.com/dashboard/

This will load index in dashboard/main.php

http://yoursite.com/dashboard/user/

This will load index in dashboard/user.php

Then of course

http://yoursite.com/dashboard/user/profile/1/

This will load the profile method in dashboard/user.php with 1 as the first parameter.

Note: Anything after the method name is a parameter. HF is smart enough to be able to detect if a user attempts to use too many or too few parameters HF will throw an error.

# Config

HF has a set of default config values in system/engine/config-default.php. You can override those values by setting one or more of those values in application/config.php. Just be sure to return $config as the last statement. Setting $config["DEBUG"] in config.php is the suggested way of toggling debug mode in your site rather than modifying config-default.php.

You may even copy config-default.php to application/config.php if you are going to be modifying all the config options.

# Errors

HF will handle errors gracefully. If an error happens or an exception is thrown it will either show it to you on the screen or send you an email with the information. This behavior can be changed by setting the site into debug mode by setting the $config["DEBUG"] flag to true/false.

The error messages displaying by HF is very basic and should be used in a production site.

Currently HF supports two errors:

* 500 - system error
* 404 - page not found

If you would like to change the behavior of how HF handles those just create an application/status.php file with a class called status that extends HF_Status. Then implement your own functions.

Note: HF_Status extends HF_Controller so you can use the template engine.

For example:

    class status extends HF_Status
    {
        public function Status404()
        {
            echo "This is not the page you are looking for";
        }

        public function Status500()
        {
            echo ">.o ow! stop doing that!";
        }
    }

# Vendor

There is a directory called system/vendor which contains projects that other people have been working on. I include these as a convenience to help you create sites faster. I also make sure to keep them decoupled from HF as much as possible in case you don't want to use them or remove them.

HF does leverage StackTracePrint.php, however, it's just a couple of functions so if you didn't like it you could delete the code in the functions.

HF may use some pear packages (such as ones for email) you can see which ones it does use here:

* pear install Mail - http://pear.php.net/package/Mail_Mime/
* pear install Mail_Mime - http://pear.php.net/package/Mail/

Note: HF does contain a simple SMTP class, however, it doesn't support SSL/TLS so you will need to use the Pear package for that (for example if you are going to send email through gmail or something).

HF includes a small DB library written by David Pennington you can read more about how to use it [here](https://github.com/Xeoncross/DByte)

HF also includes an easy to use template library written by Taylor Luk you can read more about it [here](https://github.com/speedmax/h2o-php).