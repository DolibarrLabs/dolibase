# Dolibase

[![Stable Version](https://img.shields.io/badge/stable-v2.8.6-brightgreen.svg)](https://github.com/AXeL-dev/dolibase/releases/tag/v2.8.6)
[![PHP Min](https://img.shields.io/badge/PHP-%3E%3D%205.3-blue.svg)](https://github.com/php)
[![Dolibarr Min](https://img.shields.io/badge/Dolibarr-%3E%3D%203.8.x-orange.svg)](https://github.com/Dolibarr/dolibarr)

Dolibase is a set of reusable code & architecture that make coding [Dolibarr](https://github.com/Dolibarr/dolibarr) modules more faster :rocket: and easier.

## Why to use it?

- **Open source**: You can check the source code & contribute to the project if you want.
- **Ensure backward compatibility**: Your module(s) will work even on old Dolibarr versions (starting from version 3.8).
- **Less & clean code**: Write less code in a clean way & reduce repetitive code frequency.

## How it works?

Dolibase is following the main dolibarr design pattern with some few adjustments to fit its needs.

Below a simple graph that demonstrate directory structure differences between a basic dolibarr module & a dolibase module.

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
- `admin/setup.php` and `admin/about.php` contains module settings & author informations (they can only be consulted by an administrator).
- `core/modules/modMyModule.class.php` is the module main configuration file or class, it contains all the informations about the module: name, description, menus, user permissions, etc.. In dolibase, this is a bit different, some of the module configuration are set in the `config.php` file in a way to allow reusing them in other parts of the module.
- `core/boxes/mywidget.php` is a module widget that can be displayed in the dashboard of Dolibarr.
- `core/triggers` contains [trigger](https://wiki.dolibarr.org/index.php/Triggers) files that allows you to execute personalized code after a Dolibarr event.
- `class` folder may contains your objects class & functions, sql queries, etc.. It's a kind of model(s) container if you're familiar with the [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture.
- `dolibase` folder contains all the code & logic of dolibase.
- `img` is a folder for your images (note that module's picture should start with the `object_` prefix).
- `langs` folder contains all the translations related to your module.
- `sql` folder contains the sql files to create or update the tables of your module.
- `css` folder should contain your css files.
- `js` folder is for your javascript files.
- `myfirstpage.php` is considered as your first module page in the example above.
- `config.php` is the configuration file used by dolibase, it contains the main configuration for your module @see [config.default.php](https://github.com/AXeL-dev/dolibase/blob/master/test/config.default.php).
- `autoload.php` is responsible of loading the module configuration, dolibarr environment & dolibase requirements. You can even add any php file you want to be auto-loaded @see [autoload.default.php](https://github.com/AXeL-dev/dolibase/blob/master/test/autoload.default.php).

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

**Note** that `localhost/dolibarr` may change depending on your dolibarr installation & your domain name.

## Examples

1. Module class:

```php
<?php

// Load Dolibase
dol_include_once('/myfirstmodule/autoload.php');

// Load Dolibase Module class
dolibase_include_once('/core/class/module.php');

class modMyFirstModule extends DolibaseModule
{
    public function loadSettings()
    {
        // Add constant(s)
        $this->addConstant('MY_FIRST_MODULE_CONST', 'test');

        // Add widget(s)
        $this->addWidget('mybox.php');

        // Add CSS & JS files
        $this->addCssFile('mycss.css.php')
             ->addJsFile('myjs.js.php');

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
dolibase_include_once('/core/class/page.php');

// Create Page using Dolibase
$page = new Page('My Page Title', '$user->rights->myfirstmodule->read'); // set page title & control user access

// Print page header & dolibarr's main menus
$page->begin();

echo 'Hello world!';

// Print page footer
$page->end();

```

3. Query builder:

```php
<?php

// Load Dolibase
include_once 'autoload.php';

// Load Dolibase Page class
dolibase_include_once('/core/class/page.php');

// Load Query Builder class
dolibase_include_once('/core/class/query_builder.php');

// Create Page using Dolibase
$page = new Page('Test Query Builder');

$page->begin();

$titles = array();
$queries = array();

// 1st query
$titles[]  = 'Select with order by & limit';
$queries[] = QueryBuilder::getInstance()
                         ->select('login, firstname, lastname')
                         ->from('user')
                         ->orderBy('rowid', 'ASC')
                         ->limit(5);

// 2nd query
$titles[]  = 'Insert';
$queries[] = QueryBuilder::getInstance()
                         ->insert('user', array('login' => 'axel', 'lastname' => 'AXeL'));

// 3rd query
$titles[]  = 'Update';
$queries[] = QueryBuilder::getInstance()
                         ->update('user', array('firstname' => 'Dev'))
                         ->where("login = 'axel'");

// 4th query
$titles[]  = 'Select where Like';
$queries[] = QueryBuilder::getInstance()
                         ->select('login, firstname, lastname')
                         ->from('user')
                         ->where("login LIKE 'axe%'");

// 5th query
$titles[]  = 'Delete';
$queries[] = QueryBuilder::getInstance()
                         ->delete('user')
                         //->where(array('login' => 'axel', 'lastname' => 'AXeL'))
                         ->where("login = 'axel'")
                         ->where("lastname = 'AXeL'")
                         ->orWhere("firstname = 'Dev'");

// 6th query
$titles[]  = 'Count';
$queries[] = QueryBuilder::getInstance()
                         ->select('count(*) as count')
                         ->from('user')
                         ->where("login = 'axel'");

// 7th query
$titles[]  = 'Left join';
$queries[] = QueryBuilder::getInstance()
                         ->select('p.ref as product, p.price as price, u.login as author')
                         ->from('product as p')
                         ->join('user as u', 'u.rowid = p.fk_user_author', 'left');

// 8th query
$titles[]  = 'Multi Left join';
$queries[] = QueryBuilder::getInstance()
                         ->select('p.ref as product, u.login as author, pl.ref as propal')
                         ->from('product as p')
                         ->join('user as u', 'u.rowid = p.fk_user_author', 'left')
                         ->join('propaldet as pd', 'pd.fk_product = p.rowid', 'left')
                         ->join('propal as pl', 'pl.rowid = pd.fk_propal', 'left');

// 9th query
$titles[]  = 'IQ Join with Select';
$queries[] = QueryBuilder::getInstance()
                         ->select('p.ref as product, u.login as author')
                         ->from(array('product as p', 'user as u'))
                         ->where('p.fk_user_author = u.rowid');

// 10th query
$titles[]  = 'Subquery'; 
$subquery  = QueryBuilder::getInstance()->select('p.fk_user_author')->from('product', 'p')->get();
$queries[] = QueryBuilder::getInstance()
                         ->select('u.login as user')
                         ->from('user', 'u')
                         ->where("u.rowid IN ($subquery)");

// Show queries
foreach ($queries as $i => $query) {
    echo '<h2><u>'.($i + 1).') '.$titles[$i].'</u></h2>';
    echo '<h3>query:</h3>'.$query->get();
    echo '<h3>result:</h3>'.array_to_table($query->result());
    echo '<h3>result count:</h3>'.$query->count();
    echo '<h3>affected rows:</h3>'.$query->affected();
}

$page->end();

```

:tada: So simple isn't it ?!

Find more module examples in the [test](https://github.com/AXeL-dev/dolibase/tree/master/test) folder or check the [documentation](https://axel-dev.github.io/dolibase/).

## Tips

1. **Disable automatic check for module updates**:

   Dolibase may check automatically for a newer module(s) version(s) on [Dolistore](https://www.dolistore.com/) & it may slowdown your modules list page if you have a slow connection or if you are using many modules based on dolibase, so to disable this feature for all the modules at once:

      - Go to **Home** > **Setup** > **Modules** > **Other setup**.
      - Add a new entry as `DOLIBASE_DISABLE_CHECK_FOR_UPDATES` & give it the value `1`.
      - Confirm using **Add** button & that's it.

2. **Enable experimental & development modules**:

   By default experimental & development modules are disabled/hidden on Dolibarr, if you want to enable them:

      - Go to **Home** > **Setup** > **Modules** > **Other setup**.
      - Search for the entry name `MAIN_FEATURES_LEVEL` (or add it if it don't exist), then set the value to `2`.
      - Confirm using **Modify** button at the bottom.

## Changelog

See [changelog](changelog.md).

## License

Dolibase is licensed under the [MIT](LICENSE) license.
