# Dolibase

[![Stable Version](https://img.shields.io/badge/stable-1.2.0-brightgreen.svg)](https://github.com/AXeL-dev/dolibase/releases)
[![PHP Min](https://img.shields.io/badge/PHP-%3E%3D%205.0-blue.svg)](https://github.com/php)
[![Dolibarr Min](https://img.shields.io/badge/Dolibarr-%3E%3D%203.8.x-orange.svg)](https://github.com/Dolibarr/dolibarr)

Dolibase is a framework that make coding [Dolibarr](https://github.com/Dolibarr/dolibarr) modules more faster, more easier in a way that your code is more organised and more easy to read & maintain.

Example of a new module class:

```php
class modMyFirstModule extends DolibarrModules
{
	public function __construct($db)
	{
		// Create Module using Dolibase
		$module = new Module($this);

		// Add constant(s)
		$module->addConstant("MY_FIRST_MODULE_CONST", "test");
		$module->addConstant("MY_FIRST_MODULE_SECOND_CONST", "test2");

		// Add widgets
		$module->addWidget("mybox.php");

		// Add CSS or even JS files
		$module->addCssFile("mycss.css.php");

		// Set permissions
		$module->addPermission("create", "Create permission", "c");
		$module->addPermission("modify", "Modify permission", "m");
		$module->addPermission("delete", "Delete permission", "d");

		// Add menu(s)
		$module->addTopMenu($module->config['top_menu_name'], "MyFirstMenu", "/myfirstmodule/index.php?test=1");
		$module->addLeftMenu($module->config['top_menu_name'], "myleftmenu", "MyLeftMenu", "/myfirstmodule/index.php?test=2");
		$module->addLeftSubMenu($module->config['top_menu_name'], "myleftmenu", "mysubleftmenu", "MySubLeftMenu", "/myfirstmodule/index.php?test=3");
	}
}
```

You can find more examples including some test modules in test folder.
