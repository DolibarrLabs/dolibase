### Dolibase Framework
------

#### 3.0.3 - APR19

*   NEW: bump version.

---

#### 3.0.2 - FEB19

*   NEW: better skin for documentation.
*   NEW: report bug section updated in AboutPage template.
*   NEW: `getExtraFields` function added to CustomObject class.
*   FIX: translation issue on crabe document model.

---

#### 3.0.1 - FEB19

*   NEW: `import_export` class added to lite version.
*   NEW: module & package builder improved.

---

#### 3.0.0 - FEB19

*   NEW: package builder.
*   NEW: translations tab added to module builder.
*   NEW: `get_module_path` global function added.

---

#### 2.9.8 - FEB19

*   NEW: option to use a [lite](https://github.com/AXeL-dev/dolibase/blob/master/lite.md) version of dolibase when creating a module using the module builder.
*   FIX: builder issues [#1](https://github.com/AXeL-dev/dolibase/issues/1).
*   FIX: missing slash on module `loadTables` function.
*   FIX: widget link align issue on dolibarr 9.

---

#### 2.9.7 - JAN19

*   NEW: `ValidUrl` validation rule added.
*   NEW: `Experimental` badge added to changelog page.
*   FIX: ensure backward compatibility + other minor fixes.

---

#### 2.9.6 - JAN19

*   NEW: useless global var `$dolibase_config['langs']['path']` removed/replaced with `$dolibase_config['main']['path']`.
*   NEW: `addConfirmButton` function added to SetupPage class.
*   FIX: minor issues.

---

#### 2.9.5 - JAN19

*   NEW: Chart class.
*   NEW: add support for note field in changelog template.
*   NEW: `addMultiSelectListOption` function added to SetupPage class.
*   NEW: `multiSelectListInput`, `fileInput` functions added to CustomForm class.
*   FIX: add missing $textobject var to ExtrafieldsPage class.

---

#### 2.9.4 - JAN19

*   NEW: `GETPOSTDATETIME` global function added.
*   NEW: `datetimeInput` function added to CustomForm class.
*   NEW: customize some extrafields types in ListPage class.
*   FIX: missing concatenation operator for $body attribute in FormPage class.
*   FIX: disable using ajax by default for switch options in setup page (did not fit well with some modules).

---

#### 2.9.3 - JAN19

*   NEW: use ajax to avoid page reload for actions confirmation & switch options in setup page.
*   NEW: `string_to_array`, `array_to_string` & `js_enabled` global functions added.
*   NEW: allow function chaining of `core/pages` public functions.
*   FIX: minor issues.

---

#### 2.9.2 - DEC18

*   NEW: allow function chaining of `core/class` public functions.
*   FIX: in setup page, `set_*` action value should be set to 1 only if not submitted.
*   FIX: add DISTINCT to fetchAll function in CrudObject class.

---

#### 2.9.1 - DEC18

*   NEW: enhance table2csv plugin.
*   FIX: minor fixes.

---

#### 2.9.0 - DEC18

*   NEW: Report bug button added to about page template (under module informations).
*   NEW: Changelog page.
*   NEW: functions to enable/disable module(s) for external users added into DolibaseModule class.

---

#### 2.8.7 - DEC18

*   FIX: `set_*` action in Setup page.

---

#### 2.8.6 - DEC18

*   NEW: `tel`, `email` validation rules renamed & `validID` added.
*   NEW: test modules version is now set to the same as dolibase version.
*   NEW: widget added for books module.
*   FIX: PHP warning on Widget class.

---

#### 2.8.5 - DEC18

*   NEW: more validation rules added, to know: `minLength`, `maxLength`, `lessThan`, `greaterThan` (revamped).

---

#### 2.8.4 - DEC18

*   NEW: clone function added to CustomObject class.

---

#### 2.8.3 - DEC18

*   FIX: specimen pdf generation.

---

#### 2.8.2 - DEC18

*   NEW: enable function chaining on some public functions of Widget class.
*   NEW: set CrudObject class to abstract.
*   NEW: fetchAll & fetchWhere functions rewrited in CrudObject class.
*   NEW: fetchAll function calls replaced in builder & test modules.
*   NEW: addSelect function added to QueryBuilder class.
*   NEW: replace Logs class with QueryBuilder in log page.
*   FIX: missing $doc_lines attribute added into CustomObject class.
*   FIX: some HTML tweaks in CustomForm class.

---

#### 2.8.1 - DEC18

*   NEW: enable function chaining for DolibaseModule class public functions.
*   NEW: start replacing deprecated CrudObject class with QueryBuilder.
*   NEW: lastId function added to QueryBuilder class.
*   NEW: setFields function added to FormPage class.
*   NEW: dolibase_print_date global function added.
*   NEW: use $object->doc_lines instead of $object->lines for document build.
*   FIX: typo error on QueryBuilder class.

---

#### 2.8.0 - DEC18

*   NEW: QueryBuilder class.
*   FIX: add "?mainmenu=home" in setup page redirect urls.
*   FIX: CSS tweaks.

---

#### 2.7.1 - DEC18

*   NEW: isUnderConstruction function renamed to underConstruction + notFound, addCssfiles & addJsfiles functions added into page class.
*   NEW: setupNotAvailbale function added into Setup page.
*   FIX: translate $message string in dolibase_flash_message global function.
*   FIX: issue when id does not exist in Card page.
*   FIX: some HTML tweaks & corrections.

---

#### 2.7.0 - NOV18

*   NEW: date select form added to calendar page navigation bar.
*   NEW: introduce showTemplate, getTemplatePath, isUnderConstruction functions into page class.
*   NEW: uncheck use custom class name option by default in module & widget builder.
*   FIX: remove deprecated `dolibase/autoload.php`.
*   FIX: other minor fixes & optimisations.

---

#### 2.6.5 - NOV18

*   NEW: possibility to enable triggers from module config file.

---

#### 2.6.4 - NOV18

*   NEW: Arabic translation.
*   FIX: builder templates.

---

#### 2.6.3 - NOV18

*   NEW: mailing library.
*   NEW: dolibase_error() & dolibase_flash_message() global functions added.

---

#### 2.6.2 - NOV18

*   FIX: missing $subject var in azur pdf model.
*   FIX: code cleaned.

---

#### 2.6.1 - NOV18

*   NEW: extra folder renamed to vendor.
*   FIX: comments indentation.

---

#### 2.6.0 - NOV18

*   NEW: `autoload.php` added to module root directory (this little guy will now be responsible of loading the module configuration, dolibase requirements, etc..).
*   NEW: `dolibase/autoload.php` renamed to `dolibase/main.php` (to avoid confusion with module autoload file & because it makes more sense i guess).
*   FIX: keep `dolibase/autoload.php` for backward compatibility (but it's deprecated anyway & will not be used anymore on new modules).
*   NEW: module builder updated (to fit changes above).
*   FIX: empty option added to the type select in extrafields page.

---

#### 2.5.1 - NOV18

*   NEW: better assets handling in page class.
*   FIX: use DISTINCT in fetchAll query to avoid double results.

---

#### 2.5.0 - OCT18

*   NEW: improve builder.
*   NEW: add some validation rules to Field class.
*   NEW: CRON support.
*   NEW: add $date_fields array to CrudObject class.

---

#### 2.4.3 - OCT18

*   NEW: add documentation.
*   NEW: add statistics page into page builder.
*   FIX: some errors related to documentation.

---

#### 2.4.2 - OCT18

*   NEW: support for custom numbering & document models.
*   NEW: numbering & document model builder.
*   FIX: use get_modulepart function to get modulepart value to avoid confusion with get_rights_class function.

---

#### 2.4.1 - OCT18

*   NEW: page builder.
*   NEW: model builder.
*   NEW: translation builder.

---

#### 2.4.0 - OCT18

*   NEW: module builder.
*   NEW: widget builder.

---

#### 2.3.8 - SEP18

*   NEW: possibility to add tooltip details when calling getNomUrl function in custom_object class.
*   NEW: add a function to enable mail delivery receipt in card page.
*   NEW: allow setting custom mail substitutions in card page.
*   NEW: add enableHooks, addCssFiles, addJsFiles functions to module class.

---

#### 2.3.7 - SEP18

*   NEW: enhance compare_version global function.
*   FIX: missing lib inclusion in setup page.
*   NEW: allow file upload in setup page.

---

#### 2.3.6 - SEP18

*   NEW: upgrade PHP minimum version to 5.3.
*   FIX: greaterThanZero validation rule.

---

#### 2.3.5 - SEP18

*   NEW: enhance README.
*   NEW: change the way mail subject & template were defined in card page.

---

#### 2.3.4 - SEP18

*   NEW: rename textArea function to textEditor in custom form class.
*   NEW: enhance crud object class with new custom functions.
*   FIX: minor issues.

---

#### 2.3.3 - SEP18

*   NEW: update logs & books test modules.
*   NEW: enhance crud object class.
*   FIX: addons error when enabling a module.

---

#### 2.3.2 - SEP18

*   NEW: possibility to disable check for updates for all dolibase modules.
*   NEW: optimise stats class.
*   FIX: some missing array declaration in module class.

---

#### 2.3.1 - SEP18

*   NEW: function to enable triggers in module class.
*   FIX: code optimisation.

---

#### 2.3.0 - SEP18

*   NEW: enhance Log Page.
*   NEW: Calendar Page.
*   NEW: Dolibase Logs test module.
*   FIX: date issue on logs class.
*   FIX: translation issue on List page.
*   FIX: GETPOSTDATE function return a blank value when it shouldn't.

---

#### 2.2.5 - SEP18

*   FIX: context issue on List Page.
*   FIX: fetchAll lines array was not initialised when request result is empty.
*   FIX: allow setting sort field & order when retrieving a dictionary values.

---

#### 2.2.4 - SEP18

*   FIX: remove some useless parts of code.
*   FIX: rename some vars in Setup Page.
*   FIX: review ExtraFields Page code.

---

#### 2.2.3 - SEP18

*   NEW: multi-numbering models configuration.
*   NEW: allow using class custom picto & url in getNomUrl function.
*   NEW: add checkList function to CustomForm class.
*   FIX: translate dict values in get_list function.
*   NEW: add object_element field to dolibase_logs table.
*   NEW: improve compatibility with submodules.
*   FIX: code optimisation.

---

#### 2.2.2 - AUG18

*   FIX: add langs path to config, to avoid confusion or future issues with main path.

---

#### 2.2.1 - AUG18

*   FIX: optimise DocModel class.
*   FIX: improve card banner.
*   NEW: add getImage function to CustomObject class.
*   NEW: update books module to version 1.6.0 & fix menu icon issue on dolibarr 8.

---

#### 2.2.0 - AUG18

*   FIX: remove all dolibase constants & replace them with global vars.
*   FIX: merge global vars into one array for better code visibility.

---

#### 2.1.0 - AUG18

*   NEW: separate debug bar from dolibase & use it as a standalone module.
*   FIX: some minor syntax errors.

---

#### 2.0.2 - AUG18

*   FIX: update config collector.

---

#### 2.0.1 - AUG18

*   NEW: Add $dolibase_tables var.

---

#### 2.0.0 - AUG18

*   NEW: Debug bar.
*   FIX: some dolibarr errors/warnings.
*   FIX: issues with components include.
*   FIX: database errors on pdf generation.

---

#### 1.8.3 - AUG18

*   FIX: hide file_get_contents function warnings when checking for updates.
*   FIX: fiche end issue on card page.

---

#### 1.8.2 - AUG18

*   NEW: Spanish/Italiano & Deutsch translations.
*   FIX: autofill receiver(s) in mail form.
*   FIX: module updates title have been changed.

---

#### 1.8.1 - AUG18

*   FIX: issues related to version 1.8.0.

---

#### 1.8.0 - AUG18

*   FIX: DOLIBASE_PATH constant definition.
*   NEW: add DOLIBASE_LANGS_ROOT constant to load custom language files easily.
*   FIX: allow adding custom subject & template to mail form on card page.
*   NEW: add Polish & English GB translations.
*   FIX: update test modules to fit changes made on language files loading.
*   FIX: about page text position issue on Dolibarr 8.

---

#### 1.7.9 - AUG18

*   NEW: add a feature to check modules updates.
*   FIX: rename dict table.

---

#### 1.7.8 - AUG18

*   FIX: default document selection.

---

#### 1.7.7 - AUG18

*   NEW: add crabe document model.

---

#### 1.7.6 - AUG18

*   FIX: update related objects to fit changes on product returns module.

---

#### 1.7.5 - AUG18

*   FIX: document preview error on setup page.

---

#### 1.7.4 - AUG18

*   FIX: major bugs fixes related to new features.

---

#### 1.7.3 - AUG18

*   NEW: add Document page.
*   NEW: update Books module to version 1.5.0.

---

#### 1.7.2 - AUG18

*   NEW: add email templates support (only with $type == 'all' for the moment).
*   FIX: extrafields issues on dolibarr 5.0.4.

---

#### 1.7.1 - AUG18

*   NEW: add extra-fields support.
*   NEW: update Books module to version 1.4.0.

---

#### 1.7.0 - AUG18

*   NEW: add email sending support.
*   NEW: add PDF/Document generation support.
*   NEW: update Books module to version 1.3.0.
*   FIX: some typo errors.

---

#### 1.6.5 - AUG18

*   FIX: bottom border removed from statistics page.

---

#### 1.6.4 - JUL18

*   NEW: add an option to save card as CSV also.

---

#### 1.6.3 - JUL18

*   NEW: add an option to save card as PDF.
*   NEW: update Books module.

---

#### 1.6.2 - JUL18

*   FIX: remove config migration for better performance.

---

#### 1.6.1 - MAY18

*   FIX: issues related to version 1.6.0.

---

#### 1.6.0 - MAY18

*   NEW: native module (test module).
*   NEW: migration script (to old config).
*   NEW: better dolibase config organization.

---

#### 1.5.2 - MAY18

*   FIX: better code optimisation for the last fix (in version 1.5.1).

---

#### 1.5.1 - MAY18

*   FIX: module loadTables() function.

---

#### 1.5.0 - MAY18

*   NEW: update Widget class the same way we've done for the Module class in version 1.4.0.

---

#### 1.4.2 - MAY18

*   FIX: better file(s) include way #KISS (Keep It Simple Stupid).

---

#### 1.4.1 - MAY18

*   NEW: add ImportExport class.
*   NEW: minor improvements.

---

#### 1.4.0 - MAY18

*   NEW: change the way Dolibarr environment was included.
*   NEW: major changes for Module class.

---

#### 1.3.1 - APR18

*   FIX: Dictionary get_list function.

---

#### 1.3.0 - APR18

*   NEW: Add Dictionary support.
*   NEW: Add Create/Update/Delete triggers.
*   NEW: Add expanded links feature.
*   FIX: Bug on modules page due to multi-inclusion of dolibase.

---

#### 1.2.0 - APR18

*   NEW: Add Dolibase logs.
*   FIX: dolibase_include_once function have been reviewed.
*   NEW: Add Statistics page.
*   UPDATE: Optimise HTML generation functions.
*   FIX: Minor bugs.

---

#### 1.1.0 - APR18

*   NEW: Add Numbering models.
*   NEW: Add Create/Card/Index/List pages.
*   FIX: Few bugs.

---

#### 1.0.0 - APR18

*   NEW: Add About page.
*   NEW: Add Setup page.
*   NEW: Add Page class.
*   NEW: Add Widget class.
*   NEW: Add const.php file.
*   NEW: Add autoload.php file.
*   NEW: Add lib/functions.php file.
*   NEW: Add Module class.

**_Notes:_**


---
