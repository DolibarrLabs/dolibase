# Dolibase

[![Stable Version](https://img.shields.io/badge/stable-v2.9.6-brightgreen.svg)](https://github.com/AXeL-dev/dolibase/releases/tag/v2.9.6)
[![PHP Min](https://img.shields.io/badge/PHP-%3E%3D%205.3-blue.svg)](https://github.com/php)
[![Dolibarr Min](https://img.shields.io/badge/Dolibarr-%3E%3D%203.8.x-orange.svg)](https://github.com/Dolibarr/dolibarr)

Dolibase is a set of reusable code & architecture that make coding [Dolibarr](https://github.com/Dolibarr/dolibarr) modules more faster :rocket: and easier.

## Why to use it?

- **Open source**: You can check the source code & contribute to the project if you want.
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
└── myfirstpage.php                                     ├── js
                                                        │   └── *.js
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
- `config.php` is the configuration file used by dolibase, it contains the main configuration for your module @see [config.default.php](https://github.com/AXeL-dev/dolibase/blob/master/test/config.default.php).
- `autoload.php` is responsible of loading the module configuration, dolibarr environment & dolibase requirements. You can even add any php file you want to be auto-loaded inside it @see [autoload.default.php](https://github.com/AXeL-dev/dolibase/blob/master/test/autoload.default.php).

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

## Examples

1. Module class:

```php
<?php

// Load Dolibase
dol_include_once('myfirstmodule/autoload.php');

// Load Dolibase Module class
dolibase_include_once('core/class/module.php');

class modMyFirstModule extends DolibaseModule
{
    public function loadSettings()
    {
        // Add constant(s)
        $this->addConstant('MY_FIRST_MODULE_CONST', 'test');

        // Add widget(s)
        $this->addWidget('mywidget.php');

        // Add CSS & JS files
        $this->addCssFile('mycss.css')
             ->addJsFile('myjs.js');

        // Set user permissions
        $this->addPermission('read', 'Read permission', 'r');

        // Add menu(s)
        $this->addTopMenu($this->config['other']['top_menu_name'], 'MyFirstMenu', '/myfirstmodule/index.php?test=1')
             ->addLeftMenu($this->config['other']['top_menu_name'], 'myleftmenu', 'MyLeftMenu', '/myfirstmodule/index.php?test=2')
             ->addLeftSubMenu($this->config['other']['top_menu_name'], 'myleftmenu', 'mysubleftmenu', 'MySubLeftMenu', '/myfirstmodule/index.php?test=3');
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
$page = new Page('My Page Title', '$user->rights->myfirstmodule->read'); // set page title & control user access

// Print page header & dolibarr's main menus
$page->begin();

echo 'Hello world!';

// Print page footer
$page->end();

```

:tada: So simple isn't it ?!

Find more module examples in the [test](https://github.com/AXeL-dev/dolibase/tree/master/test) folder or check the [documentation](https://axel-dev.github.io/dolibase/).

## Useful links

* [Dolibarr documentation](https://www.dolibarr.org/documentation-home).
* [Developer documentation](https://wiki.dolibarr.org/index.php/Developer_documentation).
* [Module development](https://wiki.dolibarr.org/index.php/Module_development).

## Changelog

See [changelog](changelog.md).

## License

Dolibase is licensed under the [MIT](LICENSE) license.
