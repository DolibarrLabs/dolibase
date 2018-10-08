# Dolibase

[![Stable Version](https://img.shields.io/badge/stable-v2.3.8-brightgreen.svg)](https://github.com/AXeL-dev/dolibase/releases/tag/v2.3.8)
[![PHP Min](https://img.shields.io/badge/PHP-%3E%3D%205.3-blue.svg)](https://github.com/php)
[![Dolibarr Min](https://img.shields.io/badge/Dolibarr-%3E%3D%203.8.x-orange.svg)](https://github.com/Dolibarr/dolibarr)

Dolibase is a set of reusable code & architecture that make coding [Dolibarr](https://github.com/Dolibarr/dolibarr) modules more faster and easier.

## How it works?

Dolibase is following the main dolibarr design pattern with some few adjustments to fit its needs.

Below a simple graph that demonstrate directory structure differences between a basic dolibarr module & dolibase module.

```bash
dolibarr module                                         dolibase module
├── admin                                               ├── admin
│   └── setup.php                                       │   ├── setup.php
├── core                                                │   └── about.php
│   ├── modules                                         ├── core
│   │   └── modMyModule.class.php                       │   ├── modules
│   ├── boxes                                           │   │   └── modMyModule.class.php
│   │   ├── widget1.php                                 │   ├── boxes
│   │   └── widget2.php                                 │   │   ├── widget1.php
│   └── triggers                                        │   │   └── widget2.php
│       └── interface_**_modMyModule_*.class.php        │   └── triggers
├── class                                               │       └── interface_**_modMyModule_*.class.php
│   └── *.class.php                                     ├── class
├── img                                                 │   └── *.class.php
│   └── object_mypicture.png                            ├── dolibase
├── langs                                               ├── img
│   ├── en_US                                           │   └── object_mypicture.png
│   │   └── mymodule.lang                               ├── langs
│   ├── fr_FR                                           │   ├── en_US
│   │   └── mymodule.lang                               │   │   └── mymodule.lang
│   └── **_**                                           │   ├── fr_FR
│       └── mymodule.lang                               │   │   └── mymodule.lang
├── sql                                                 │   └── **_**
│   ├── *.sql                                           │       └── mymodule.lang
│   ├── *.key.sql                                       ├── sql
│   └── data.sql (optional)                             │   ├── *.sql
├── css                                                 │   ├── *.key.sql
│   └── *.css                                           │   └── data.sql (optional)
├── js                                                  ├── css
│   └── *.js                                            │   └── *.css
└── myfirstpage.php                                     ├── js
                                                        │   └── *.js
                                                        ├── myfirstpage.php
                                                        └── config.php (mandatory)
```

**Explanation:**
- `admin/setup.php` and `admin/about.php` contains module settings & author informations (they can only be consulted by an administrator).
- `core/modules/modMyModule.class.php` is the module main configuration file or class, it contains all the informations about the module: name, description, menus, user permissions, etc.. In dolibase, this is a bit different, some of the module configuration are set in the `config.php` file in a way to allow reusing them in other parts of the module.
- `core/boxes/widget1.php` is one of the module widgets that can be displayed in the dashboard of Dolibarr.
- `core/triggers` contains [trigger](https://wiki.dolibarr.org/index.php/Triggers) files that allows you to execute personalized code after a Dolibarr event.
- `class` folder may contains your objects class & functions, sql queries, etc.. It's a kind of model(s) container if you're familiar with the [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture.
- `dolibase` folder contains all the code & logic of dolibase.
- `img` is a folder for your images (note that module's picture should start with the \`**object_**\` prefix).
- `langs` folder contains all the translations related to your module.
- `sql` folder contains the sql files to create or update the tables of your module.
- `css` folder should contain your css files.
- `js` folder is for your javascript files.
- `myfirstpage.php` is considered as your first module page in the example above.
- `config.php` is the configuration file used by dolibase, it contains the main configuration for your module @see [config.default.php](https://github.com/AXeL-dev/dolibase/blob/master/test/config.default.php).

## Installation

Dolibase can act in 2 different ways:
 - **Globally**: this means that dolibase needs to be installed only once in dolibarr's root directory & then all dolibase modules will use the global version.
 - **Internally**: each module can have its own version of dolibase (inside the module folder), so this method doesn't require any pre-installation, but some conflits may occur between modules using an old dolibase version & those who use a new one.

So, to install dolibase globally, just unzip [it](https://github.com/AXeL-dev/dolibase/releases) inside your dolibarr root directory.

## Quick start

Starting from version [2.4.0](https://github.com/AXeL-dev/dolibase/releases/tag/v2.4.0), you can easily generate your modules & widgets using Dolibase Builder.

To create a new module, simply go to the dolibase builder page & follow the instructions:

```
http://localhost/dolibarr/htdocs/dolibase/builder
```

Note that `localhost/dolibarr` may change depending on your dolibarr installation & your domain name.

## Examples

1. Module class:

```php
<?php

// Load Dolibase config file for this module (mandatory)
dol_include_once('/myfirstmodule/config.php');

// Load Dolibase Module class
dolibase_include_once('/core/class/module.php');

class modMyFirstModule extends DolibaseModule
{
	public function loadSettings()
	{
		// Add constant(s)
		$this->addConstant("MY_FIRST_MODULE_CONST", "test");

		// Add widget(s)
		$this->addWidget("mybox.php");

		// Add CSS & JS files
		$this->addCssFile("mycss.css.php");
		$this->addJsFile("myjs.js.php");

		// Set user permissions
		$this->addPermission("read", "Read permission", "r");

		// Add menu(s)
		$this->addTopMenu($this->config['other']['top_menu_name'], "MyFirstMenu", "/myfirstmodule/index.php?test=1");
		$this->addLeftMenu($this->config['other']['top_menu_name'], "myleftmenu", "MyLeftMenu", "/myfirstmodule/index.php?test=2");
		$this->addLeftSubMenu($this->config['other']['top_menu_name'], "myleftmenu", "mysubleftmenu", "MySubLeftMenu", "/myfirstmodule/index.php?test=3");
	}
}

?>
```

2. Module page:

```php
<?php

// Load Dolibase config file for this module (mandatory)
include_once 'config.php';

// Load Dolibase Page class
dolibase_include_once('/core/class/page.php');

// Create Page using Dolibase
$page = new Page("My Page Title", '$user->rights->myfirstmodule->read'); // set page title & control user access

// Print page header & dolibarr's main menus
$page->begin();

echo 'Hello world!';

// Print page footer
$page->end();

?>
```

:tada: So simple isn't it ?!

Find more module examples in the [test](https://github.com/AXeL-dev/dolibase/tree/master/test) folder or check the [Dolibarr developer documentation](https://wiki.dolibarr.org/index.php/Developer_documentation).
