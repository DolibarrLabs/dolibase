# 🚀 Dolibase

[![Stable Version](https://img.shields.io/badge/stable-v3.0.0-brightgreen.svg)](https://github.com/AXeL-dev/dolibase/releases/tag/v3.0.0)
[![PHP Min](https://img.shields.io/badge/PHP-%3E%3D%205.3-blue.svg)](https://github.com/php)
[![Dolibarr Min](https://img.shields.io/badge/Dolibarr-%3E%3D%203.8.x-orange.svg)](https://github.com/Dolibarr/dolibarr)

**WARNING**: Dolibase is discontinued. Use [DAMB](https://gitlab.com/AXeL-dev/damb) for a better alternative.

Dolibase is a set of reusable code & architecture that makes coding [Dolibarr](https://github.com/Dolibarr/dolibarr) modules more faster and easier.

## Why to use it?

- **Module builder**: Dolibase has its own module builder that you can use to speed up your developments.
- **Easy installation**: Install in 1 click using the [Dolibase Installer](https://www.dolistore.com/en/modules/1060-Dolibase-Installer.html) module.
- **Ready-to-use components**: Several components are available like pages, pdf models, classes & functions.
- **Ensure backward compatibility**: Your module(s) will work even on old Dolibarr versions (starting from version 3.8).
- **Less & clean code**: Write less code in a clean way & reduce repetitive code frequency.

## How it works?

Dolibase is following the main design pattern of dolibarr with some few adjustments to fit its needs.

Below a simple graph that demonstrate the directory structure differences between a basic dolibarr module & a dolibase module.

```bash
dolibarr module                                         dolibase module
├── admin                                               ├── admin
│   └── setup.php                                       │   ├── setup.php
├── core                                                │   └── about.php
│   ├── modules                                         ├── core
│   │   └── modMyModule.class.php                       │   ├── modules
│   ├── boxes                                           │   │   └── modMyModule.class.php
│   │   └── mywidget.php                                │   ├── boxes
│   └── triggers                                        │   │   └── mywidget.php
│       └── interface_**_modMyModule_*.class.php        │   └── triggers
├── class                                               │       └── interface_**_modMyModule_*.class.php
│   └── *.class.php                                     ├── class
├── img                                                 │   └── *.class.php
│   └── object_mypicture.png                            ├── dolibase
├── langs                                               ├── img
│   ├── en_US                                           │   └── object_mypicture.png
│   │   └── mymodule.lang                               ├── langs
│   └── **_**                                           │   ├── en_US
│       └── mymodule.lang                               │   │   └── mymodule.lang
├── sql                                                 │   └── **_**
│   ├── *.sql                                           │       └── mymodule.lang
│   └── *.key.sql                                       ├── sql
├── css                                                 │   ├── *.sql
│   └── *.css                                           │   └── *.key.sql
├── js                                                  ├── css
│   └── *.js                                            │   └── *.css
├── tpl                                                 ├── js
│   └── *.tpl.php                                       │   └── *.js
└── myfirstpage.php                                     ├── tpl
                                                        │   └── *.tpl.php
                                                        ├── myfirstpage.php
                                                        ├── config.php (mandatory)
                                                        └── autoload.php (mandatory)
```

**Explanation:**
- `admin/setup.php` and `admin/about.php` contains the module settings & the author informations (they can only be consulted by an administrator).
- `core/modules/modMyModule.class.php` is the module main configuration file or class, it contains all the informations about the module: name, description, menus, user permissions, etc.. In dolibase, this is a bit different, the module main configuration is set in the `config.php` file in a way to allow reusing it in other parts of the module.
- `core/boxes/mywidget.php` is a module widget that can be displayed in the dashboard of Dolibarr.
- `core/triggers` contains [trigger](https://wiki.dolibarr.org/index.php/Triggers) files that allows you to execute personalized code after a dolibarr event.
- `class` folder may contain your objects class & functions, sql queries, etc.. It's a kind of model(s) container if you're familiar with the [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture.
- `dolibase` folder contains all the code & logic of dolibase.
- `img` is a folder for your images (note that module's picture should start with the `object_` prefix).
- `langs` folder contains all the translations related to your module.
- `sql` folder contains the sql files to create or update the tables of your module.
- `css` folder should contain your css files.
- `js` folder is for your javascript files.
- `myfirstpage.php` is considered as your first module page in the example above.
- `config.php` is the configuration file used by dolibase, it contains the main configuration for your module @see [config.default.php](test/config.default.php).
- `autoload.php` is responsible of loading the module configuration, dolibarr environment & dolibase requirements. You can even add any php file you want to be auto-loaded inside it @see [autoload.default.php](test/autoload.default.php).

## Installation

Dolibase can act in 2 different ways:
 - **Globally**: this means that dolibase needs to be installed only once in dolibarr's root directory & then all dolibase modules will use the global version.
 - **Internally**: each module can have its own version of dolibase (inside the module folder), so this method doesn't require any pre-installation, but some conflits may occur between modules using an old dolibase version & those using a new one.

So, to install dolibase globally, just unzip [it](https://github.com/AXeL-dev/dolibase/releases) inside your dolibarr root directory or use the [Dolibase Installer](https://www.dolistore.com/en/modules/1060-Dolibase-Installer.html) module.

## Quick start

Starting from version [2.4.0](https://github.com/AXeL-dev/dolibase/releases/tag/v2.4.0), you can easily generate your modules & widgets using Dolibase Builder.

To create a new module, simply go to the dolibase builder page & follow the instructions:

```
http://localhost/dolibarr/htdocs/dolibase/builder
```

**Note** that `localhost/dolibarr` may change depending on your dolibarr installation & your domain name.

Check this [demonstration video](https://youtu.be/BmbAXGRQqyA) for a quick example.

## Examples

1. Module class:

```php
<?php

// Load Dolibase
dol_include_once('mymodule/autoload.php');

// Load Dolibase Module class
dolibase_include_once('core/class/module.php');

class modMyModule extends DolibaseModule
{
    public function loadSettings()
    {
        // Add constant(s)
        $this->addConstant('MY_MODULE_CONST', 'test');

        // Add widget(s)
        $this->addWidget('mywidget.php');

        // Add CSS & JS files
        $this->addCssFile('mycss.css')
             ->addJsFile('myjs.js');

        // Set user permission(s)
        $this->addPermission('read', 'Read permission', 'r');

        // Add menu(s)
        $this->addTopMenu($this->config['other']['top_menu_name'], 'MyTopMenu', '/mymodule/index.php?test=1')
             ->addLeftMenu($this->config['other']['top_menu_name'], 'myleftmenu', 'MyLeftMenu', '/mymodule/index.php?test=2')
             ->addLeftSubMenu($this->config['other']['top_menu_name'], 'myleftmenu', 'mysubleftmenu', 'MySubLeftMenu', '/mymodule/index.php?test=3');
    }
}

```

2. Module page:

```php
<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('core/class/page.php');

// Create Page using Dolibase
$page = new Page('My Page Title', '$user->rights->mymodule->read'); // set page title & control user access

// Print page header & dolibarr's main menus
$page->begin();

echo 'Hello world!';

// Print page footer
$page->end();

```

3. Widget class:

```php
<?php

// Load Dolibase
dol_include_once('mymodule/autoload.php');

// Load Dolibase Widget class
dolibase_include_once('core/class/widget.php');

// Load Dolibase QueryBuilder class
dolibase_include_once('core/class/query_builder.php');

class MyWidget extends Widget
{
    /**
     * @var Widget Label
     */
    public $boxlabel = 'MyWidget';
    /**
     * @var Widget Picture
     */
    public $boximg = 'mywidget.png';
    /**
     * @var Widget Position
     */
    public $position = 1;
    /**
     * @var Widget is Enabled
     */
    public $enabled = 1;


    /**
     * Load data into info_box_contents array to show array later. Called by Dolibarr before displaying the box.
     *
     * @param int $max Maximum number of records to load
     * @return void
     */
    public function loadBox($max = 5)
    {
        // Set title
        $this->setTitle('Users List');

        // Get users login, firstname & lastname from database
        $qb = QueryBuilder::getInstance()
                          ->select('login, firstname, lastname')
                          ->from('user')
                          ->orderBy('rowid', 'ASC');

        // Show users
        foreach ($qb->result() as $row) {
            $this->addContent($row->login)
                 ->addContent($row->firstname)
                 ->addContent($row->lastname)
                 ->newLine();
        }
    }
}

```

:tada: So simple isn't it ?!

Find more module examples in the [test](test) folder.

## Advanced features

With dolibase & [debugbar module](https://github.com/AXeL-dev/dolibarr-debugbar-module) you get a bunch of benefits:

**1)** Easily debug your modules.

![debug](https://www.dolistore.com/4086/Debug-bar.jpg)

To add a message to debug bar simply call the `dolibase_debug` function:

```php
dolibase_debug('your message');
```

**2)** Access to dolibarr configuration variables & constants.

![config](https://www.dolistore.com/4087/Debug-bar.jpg)

**3)** Get all the executed queries even after a page redirection.

![database](https://www.dolistore.com/4084/Debug-bar.jpg)

To save queries on page redirection use the `dolibase_redirect` function:

```php
dolibase_redirect('page.php');
```

**4)** Optimise load & response time of your modules.

![timeline](https://www.dolistore.com/4083/Debug-bar.jpg)

To start measuring time call the `start_time_measure` function then provide a measure name & label:

```php
start_time_measure('measure_1', 'Label');
```

To stop the time measuring call the `stop_time_measure` function with your measure name as a parameter:

```php
stop_time_measure('measure_1');
```

**5)** Check dolibarr logs instantly (logs module should be enabled).

![logs](https://www.dolistore.com/4085/Debug-bar.jpg)

## Documentation

Before consulting the [documentation](https://axel-dev.github.io/dolibase/) you must have some knowledge about how dolibarr works and how to develop a module, otherwise you are invited to check the links below:

* [Dolibarr documentation](https://www.dolibarr.org/documentation-home).
* [Developer documentation](https://wiki.dolibarr.org/index.php/Developer_documentation).
* [Module development](https://wiki.dolibarr.org/index.php/Module_development).

## Support me

Check my own modules collection on [Dolistore](https://www.dolistore.com/en/search?orderby=position&orderway=desc&search_query=axel).

## Changelog

See [changelog](changelog.md).

## Contributing

Follow [contributing guidelines](https://github.com/AXeL-dev/contributing/blob/master/README.md).

## License

Dolibase is licensed under the [MIT](LICENSE) license.
