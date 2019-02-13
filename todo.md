### ToDo

* Module export/import options
* Hierarchical view page?
* Menu class with functions to add/remove/setPosition/enable/disable menu(s)?
* Hooks/Triggers class builder, think to associate all dolibarr hooks with their functions to simplify hooks implementation?
* Merge $page->checkFields() & checkExtraFields() functions?
* Find a way to display extrafields on PDFs? (think about submodules case too)
* Add support for dolibarr max version in module config?
* CalendarPage: show only the 3 first events & hide the others by default.
* CalendarPage: add support for per user view.
* StepByStep/StepToStep Page class (as in native export/import module).
* Builder: add settings.json file to save last created module number & generate the next module number based on the last saved number, or maybe use the ASCII code of the 4 first letters of the module name?

## other suggestions

* Merge deprecated CrudObject class into CustomObject?
* Use spaces instead of tabulations to follow PSR-2?
* New PDF models?
* Introduce namespaces to dolibase?
* Use composer to manage dependencies?
