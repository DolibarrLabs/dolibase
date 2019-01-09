
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span>[Global Namespace]                    </div>                    <div class="bd">                                <ul>                <li data-name="class:AboutPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="AboutPage.html">AboutPage</a>                    </div>                </li>                            <li data-name="class:CalendarPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CalendarPage.html">CalendarPage</a>                    </div>                </li>                            <li data-name="class:CardPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CardPage.html">CardPage</a>                    </div>                </li>                            <li data-name="class:ChangelogPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="ChangelogPage.html">ChangelogPage</a>                    </div>                </li>                            <li data-name="class:Chart" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Chart.html">Chart</a>                    </div>                </li>                            <li data-name="class:CreatePage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CreatePage.html">CreatePage</a>                    </div>                </li>                            <li data-name="class:CrudObject" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CrudObject.html">CrudObject</a>                    </div>                </li>                            <li data-name="class:CustomForm" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CustomForm.html">CustomForm</a>                    </div>                </li>                            <li data-name="class:CustomObject" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CustomObject.html">CustomObject</a>                    </div>                </li>                            <li data-name="class:CustomStats" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="CustomStats.html">CustomStats</a>                    </div>                </li>                            <li data-name="class:Dictionary" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Dictionary.html">Dictionary</a>                    </div>                </li>                            <li data-name="class:DocModel" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="DocModel.html">DocModel</a>                    </div>                </li>                            <li data-name="class:DocumentPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="DocumentPage.html">DocumentPage</a>                    </div>                </li>                            <li data-name="class:DolibaseModule" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="DolibaseModule.html">DolibaseModule</a>                    </div>                </li>                            <li data-name="class:ExtraFieldsPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="ExtraFieldsPage.html">ExtraFieldsPage</a>                    </div>                </li>                            <li data-name="class:Field" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Field.html">Field</a>                    </div>                </li>                            <li data-name="class:FormPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="FormPage.html">FormPage</a>                    </div>                </li>                            <li data-name="class:ImportExport" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="ImportExport.html">ImportExport</a>                    </div>                </li>                            <li data-name="class:IndexPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="IndexPage.html">IndexPage</a>                    </div>                </li>                            <li data-name="class:ListPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="ListPage.html">ListPage</a>                    </div>                </li>                            <li data-name="class:LogPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="LogPage.html">LogPage</a>                    </div>                </li>                            <li data-name="class:Logs" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Logs.html">Logs</a>                    </div>                </li>                            <li data-name="class:MailObject" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="MailObject.html">MailObject</a>                    </div>                </li>                            <li data-name="class:NumModel" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="NumModel.html">NumModel</a>                    </div>                </li>                            <li data-name="class:NumModelMarbre" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="NumModelMarbre.html">NumModelMarbre</a>                    </div>                </li>                            <li data-name="class:NumModelSaphir" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="NumModelSaphir.html">NumModelSaphir</a>                    </div>                </li>                            <li data-name="class:Page" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Page.html">Page</a>                    </div>                </li>                            <li data-name="class:QueryBuilder" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="QueryBuilder.html">QueryBuilder</a>                    </div>                </li>                            <li data-name="class:SetupPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="SetupPage.html">SetupPage</a>                    </div>                </li>                            <li data-name="class:StatsPage" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="StatsPage.html">StatsPage</a>                    </div>                </li>                            <li data-name="class:Widget" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Widget.html">Widget</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span>pdf                    </div>                    <div class="bd">                                <ul>                <li data-name="class:pdf_azur" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="pdf_azur.html">azur</a>                    </div>                </li>                            <li data-name="class:pdf_crabe" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="pdf_crabe.html">crabe</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": ".html", "name": "", "doc": "Namespace "},
            
            {"type": "Class",  "link": "AboutPage.html", "name": "AboutPage", "doc": "&quot;AboutPage class&quot;"},
                                                        {"type": "Method", "fromName": "AboutPage", "fromLink": "AboutPage.html", "link": "AboutPage.html#method___construct", "name": "AboutPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "AboutPage", "fromLink": "AboutPage.html", "link": "AboutPage.html#method_generate", "name": "AboutPage::generate", "doc": "&quot;Generate page body&quot;"},
                    {"type": "Method", "fromName": "AboutPage", "fromLink": "AboutPage.html", "link": "AboutPage.html#method_generateTabs", "name": "AboutPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "AboutPage", "fromLink": "AboutPage.html", "link": "AboutPage.html#method_printModuleInformations", "name": "AboutPage::printModuleInformations", "doc": "&quot;Print module informations&quot;"},
            
            {"type": "Class",  "link": "CalendarPage.html", "name": "CalendarPage", "doc": "&quot;CalendarPage class&quot;"},
                                                        {"type": "Method", "fromName": "CalendarPage", "fromLink": "CalendarPage.html", "link": "CalendarPage.html#method___construct", "name": "CalendarPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CalendarPage", "fromLink": "CalendarPage.html", "link": "CalendarPage.html#method_generateTabs", "name": "CalendarPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "CalendarPage", "fromLink": "CalendarPage.html", "link": "CalendarPage.html#method_addFilterForm", "name": "CalendarPage::addFilterForm", "doc": "&quot;Add a filter form&quot;"},
                    {"type": "Method", "fromName": "CalendarPage", "fromLink": "CalendarPage.html", "link": "CalendarPage.html#method_showNavBar", "name": "CalendarPage::showNavBar", "doc": "&quot;Show navigation bar&quot;"},
                    {"type": "Method", "fromName": "CalendarPage", "fromLink": "CalendarPage.html", "link": "CalendarPage.html#method_showEvents", "name": "CalendarPage::showEvents", "doc": "&quot;Show calendar events&quot;"},
                    {"type": "Method", "fromName": "CalendarPage", "fromLink": "CalendarPage.html", "link": "CalendarPage.html#method_printCalendar", "name": "CalendarPage::printCalendar", "doc": "&quot;Print a calendar&quot;"},
            
            {"type": "Class",  "link": "CardPage.html", "name": "CardPage", "doc": "&quot;CardPage class&quot;"},
                                                        {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method___construct", "name": "CardPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_canEdit", "name": "CardPage::canEdit", "doc": "&quot;Return if the current user can edit the page or not&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_canDelete", "name": "CardPage::canDelete", "doc": "&quot;Return if the current user can delete the object or not&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_setMailSubject", "name": "CardPage::setMailSubject", "doc": "&quot;Set mail subject&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_setMailTemplate", "name": "CardPage::setMailTemplate", "doc": "&quot;Set mail template&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_enableMailDeliveryReceipt", "name": "CardPage::enableMailDeliveryReceipt", "doc": "&quot;Enables mail delivery receipt&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_setMailSubstitutions", "name": "CardPage::setMailSubstitutions", "doc": "&quot;Set mail substitutions&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_openButtonsDiv", "name": "CardPage::openButtonsDiv", "doc": "&quot;Open buttons div (if not already opened)&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_closeButtonsDiv", "name": "CardPage::closeButtonsDiv", "doc": "&quot;Close buttons div (if opened)&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_addButton", "name": "CardPage::addButton", "doc": "&quot;Add a button to the page&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_addConfirmButton", "name": "CardPage::addConfirmButton", "doc": "&quot;Add a confirmation button to the page&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_addListButton", "name": "CardPage::addListButton", "doc": "&quot;Add a list button to the page&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_addSaveAsButton", "name": "CardPage::addSaveAsButton", "doc": "&quot;Add save as button to the page&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_showField", "name": "CardPage::showField", "doc": "&quot;Show a table field&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_showRefField", "name": "CardPage::showRefField", "doc": "&quot;Show reference\/Ref. field&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_showExtraFields", "name": "CardPage::showExtraFields", "doc": "&quot;Show extra fields&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_updateExtraFields", "name": "CardPage::updateExtraFields", "doc": "&quot;Update extra fields&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_showBanner", "name": "CardPage::showBanner", "doc": "&quot;Show banner&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editField", "name": "CardPage::editField", "doc": "&quot;Edit a field&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editTextField", "name": "CardPage::editTextField", "doc": "&quot;Add a table field with a text input&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editTextAreaField", "name": "CardPage::editTextAreaField", "doc": "&quot;Add a table field with a text area&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editNumberField", "name": "CardPage::editNumberField", "doc": "&quot;Add a table field with a number input&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editDateField", "name": "CardPage::editDateField", "doc": "&quot;Add a table field with a date picker&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editListField", "name": "CardPage::editListField", "doc": "&quot;Add a table field with a list&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editRadioListField", "name": "CardPage::editRadioListField", "doc": "&quot;Add a table field with a radio input(s)&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editCheckListField", "name": "CardPage::editCheckListField", "doc": "&quot;Add a table field with a checkbox input(s)&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_editColorField", "name": "CardPage::editColorField", "doc": "&quot;Add a table field with a color picker&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_printRelatedObjects", "name": "CardPage::printRelatedObjects", "doc": "&quot;Print related objects block&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_printDocuments", "name": "CardPage::printDocuments", "doc": "&quot;Print documents block&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_printMailForm", "name": "CardPage::printMailForm", "doc": "&quot;Print mail form&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_begin", "name": "CardPage::begin", "doc": "&quot;Generate page beginning&quot;"},
                    {"type": "Method", "fromName": "CardPage", "fromLink": "CardPage.html", "link": "CardPage.html#method_end", "name": "CardPage::end", "doc": "&quot;Generate page end&quot;"},
            
            {"type": "Class",  "link": "ChangelogPage.html", "name": "ChangelogPage", "doc": "&quot;ChangelogPage class&quot;"},
                                                        {"type": "Method", "fromName": "ChangelogPage", "fromLink": "ChangelogPage.html", "link": "ChangelogPage.html#method___construct", "name": "ChangelogPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "ChangelogPage", "fromLink": "ChangelogPage.html", "link": "ChangelogPage.html#method_generate", "name": "ChangelogPage::generate", "doc": "&quot;Generate page body&quot;"},
                    {"type": "Method", "fromName": "ChangelogPage", "fromLink": "ChangelogPage.html", "link": "ChangelogPage.html#method_generateTabs", "name": "ChangelogPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "ChangelogPage", "fromLink": "ChangelogPage.html", "link": "ChangelogPage.html#method_printChangelog", "name": "ChangelogPage::printChangelog", "doc": "&quot;Print module changelog&quot;"},
            
            {"type": "Class",  "link": "Chart.html", "name": "Chart", "doc": "&quot;Chart class&quot;"},
                                                        {"type": "Method", "fromName": "Chart", "fromLink": "Chart.html", "link": "Chart.html#method_generate", "name": "Chart::generate", "doc": "&quot;Generate chart&quot;"},
                    {"type": "Method", "fromName": "Chart", "fromLink": "Chart.html", "link": "Chart.html#method_display", "name": "Chart::display", "doc": "&quot;Display chart (shortcut for draw &amp;amp; show)&quot;"},
            
            {"type": "Class",  "link": "CreatePage.html", "name": "CreatePage", "doc": "&quot;CreatePage class&quot;"},
                                                        {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method___construct", "name": "CreatePage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_checkExtraFields", "name": "CreatePage::checkExtraFields", "doc": "&quot;Check page extrafields&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addField", "name": "CreatePage::addField", "doc": "&quot;Add a table field&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addTextField", "name": "CreatePage::addTextField", "doc": "&quot;Add a table field with a text input&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addTextAreaField", "name": "CreatePage::addTextAreaField", "doc": "&quot;Add a table field with a text area&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addNumberField", "name": "CreatePage::addNumberField", "doc": "&quot;Add a table field with a number input&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addDateField", "name": "CreatePage::addDateField", "doc": "&quot;Add a table field with a date picker&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addListField", "name": "CreatePage::addListField", "doc": "&quot;Add a table field with a list&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addRadioListField", "name": "CreatePage::addRadioListField", "doc": "&quot;Add a table field with a radio input(s)&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addCheckListField", "name": "CreatePage::addCheckListField", "doc": "&quot;Add a table field with a checkbox input(s)&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addColorField", "name": "CreatePage::addColorField", "doc": "&quot;Add a table field with a color picker&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_addExtraFields", "name": "CreatePage::addExtraFields", "doc": "&quot;Add extra fields&quot;"},
                    {"type": "Method", "fromName": "CreatePage", "fromLink": "CreatePage.html", "link": "CreatePage.html#method_generateFormButtons", "name": "CreatePage::generateFormButtons", "doc": "&quot;Generate form buttons&quot;"},
            
            {"type": "Class",  "link": "CrudObject.html", "name": "CrudObject", "doc": "&quot;CrudObject class (Create\/Read\/Update\/Delete)&quot;"},
                                                        {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method___construct", "name": "CrudObject::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_setTableName", "name": "CrudObject::setTableName", "doc": "&quot;Set table name&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_create", "name": "CrudObject::create", "doc": "&quot;Create object into database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_fetch", "name": "CrudObject::fetch", "doc": "&quot;Load object in memory from database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_fetchWhere", "name": "CrudObject::fetchWhere", "doc": "&quot;Load object in memory from database (wrapper for fetchAll function)&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_fetchAll", "name": "CrudObject::fetchAll", "doc": "&quot;Load all object entries in memory from database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_update", "name": "CrudObject::update", "doc": "&quot;Update object into database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_updateWhere", "name": "CrudObject::updateWhere", "doc": "&quot;Update row(s) into database (wrapper for updateAll function)&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_updateAll", "name": "CrudObject::updateAll", "doc": "&quot;Update all object rows into database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_delete", "name": "CrudObject::delete", "doc": "&quot;Delete object in database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_deleteWhere", "name": "CrudObject::deleteWhere", "doc": "&quot;Delete row(s) in database (wrapper for deleteAll function)&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_deleteAll", "name": "CrudObject::deleteAll", "doc": "&quot;Delete all object rows in database&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_deleteAllObjectLinked", "name": "CrudObject::deleteAllObjectLinked", "doc": "&quot;Delete all links between an object $this&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_escape", "name": "CrudObject::escape", "doc": "&quot;Escape field value&quot;"},
                    {"type": "Method", "fromName": "CrudObject", "fromLink": "CrudObject.html", "link": "CrudObject.html#method_run_triggers", "name": "CrudObject::run_triggers", "doc": "&quot;Run Dolibarr triggers (from other modules)&quot;"},
            
            {"type": "Class",  "link": "CustomForm.html", "name": "CustomForm", "doc": "&quot;CustomForm class&quot;"},
                                                        {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method___construct", "name": "CustomForm::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_checkBox", "name": "CustomForm::checkBox", "doc": "&quot;Return a checkbox&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_textInput", "name": "CustomForm::textInput", "doc": "&quot;Return a text input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_textArea", "name": "CustomForm::textArea", "doc": "&quot;Return a text area&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_textEditor", "name": "CustomForm::textEditor", "doc": "&quot;Return a text area with editor (if WYSIWYG editor module is activated)&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_fileInput", "name": "CustomForm::fileInput", "doc": "&quot;Return a file input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_numberInput", "name": "CustomForm::numberInput", "doc": "&quot;Return a number input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_rangeInput", "name": "CustomForm::rangeInput", "doc": "&quot;Return a range input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_dateInput", "name": "CustomForm::dateInput", "doc": "&quot;Return a date input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_datetimeInput", "name": "CustomForm::datetimeInput", "doc": "&quot;Return a datetime input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_listInput", "name": "CustomForm::listInput", "doc": "&quot;Return a list&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_multiSelectListInput", "name": "CustomForm::multiSelectListInput", "doc": "&quot;Return a multi select list&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_radioList", "name": "CustomForm::radioList", "doc": "&quot;Return a radio list&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_checkList", "name": "CustomForm::checkList", "doc": "&quot;Return a check list&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_colorInput", "name": "CustomForm::colorInput", "doc": "&quot;Return a color input&quot;"},
                    {"type": "Method", "fromName": "CustomForm", "fromLink": "CustomForm.html", "link": "CustomForm.html#method_productList", "name": "CustomForm::productList", "doc": "&quot;Return products list&quot;"},
            
            {"type": "Class",  "link": "CustomObject.html", "name": "CustomObject", "doc": "&quot;CustomObject class&quot;"},
                                                        {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method___construct", "name": "CustomObject::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_clone", "name": "CustomObject::clone", "doc": "&quot;Clone an object&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_create", "name": "CustomObject::create", "doc": "&quot;Create object into database&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_update", "name": "CustomObject::update", "doc": "&quot;Update object into database&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_delete", "name": "CustomObject::delete", "doc": "&quot;Delete object in database&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_getNextNumRef", "name": "CustomObject::getNextNumRef", "doc": "&quot;Returns the reference to the following non used object depending on the active numbering model\ndefined into MODULE_RIGHTS_CLASS_ADDON&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_getNomUrl", "name": "CustomObject::getNomUrl", "doc": "&quot;Return clicable name (with picto eventually)&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_getLibStatut", "name": "CustomObject::getLibStatut", "doc": "&quot;Return label of status of object (draft, validated, .&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_getImage", "name": "CustomObject::getImage", "doc": "&quot;Get object image(s)&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_generateDocument", "name": "CustomObject::generateDocument", "doc": "&quot;Create a document onto disk according to template module.&quot;"},
                    {"type": "Method", "fromName": "CustomObject", "fromLink": "CustomObject.html", "link": "CustomObject.html#method_deleteDocument", "name": "CustomObject::deleteDocument", "doc": "&quot;Delete document from disk.&quot;"},
            
            {"type": "Class",  "link": "CustomStats.html", "name": "CustomStats", "doc": "&quot;CustomStats class&quot;"},
                                                        {"type": "Method", "fromName": "CustomStats", "fromLink": "CustomStats.html", "link": "CustomStats.html#method___construct", "name": "CustomStats::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "CustomStats", "fromLink": "CustomStats.html", "link": "CustomStats.html#method_getNbByMonth", "name": "CustomStats::getNbByMonth", "doc": "&quot;Return object number by month for a year&quot;"},
                    {"type": "Method", "fromName": "CustomStats", "fromLink": "CustomStats.html", "link": "CustomStats.html#method_getNbByYear", "name": "CustomStats::getNbByYear", "doc": "&quot;Return object number per year&quot;"},
                    {"type": "Method", "fromName": "CustomStats", "fromLink": "CustomStats.html", "link": "CustomStats.html#method_getAllByYear", "name": "CustomStats::getAllByYear", "doc": "&quot;Return nb, total and average&quot;"},
                    {"type": "Method", "fromName": "CustomStats", "fromLink": "CustomStats.html", "link": "CustomStats.html#method_getAmountByMonth", "name": "CustomStats::getAmountByMonth", "doc": "&quot;Return object amount by month for a year&quot;"},
                    {"type": "Method", "fromName": "CustomStats", "fromLink": "CustomStats.html", "link": "CustomStats.html#method_getAverageByMonth", "name": "CustomStats::getAverageByMonth", "doc": "&quot;Return object amount average by month for a year&quot;"},
            
            {"type": "Class",  "link": "Dictionary.html", "name": "Dictionary", "doc": "&quot;Dictionary class&quot;"},
                                                        {"type": "Method", "fromName": "Dictionary", "fromLink": "Dictionary.html", "link": "Dictionary.html#method_get_active", "name": "Dictionary::get_active", "doc": "&quot;Returns dictionary active lines list&quot;"},
                    {"type": "Method", "fromName": "Dictionary", "fromLink": "Dictionary.html", "link": "Dictionary.html#method_get_all", "name": "Dictionary::get_all", "doc": "&quot;Returns dictionary all lines list&quot;"},
            
            {"type": "Class",  "link": "DocModel.html", "name": "DocModel", "doc": "&quot;DocModel class&quot;"},
                                                        {"type": "Method", "fromName": "DocModel", "fromLink": "DocModel.html", "link": "DocModel.html#method_getModelsList", "name": "DocModel::getModelsList", "doc": "&quot;Return list of active generation models&quot;"},
            
            {"type": "Class",  "link": "DocumentPage.html", "name": "DocumentPage", "doc": "&quot;DocumentPage class&quot;"},
                                                        {"type": "Method", "fromName": "DocumentPage", "fromLink": "DocumentPage.html", "link": "DocumentPage.html#method___construct", "name": "DocumentPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "DocumentPage", "fromLink": "DocumentPage.html", "link": "DocumentPage.html#method_generateTabs", "name": "DocumentPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "DocumentPage", "fromLink": "DocumentPage.html", "link": "DocumentPage.html#method_showBanner", "name": "DocumentPage::showBanner", "doc": "&quot;Show banner&quot;"},
                    {"type": "Method", "fromName": "DocumentPage", "fromLink": "DocumentPage.html", "link": "DocumentPage.html#method_getTabTitle", "name": "DocumentPage::getTabTitle", "doc": "&quot;Return Tab title&quot;"},
                    {"type": "Method", "fromName": "DocumentPage", "fromLink": "DocumentPage.html", "link": "DocumentPage.html#method_begin", "name": "DocumentPage::begin", "doc": "&quot;Generate page beginning&quot;"},
                    {"type": "Method", "fromName": "DocumentPage", "fromLink": "DocumentPage.html", "link": "DocumentPage.html#method_printDocuments", "name": "DocumentPage::printDocuments", "doc": "&quot;Print documents\/linked files&quot;"},
            
            {"type": "Class",  "link": "DolibaseModule.html", "name": "DolibaseModule", "doc": "&quot;DolibaseModule class&quot;"},
                                                        {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method___construct", "name": "DolibaseModule::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_loadSettings", "name": "DolibaseModule::loadSettings", "doc": "&quot;Function called after module configuration.&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_checkUpdates", "name": "DolibaseModule::checkUpdates", "doc": "&quot;Function to check module updates.&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_init", "name": "DolibaseModule::init", "doc": "&quot;Function called when module is enabled.&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_enableModuleForExternalUsers", "name": "DolibaseModule::enableModuleForExternalUsers", "doc": "&quot;Enable module for external users&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_disableModuleForExternalUsers", "name": "DolibaseModule::disableModuleForExternalUsers", "doc": "&quot;Disable module for external users&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_setAddons", "name": "DolibaseModule::setAddons", "doc": "&quot;Set\/Activate addons required by module&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_loadTables", "name": "DolibaseModule::loadTables", "doc": "&quot;Create tables, keys and data required by module\nFiles llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys\nand create data commands must be stored in directory \/mymodule\/sql\/\nThis function is called by this-&gt;init&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_remove", "name": "DolibaseModule::remove", "doc": "&quot;Function called when module is disabled.&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addConstant", "name": "DolibaseModule::addConstant", "doc": "&quot;Add a constant&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addWidget", "name": "DolibaseModule::addWidget", "doc": "&quot;Add a widget&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addPermission", "name": "DolibaseModule::addPermission", "doc": "&quot;Add a permission&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addSubPermission", "name": "DolibaseModule::addSubPermission", "doc": "&quot;Add a sub permission&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_generatePermissionID", "name": "DolibaseModule::generatePermissionID", "doc": "&quot;Generate an ID for permissions&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addTopMenu", "name": "DolibaseModule::addTopMenu", "doc": "&quot;Add a top menu entry&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addLeftMenu", "name": "DolibaseModule::addLeftMenu", "doc": "&quot;Add a left menu entry&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addLeftSubMenu", "name": "DolibaseModule::addLeftSubMenu", "doc": "&quot;Add a left sub menu entry&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addMenu", "name": "DolibaseModule::addMenu", "doc": "&quot;Add a menu&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addCssFile", "name": "DolibaseModule::addCssFile", "doc": "&quot;Add a CSS file&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addCssFiles", "name": "DolibaseModule::addCssFiles", "doc": "&quot;Add an array of CSS files&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addJsFile", "name": "DolibaseModule::addJsFile", "doc": "&quot;Add a JS file&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addJsFiles", "name": "DolibaseModule::addJsFiles", "doc": "&quot;Add an array of JS files&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_enableHook", "name": "DolibaseModule::enableHook", "doc": "&quot;Enable a hook&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_enableHooks", "name": "DolibaseModule::enableHooks", "doc": "&quot;Enable an array of hooks&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_enableTriggers", "name": "DolibaseModule::enableTriggers", "doc": "&quot;Enable triggers&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addDictionary", "name": "DolibaseModule::addDictionary", "doc": "&quot;Add a dictionary&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addModulePart", "name": "DolibaseModule::addModulePart", "doc": "&quot;Add a module part&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_activateNumModel", "name": "DolibaseModule::activateNumModel", "doc": "&quot;Activate a numbering model&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_activateDocModel", "name": "DolibaseModule::activateDocModel", "doc": "&quot;Activate a document model&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addCronJob", "name": "DolibaseModule::addCronJob", "doc": "&quot;Add a cron job&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addCronCommand", "name": "DolibaseModule::addCronCommand", "doc": "&quot;Add a cron job using a command&quot;"},
                    {"type": "Method", "fromName": "DolibaseModule", "fromLink": "DolibaseModule.html", "link": "DolibaseModule.html#method_addCronMethod", "name": "DolibaseModule::addCronMethod", "doc": "&quot;Add a cron job using a method&quot;"},
            
            {"type": "Class",  "link": "ExtraFieldsPage.html", "name": "ExtraFieldsPage", "doc": "&quot;ExtraFieldsPage class&quot;"},
                                                        {"type": "Method", "fromName": "ExtraFieldsPage", "fromLink": "ExtraFieldsPage.html", "link": "ExtraFieldsPage.html#method___construct", "name": "ExtraFieldsPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "ExtraFieldsPage", "fromLink": "ExtraFieldsPage.html", "link": "ExtraFieldsPage.html#method_generate", "name": "ExtraFieldsPage::generate", "doc": "&quot;Generate page body&quot;"},
                    {"type": "Method", "fromName": "ExtraFieldsPage", "fromLink": "ExtraFieldsPage.html", "link": "ExtraFieldsPage.html#method_generateTabs", "name": "ExtraFieldsPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "ExtraFieldsPage", "fromLink": "ExtraFieldsPage.html", "link": "ExtraFieldsPage.html#method_loadDefaultActions", "name": "ExtraFieldsPage::loadDefaultActions", "doc": "&quot;Load default actions&quot;"},
                    {"type": "Method", "fromName": "ExtraFieldsPage", "fromLink": "ExtraFieldsPage.html", "link": "ExtraFieldsPage.html#method_printExtraFields", "name": "ExtraFieldsPage::printExtraFields", "doc": "&quot;Print extra fields table&quot;"},
            
            {"type": "Class",  "link": "Field.html", "name": "Field", "doc": "&quot;Field class&quot;"},
                                                        {"type": "Method", "fromName": "Field", "fromLink": "Field.html", "link": "Field.html#method___construct", "name": "Field::__construct", "doc": "&quot;Constructor&quot;"},
            
            {"type": "Class",  "link": "FormPage.html", "name": "FormPage", "doc": "&quot;FormPage class&quot;"},
                                                        {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method___construct", "name": "FormPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_setFields", "name": "FormPage::setFields", "doc": "&quot;Set page fields&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_checkFields", "name": "FormPage::checkFields", "doc": "&quot;Check page fields&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_checkField", "name": "FormPage::checkField", "doc": "&quot;Check specified field&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_getField", "name": "FormPage::getField", "doc": "&quot;Return specified field if found&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_askForConfirmation", "name": "FormPage::askForConfirmation", "doc": "&quot;Show a confirmation message&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_appendToBody", "name": "FormPage::appendToBody", "doc": "&quot;Append a content to page body&quot;"},
                    {"type": "Method", "fromName": "FormPage", "fromLink": "FormPage.html", "link": "FormPage.html#method_generate", "name": "FormPage::generate", "doc": "&quot;Generate page body&quot;"},
            
            {"type": "Class",  "link": "ImportExport.html", "name": "ImportExport", "doc": "&quot;ImportExport class&quot;"},
                                                        {"type": "Method", "fromName": "ImportExport", "fromLink": "ImportExport.html", "link": "ImportExport.html#method_addJsFiles", "name": "ImportExport::addJsFiles", "doc": "&quot;Add import\/export js files to the page&quot;"},
                    {"type": "Method", "fromName": "ImportExport", "fromLink": "ImportExport.html", "link": "ImportExport.html#method_addExportButton", "name": "ImportExport::addExportButton", "doc": "&quot;Add\/print export button&quot;"},
                    {"type": "Method", "fromName": "ImportExport", "fromLink": "ImportExport.html", "link": "ImportExport.html#method_addImportButton", "name": "ImportExport::addImportButton", "doc": "&quot;Add\/print import button&quot;"},
            
            {"type": "Class",  "link": "IndexPage.html", "name": "IndexPage", "doc": "&quot;IndexPage class&quot;"},
                                                        {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method___construct", "name": "IndexPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_begin", "name": "IndexPage::begin", "doc": "&quot;Generate page beginning&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_end", "name": "IndexPage::end", "doc": "&quot;Generate page end&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_openLeftSection", "name": "IndexPage::openLeftSection", "doc": "&quot;Opens a left section&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_closeLeftSection", "name": "IndexPage::closeLeftSection", "doc": "&quot;Close a left section&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_openRightSection", "name": "IndexPage::openRightSection", "doc": "&quot;Opens a right section&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_closeRightSection", "name": "IndexPage::closeRightSection", "doc": "&quot;Close a right section&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_addSearchForm", "name": "IndexPage::addSearchForm", "doc": "&quot;Add a search form&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_addStatsGraph", "name": "IndexPage::addStatsGraph", "doc": "&quot;Add a statistics graph&quot;"},
                    {"type": "Method", "fromName": "IndexPage", "fromLink": "IndexPage.html", "link": "IndexPage.html#method_addStatsGraphFromData", "name": "IndexPage::addStatsGraphFromData", "doc": "&quot;Add a statistics graph from predefined data&quot;"},
            
            {"type": "Class",  "link": "ListPage.html", "name": "ListPage", "doc": "&quot;ListPage class&quot;"},
                                                        {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method___construct", "name": "ListPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_loadDefaultActions", "name": "ListPage::loadDefaultActions", "doc": "&quot;Load default actions&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_openList", "name": "ListPage::openList", "doc": "&quot;Open list \/ print list head&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_addColumn", "name": "ListPage::addColumn", "doc": "&quot;Add a table column&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_addExtraFields", "name": "ListPage::addExtraFields", "doc": "&quot;Add extrafields columns&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_fetchExtraFields", "name": "ListPage::fetchExtraFields", "doc": "&quot;Fetch extrafields&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_addButtons", "name": "ListPage::addButtons", "doc": "&quot;Add buttons to the list&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_closeList", "name": "ListPage::closeList", "doc": "&quot;Close list&quot;"},
                    {"type": "Method", "fromName": "ListPage", "fromLink": "ListPage.html", "link": "ListPage.html#method_closeRow", "name": "ListPage::closeRow", "doc": "&quot;Close table row&quot;"},
            
            {"type": "Class",  "link": "LogPage.html", "name": "LogPage", "doc": "&quot;LogPage class&quot;"},
                                                        {"type": "Method", "fromName": "LogPage", "fromLink": "LogPage.html", "link": "LogPage.html#method___construct", "name": "LogPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "LogPage", "fromLink": "LogPage.html", "link": "LogPage.html#method_loadDefaultActions", "name": "LogPage::loadDefaultActions", "doc": "&quot;Load default actions&quot;"},
                    {"type": "Method", "fromName": "LogPage", "fromLink": "LogPage.html", "link": "LogPage.html#method_generateTabs", "name": "LogPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "LogPage", "fromLink": "LogPage.html", "link": "LogPage.html#method_showBanner", "name": "LogPage::showBanner", "doc": "&quot;Show banner&quot;"},
                    {"type": "Method", "fromName": "LogPage", "fromLink": "LogPage.html", "link": "LogPage.html#method_printLogs", "name": "LogPage::printLogs", "doc": "&quot;Print logs&quot;"},
            
            {"type": "Class",  "link": "Logs.html", "name": "Logs", "doc": "&quot;Logs class&quot;"},
                                                        {"type": "Method", "fromName": "Logs", "fromLink": "Logs.html", "link": "Logs.html#method___construct", "name": "Logs::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "Logs", "fromLink": "Logs.html", "link": "Logs.html#method_add", "name": "Logs::add", "doc": "&quot;Add log into database&quot;"},
            
            {"type": "Class",  "link": "MailObject.html", "name": "MailObject", "doc": "&quot;MailObject class&quot;"},
                                                        {"type": "Method", "fromName": "MailObject", "fromLink": "MailObject.html", "link": "MailObject.html#method___construct", "name": "MailObject::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "MailObject", "fromLink": "MailObject.html", "link": "MailObject.html#method_fetch", "name": "MailObject::fetch", "doc": "&quot;Load object in memory from database&quot;"},
            
            {"type": "Class",  "link": "NumModel.html", "name": "NumModel", "doc": "&quot;NumModel class&quot;"},
                                                        {"type": "Method", "fromName": "NumModel", "fromLink": "NumModel.html", "link": "NumModel.html#method_isEnabled", "name": "NumModel::isEnabled", "doc": "&quot;Return if a model can be used or not&quot;"},
                    {"type": "Method", "fromName": "NumModel", "fromLink": "NumModel.html", "link": "NumModel.html#method_info", "name": "NumModel::info", "doc": "&quot;Return the default description of the numbering model&quot;"},
                    {"type": "Method", "fromName": "NumModel", "fromLink": "NumModel.html", "link": "NumModel.html#method_getExample", "name": "NumModel::getExample", "doc": "&quot;Return an example of numbering&quot;"},
                    {"type": "Method", "fromName": "NumModel", "fromLink": "NumModel.html", "link": "NumModel.html#method_canBeActivated", "name": "NumModel::canBeActivated", "doc": "&quot;Check if the numbers already existing in the database doesn&#039;t have conflicts with this numbering model&quot;"},
                    {"type": "Method", "fromName": "NumModel", "fromLink": "NumModel.html", "link": "NumModel.html#method_getNextValue", "name": "NumModel::getNextValue", "doc": "&quot;Return next numbering value&quot;"},
                    {"type": "Method", "fromName": "NumModel", "fromLink": "NumModel.html", "link": "NumModel.html#method_getVersion", "name": "NumModel::getVersion", "doc": "&quot;Return numbering model version&quot;"},
            
            {"type": "Class",  "link": "NumModelMarbre.html", "name": "NumModelMarbre", "doc": "&quot;NumModelMarbre class&quot;"},
                                                        {"type": "Method", "fromName": "NumModelMarbre", "fromLink": "NumModelMarbre.html", "link": "NumModelMarbre.html#method___construct", "name": "NumModelMarbre::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "NumModelMarbre", "fromLink": "NumModelMarbre.html", "link": "NumModelMarbre.html#method_info", "name": "NumModelMarbre::info", "doc": "&quot;Return description of numbering model&quot;"},
                    {"type": "Method", "fromName": "NumModelMarbre", "fromLink": "NumModelMarbre.html", "link": "NumModelMarbre.html#method_getExample", "name": "NumModelMarbre::getExample", "doc": "&quot;Return an example of numbering&quot;"},
                    {"type": "Method", "fromName": "NumModelMarbre", "fromLink": "NumModelMarbre.html", "link": "NumModelMarbre.html#method_canBeActivated", "name": "NumModelMarbre::canBeActivated", "doc": "&quot;Check if the numbers already existing in the database doesn&#039;t have conflicts with this numbering model&quot;"},
                    {"type": "Method", "fromName": "NumModelMarbre", "fromLink": "NumModelMarbre.html", "link": "NumModelMarbre.html#method_getNextValue", "name": "NumModelMarbre::getNextValue", "doc": "&quot;Return next free value&quot;"},
            
            {"type": "Class",  "link": "NumModelSaphir.html", "name": "NumModelSaphir", "doc": "&quot;NumModelSaphir class&quot;"},
                                                        {"type": "Method", "fromName": "NumModelSaphir", "fromLink": "NumModelSaphir.html", "link": "NumModelSaphir.html#method___construct", "name": "NumModelSaphir::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "NumModelSaphir", "fromLink": "NumModelSaphir.html", "link": "NumModelSaphir.html#method_info", "name": "NumModelSaphir::info", "doc": "&quot;Return description of numbering model&quot;"},
                    {"type": "Method", "fromName": "NumModelSaphir", "fromLink": "NumModelSaphir.html", "link": "NumModelSaphir.html#method_getExample", "name": "NumModelSaphir::getExample", "doc": "&quot;Return an example of numbering&quot;"},
                    {"type": "Method", "fromName": "NumModelSaphir", "fromLink": "NumModelSaphir.html", "link": "NumModelSaphir.html#method_getNextValue", "name": "NumModelSaphir::getNextValue", "doc": "&quot;Return next free value&quot;"},
            
            {"type": "Class",  "link": "Page.html", "name": "Page", "doc": "&quot;Page class&quot;"},
                                                        {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method___construct", "name": "Page::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_setTitle", "name": "Page::setTitle", "doc": "&quot;Set page title&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_setMainSubtitle", "name": "Page::setMainSubtitle", "doc": "&quot;Set page main subtitle&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_loadLang", "name": "Page::loadLang", "doc": "&quot;Load a language file&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_loadLangs", "name": "Page::loadLangs", "doc": "&quot;Load an array of language files&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_appendToHead", "name": "Page::appendToHead", "doc": "&quot;Append content to page head&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addJsFile", "name": "Page::addJsFile", "doc": "&quot;Add JS file to page head&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addJsFiles", "name": "Page::addJsFiles", "doc": "&quot;Add an array of JS files&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addCssFile", "name": "Page::addCssFile", "doc": "&quot;Add CSS file to page head&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addCssFiles", "name": "Page::addCssFiles", "doc": "&quot;Add an array of CSS files&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addTab", "name": "Page::addTab", "doc": "&quot;Add a tab to the page\nNote: this function should be called before $page-&gt;begin() function, otherwise it will not work as expected.&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_setTabsPicture", "name": "Page::setTabsPicture", "doc": "&quot;Set tabs picture&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_setTabsTitle", "name": "Page::setTabsTitle", "doc": "&quot;Set tabs title&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_generateTabs", "name": "Page::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addSubTitle", "name": "Page::addSubTitle", "doc": "&quot;Add a subtitle&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_openForm", "name": "Page::openForm", "doc": "&quot;Open a form only if not already opened&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_closeForm", "name": "Page::closeForm", "doc": "&quot;Close an opened form&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_openTable", "name": "Page::openTable", "doc": "&quot;Opens a new html table&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_closeTable", "name": "Page::closeTable", "doc": "&quot;Close an opened html table&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_openRow", "name": "Page::openRow", "doc": "&quot;Open a table row&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_closeRow", "name": "Page::closeRow", "doc": "&quot;Close a table row&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addColumn", "name": "Page::addColumn", "doc": "&quot;Add a table column&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_addLineBreak", "name": "Page::addLineBreak", "doc": "&quot;Add a line break (or many)&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_getTemplatePath", "name": "Page::getTemplatePath", "doc": "&quot;Return template absolute path&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_showTemplate", "name": "Page::showTemplate", "doc": "&quot;Include a template into the page.&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_underConstruction", "name": "Page::underConstruction", "doc": "&quot;Show page_under_construction template (only once)&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_notFound", "name": "Page::notFound", "doc": "&quot;Show page_not_found template (only once)&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_loadDefaultActions", "name": "Page::loadDefaultActions", "doc": "&quot;Load default actions&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_begin", "name": "Page::begin", "doc": "&quot;Generate page beginning&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_generate", "name": "Page::generate", "doc": "&quot;Generate page body&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_redirect", "name": "Page::redirect", "doc": "&quot;Redirect to a url (alias for dolibase_redirect function)&quot;"},
                    {"type": "Method", "fromName": "Page", "fromLink": "Page.html", "link": "Page.html#method_end", "name": "Page::end", "doc": "&quot;Generate page end&quot;"},
            
            {"type": "Class",  "link": "QueryBuilder.html", "name": "QueryBuilder", "doc": "&quot;QueryBuilder class&quot;"},
                                                        {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method___construct", "name": "QueryBuilder::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_getInstance", "name": "QueryBuilder::getInstance", "doc": "&quot;Return Query Builder instance&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_get", "name": "QueryBuilder::get", "doc": "&quot;Return query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_reset", "name": "QueryBuilder::reset", "doc": "&quot;Reset Query Builder&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_execute", "name": "QueryBuilder::execute", "doc": "&quot;Execute query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_result", "name": "QueryBuilder::result", "doc": "&quot;Execute query if not executed &amp;amp; return an array of result(s)&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_count", "name": "QueryBuilder::count", "doc": "&quot;Execute query if not executed &amp;amp; return query result(s) count&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_affected", "name": "QueryBuilder::affected", "doc": "&quot;Return affected rows count of an INSERT, UPDATE or DELETE query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_lastId", "name": "QueryBuilder::lastId", "doc": "&quot;Return last id after an INSERT query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_escape", "name": "QueryBuilder::escape", "doc": "&quot;Escape field value&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_select", "name": "QueryBuilder::select", "doc": "&quot;Add SELECT statement to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_addSelect", "name": "QueryBuilder::addSelect", "doc": "&quot;Add more options to SELECT statement (multiple calls allowed)&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_from", "name": "QueryBuilder::from", "doc": "&quot;Add FROM clause to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_where", "name": "QueryBuilder::where", "doc": "&quot;Add WHERE clause to query (multiple calls allowed)&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_orWhere", "name": "QueryBuilder::orWhere", "doc": "&quot;Add OR to WHERE clause (multiple calls allowed)&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_groupBy", "name": "QueryBuilder::groupBy", "doc": "&quot;Add GROUP BY clause to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_orderBy", "name": "QueryBuilder::orderBy", "doc": "&quot;Add ORDER BY clause to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_limit", "name": "QueryBuilder::limit", "doc": "&quot;Add LIMIT clause to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_join", "name": "QueryBuilder::join", "doc": "&quot;Add JOIN clause to query (multiple calls allowed)&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_insert", "name": "QueryBuilder::insert", "doc": "&quot;Add INSERT statement to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_update", "name": "QueryBuilder::update", "doc": "&quot;Add UPDATE statement to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_delete", "name": "QueryBuilder::delete", "doc": "&quot;Add DELETE statement to query&quot;"},
                    {"type": "Method", "fromName": "QueryBuilder", "fromLink": "QueryBuilder.html", "link": "QueryBuilder.html#method_truncate", "name": "QueryBuilder::truncate", "doc": "&quot;Add TRUNCATE statement to query&quot;"},
            
            {"type": "Class",  "link": "SetupPage.html", "name": "SetupPage", "doc": "&quot;SetupPage class&quot;"},
                                                        {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method___construct", "name": "SetupPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_setTitleLink", "name": "SetupPage::setTitleLink", "doc": "&quot;Set Title link&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_setDocModelPreviewPicture", "name": "SetupPage::setDocModelPreviewPicture", "doc": "&quot;Set Document model(s) preview picture&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_loadDefaultActions", "name": "SetupPage::loadDefaultActions", "doc": "&quot;Load default actions&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_generate", "name": "SetupPage::generate", "doc": "&quot;Generate page body&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_generateTabs", "name": "SetupPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_useAjaxToSwitchOnOff", "name": "SetupPage::useAjaxToSwitchOnOff", "doc": "&quot;Set $use_ajax_to_switch_on_off attribute to true&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_setupNotAvailable", "name": "SetupPage::setupNotAvailable", "doc": "&quot;Show setup_not_available template (only once)&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_newOptionsTable", "name": "SetupPage::newOptionsTable", "doc": "&quot;Create a new table for options&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addOption", "name": "SetupPage::addOption", "doc": "&quot;Add a new option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addSwitchOption", "name": "SetupPage::addSwitchOption", "doc": "&quot;Add a new switch option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addTextOption", "name": "SetupPage::addTextOption", "doc": "&quot;Add a new text option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addNumberOption", "name": "SetupPage::addNumberOption", "doc": "&quot;Add a new number only option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addRangeOption", "name": "SetupPage::addRangeOption", "doc": "&quot;Add a new range option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addListOption", "name": "SetupPage::addListOption", "doc": "&quot;Add a new list option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addMultiSelectListOption", "name": "SetupPage::addMultiSelectListOption", "doc": "&quot;Add a new multi select list option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addColorOption", "name": "SetupPage::addColorOption", "doc": "&quot;Add a new color picker option&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_openButtonsDiv", "name": "SetupPage::openButtonsDiv", "doc": "&quot;Open buttons div (if not already opened)&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_closeButtonsDiv", "name": "SetupPage::closeButtonsDiv", "doc": "&quot;Close buttons div (if opened)&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addButton", "name": "SetupPage::addButton", "doc": "&quot;Add a button to the page&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_addConfirmButton", "name": "SetupPage::addConfirmButton", "doc": "&quot;Add a confirmation button to the page&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_printNumModels", "name": "SetupPage::printNumModels", "doc": "&quot;Print numbering models&quot;"},
                    {"type": "Method", "fromName": "SetupPage", "fromLink": "SetupPage.html", "link": "SetupPage.html#method_printDocModels", "name": "SetupPage::printDocModels", "doc": "&quot;Print document models&quot;"},
            
            {"type": "Class",  "link": "StatsPage.html", "name": "StatsPage", "doc": "&quot;StatsPage class&quot;"},
                                                        {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method___construct", "name": "StatsPage::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_begin", "name": "StatsPage::begin", "doc": "&quot;Generate page beginning&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_generateTabs", "name": "StatsPage::generateTabs", "doc": "&quot;Generate tabs&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_end", "name": "StatsPage::end", "doc": "&quot;Generate page end&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_openLeftSection", "name": "StatsPage::openLeftSection", "doc": "&quot;Opens a left section&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_closeLeftSection", "name": "StatsPage::closeLeftSection", "doc": "&quot;Close a left section&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_openRightSection", "name": "StatsPage::openRightSection", "doc": "&quot;Opens a right section&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_closeRightSection", "name": "StatsPage::closeRightSection", "doc": "&quot;Close a right section&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_addFilterForm", "name": "StatsPage::addFilterForm", "doc": "&quot;Add a filter form&quot;"},
                    {"type": "Method", "fromName": "StatsPage", "fromLink": "StatsPage.html", "link": "StatsPage.html#method_addGraph", "name": "StatsPage::addGraph", "doc": "&quot;Add a statistics graph&quot;"},
            
            {"type": "Class",  "link": "Widget.html", "name": "Widget", "doc": "&quot;Widget class&quot;"},
                                                        {"type": "Method", "fromName": "Widget", "fromLink": "Widget.html", "link": "Widget.html#method___construct", "name": "Widget::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "Widget", "fromLink": "Widget.html", "link": "Widget.html#method_setTitle", "name": "Widget::setTitle", "doc": "&quot;Set widget title&quot;"},
                    {"type": "Method", "fromName": "Widget", "fromLink": "Widget.html", "link": "Widget.html#method_setLink", "name": "Widget::setLink", "doc": "&quot;Set widget link&quot;"},
                    {"type": "Method", "fromName": "Widget", "fromLink": "Widget.html", "link": "Widget.html#method_addContent", "name": "Widget::addContent", "doc": "&quot;Add content to widget&quot;"},
                    {"type": "Method", "fromName": "Widget", "fromLink": "Widget.html", "link": "Widget.html#method_newLine", "name": "Widget::newLine", "doc": "&quot;Add a new line to widget&quot;"},
                    {"type": "Method", "fromName": "Widget", "fromLink": "Widget.html", "link": "Widget.html#method_showBox", "name": "Widget::showBox", "doc": "&quot;Method to show box. Called by Dolibarr eatch time it wants to display the box.&quot;"},
            
            {"type": "Class",  "link": "pdf_azur.html", "name": "pdf_azur", "doc": "&quot;pdf_azur class&quot;"},
                                                        {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method___construct", "name": "pdf_azur::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method_write_file", "name": "pdf_azur::write_file", "doc": "&quot;Function to build pdf onto disk&quot;"},
                    {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method_write_content", "name": "pdf_azur::write_content", "doc": "&quot;Function to write pdf content&quot;"},
                    {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method_print_line", "name": "pdf_azur::print_line", "doc": "&quot;Function to print table line&quot;"},
                    {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method__tableau", "name": "pdf_azur::_tableau", "doc": "&quot;Show table for lines&quot;"},
                    {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method__pagehead", "name": "pdf_azur::_pagehead", "doc": "&quot;Show top header of page.&quot;"},
                    {"type": "Method", "fromName": "pdf_azur", "fromLink": "pdf_azur.html", "link": "pdf_azur.html#method__pagefoot", "name": "pdf_azur::_pagefoot", "doc": "&quot;Show footer of page. Need this-&gt;emetteur object&quot;"},
            
            {"type": "Class",  "link": "pdf_crabe.html", "name": "pdf_crabe", "doc": "&quot;pdf_crabe class&quot;"},
                                                        {"type": "Method", "fromName": "pdf_crabe", "fromLink": "pdf_crabe.html", "link": "pdf_crabe.html#method___construct", "name": "pdf_crabe::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "pdf_crabe", "fromLink": "pdf_crabe.html", "link": "pdf_crabe.html#method_write_content", "name": "pdf_crabe::write_content", "doc": "&quot;Function to write pdf content&quot;"},
                    {"type": "Method", "fromName": "pdf_crabe", "fromLink": "pdf_crabe.html", "link": "pdf_crabe.html#method_print_column", "name": "pdf_crabe::print_column", "doc": "&quot;Function to print table line&quot;"},
                    {"type": "Method", "fromName": "pdf_crabe", "fromLink": "pdf_crabe.html", "link": "pdf_crabe.html#method__tableau", "name": "pdf_crabe::_tableau", "doc": "&quot;Show table for lines&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


