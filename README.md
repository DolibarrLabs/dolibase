# Dolibase

[![Stable Version](https://img.shields.io/badge/stable-v2.3.3-brightgreen.svg)](https://github.com/AXeL-dev/dolibase/releases/tag/v2.3.3)
[![PHP Min](https://img.shields.io/badge/PHP-%3E%3D%205.0-blue.svg)](https://github.com/php)
[![Dolibarr Min](https://img.shields.io/badge/Dolibarr-%3E%3D%203.8.x-orange.svg)](https://github.com/Dolibarr/dolibarr)

Dolibase is a framework that make coding [Dolibarr](https://github.com/Dolibarr/dolibarr) modules more faster and easier, keeping your code well organised and making it easy to read & maintain.

Example of a new module class:

```php
class modMyFirstModule extends DolibaseModule
{
	public function loadSettings()
	{
		// Add constant(s)
		$this->addConstant("MY_FIRST_MODULE_CONST", "test");
		$this->addConstant("MY_FIRST_MODULE_SECOND_CONST", "test2");

		// Add widgets
		$this->addWidget("mybox.php");

		// Add CSS or even JS files
		$this->addCssFile("mycss.css.php");

		// Set permissions
		$this->addPermission("create", "Create permission", "c");
		$this->addPermission("modify", "Modify permission", "m");
		$this->addPermission("delete", "Delete permission", "d");

		// Add menu(s)
		$this->addTopMenu($this->config['other']['top_menu_name'], "MyFirstMenu", "/myfirstmodule/index.php?test=1");
		$this->addLeftMenu($this->config['other']['top_menu_name'], "myleftmenu", "MyLeftMenu", "/myfirstmodule/index.php?test=2");
		$this->addLeftSubMenu($this->config['other']['top_menu_name'], "myleftmenu", "mysubleftmenu", "MySubLeftMenu", "/myfirstmodule/index.php?test=3");
	}
}
```

You can find more examples including some test modules in test folder.
