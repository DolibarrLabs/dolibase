# 🚀 Dolibase Lite

Lite is a micro-version of dolibase with a very small size & few components, it includes only the basic requirements for your module(s) wich means that some dolibase features may be disabled or may not work until you add the required dolibase file(s).

The below graph shows the dolibase lite structure & files:

```bash
dolibase
├── core
│   ├── class
│   │   ├── module.php
│   │   ├── widget.php
│   │   ├── page.php
│   │   ├── field.php
│   │   ├── form_page.php
│   │   ├── custom_form.php
│   │   └── query_builder.php
│   ├── css
│   │   ├── page.css.php
│   │   ├── about.css.php
│   │   ├── setup.css.php
│   │   └── changelog.css.php
│   ├── js
│   │   └── form.js.php
│   ├── img
│   │   ├── not-found.png
│   │   ├── under-construction.png
│   │   └── update.png
│   ├── lib
│   │   └── functions.php
│   ├── pages
│   │   ├── about.php
│   │   ├── setup.php
│   │   └── changelog.php
│   └── tpl
│       ├── about_module.php
│       ├── module_changelog.php
│       ├── page_not_found.php
│       ├── page_under_construction.php
│       └── setup_not_available.php
├── langs
│   └── **_**
│       ├── module.lang
│       ├── page.lang
│       ├── validation.lang
│       ├── about_page.lang
│       ├── setup_page.lang
│       └── changelog_page.lang
├── config.php
└── main.php
```

## How to get it?

You will find an option at the bottom of the module builder to use/include Lite in your module(s).
