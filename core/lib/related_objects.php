<?php
/**
 * Dolibase
 * 
 * Open source framework for Dolibarr ERP/CRM
 *
 * Copyright (c) 2018 - 2019
 *
 *
 * @package     Dolibase
 * @author      AXeL
 * @copyright   Copyright (c) 2018 - 2019, AXeL-dev
 * @license     MIT
 * @link        https://github.com/AXeL-dev/dolibase
 * 
 */

/**
 * Returns matched objects with the keyword
 *
 * @param   $keyword    keyword to search (object ref for example)
 * @return  array       matched objects
 */
function search_object($keyword)
{
	$result = array();

	$object_types = array('invoice', 'order', 'proposal', 'project', 'task', 'company', 'contact', 'activity', 'product', 'supplier-invoice', 'supplier-po', 'intervention', 'returned_product');

	foreach($object_types  as $type) {
		$result[$type] = search_object_by_type($type, $keyword);
	}

	return $result;
}

/**
 * Returns object informations using type
 *
 * @param   $type       object type
 * @return  array       object informations
 */
function get_object_by_type($type)
{
	$object = array();

	// default
	$object['table']       = MAIN_DB_PREFIX.$type;
	$object['name']        = ucfirst($type);
	$object['id_field']    = 'rowid';
	$object['ref_field']   = 'ref';
	$object['ref_field2']  = '';
	$object['date_field']  = '';
	$object['class_file']  = '';
	$object['join_to_soc'] = false;

	// customised
	if ($type == 'company') {
		$object['table']     = MAIN_DB_PREFIX.'societe';
		$object['name']      = 'Societe';
		$object['ref_field'] = 'nom';
	}
	else if ($type == 'project') {
		$object['class_file']  = '/projet/class/project.class.php';
		$object['table']       = MAIN_DB_PREFIX.'projet';
		$object['name']        = 'Project';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'task' || $type == 'project_task') {
		$object['class_file']  = '/projet/class/task.class.php';
		$object['table']       = MAIN_DB_PREFIX.'projet_task';
		$object['name']        = 'Task';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'activity' || $type == 'event' || $type == 'action') {
		$object['class_file']  = '/comm/action/class/actioncomm.class.php';
		$object['table']       = MAIN_DB_PREFIX.'actioncomm';
		$object['name']        = 'ActionComm';
		$object['id_field']    = 'id';
		$object['ref_field']   = 'id';
		$object['ref_field2']  = 'label';
		$object['date_field']  = 'datep';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'order' || $type == 'commande') {
		$object['table']       = MAIN_DB_PREFIX.'commande';
		$object['name']        = 'Commande';
		$object['date_field']  = 'date_commande';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'invoice') {
		$object['table'] = MAIN_DB_PREFIX.'facture';
		$object['name']        = 'Facture';
		$object['ref_field']   = 'facnumber';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'contact') {
		$object['class_file']  = '/contact/class/contact.class.php';
		$object['table']       = MAIN_DB_PREFIX.'socpeople';
		$object['ref_field']   = 'lastname';
		//$object['date_field']  = 'date_creation'; // will not work because Contact fetch function don't pick up the date
		$object['join_to_soc'] = true;
	}
	else if ($type == 'proposal') {
		$object['class_file']  = '/comm/propal/class/propal.class.php';
		$object['table']       = MAIN_DB_PREFIX.'propal';
		$object['name']        = 'Propal';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'supplier-invoice') {
		$object['class_file']  = '/fourn/class/fournisseur.facture.class.php';
		$object['table']       = MAIN_DB_PREFIX.'facture_fourn';
		$object['name']        = 'FactureFournisseur';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'supplier-po') {
		$object['class_file']  = '/fourn/class/fournisseur.commande.class.php';
		$object['table']       = MAIN_DB_PREFIX.'commande_fournisseur';
		$object['name']        = 'CommandeFournisseur';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'intervention') {
		$object['table']       = MAIN_DB_PREFIX.'fichinter';
		$object['name']        = 'Fichinter';
		$object['join_to_soc'] = true;
	}
	else if ($type == 'returned_product') {
		$object['class_file']  = '/productreturns/class/returnedProduct.class.php';
		$object['table']       = MAIN_DB_PREFIX.'returned_product';
		$object['name']        = 'ReturnedProduct';
		$object['date_field']  = 'creation_date';
	}

	return $object;
}

/**
 * Returns matched objects with the keyword & type
 *
 * @param   $type       object type
 * @param   $keyword    keyword to search (object ref for example)
 * @return  array       matched objects
 */
function search_object_by_type($type, $keyword)
{
	global $db;

	$object = get_object_by_type($type);

	$result = array();

	// Build query
	$sql = "SELECT t.".$object['id_field']." as rowid, CONCAT(t.".$object['ref_field']." ".( empty($object['ref_field2']) ? '' : ",' ',t.".$object['ref_field2'] )." ) as ref ";

	if ($object['join_to_soc']) {
		if ($type == 'task') {
			$sql.= ",CONCAT(p.title,', ',s.nom) as client";
		}
		else if ($type == 'order' || $type == 'commande') {
			$sql.= ",CONCAT(s.nom , ', Date : ' , DATE_FORMAT(t.date_commande,'%m-%d-%Y')) as client";
		}
		else {
			$sql.= ",s.nom as client";
		}
	}

	$sql.= " FROM ".$object['table']." as t ";

	if ($object['join_to_soc']) {
		if ($type == 'task') {
			$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."projet p ON (p.rowid = t.fk_projet)";
			$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe s ON (s.rowid = p.fk_soc)";
		}
		else {
			$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe s ON (s.rowid = t.fk_soc)";
		}
	}

	if ($db->type == 'pgsql' && ($object['ref_field'] == 'id' || $object['ref_field'] == 'rowid')) {
		$sql.= " WHERE CAST(t.".$object['ref_field']." AS TEXT) LIKE '".$keyword."%'";
	} else {
		$sql.= " WHERE t.".$object['ref_field']." LIKE '".$keyword."%'";
	}

	if (! empty($object['ref_field2']) && $db->type == 'pgsql' && ($object['ref_field2'] == 'id' || $object['ref_field2'] == 'rowid')) {
		$sql.= " OR CAST(t.".$object['ref_field2']." AS TEXT) LIKE '".$keyword."%'";
	} else if (!empty($object['ref_field2'])) {
		$sql.= " OR t.".$object['ref_field2']." LIKE '".$keyword."%'";
	}

	$sql.= " LIMIT 20";

	// Run query
	$res = $db->query($sql);

	if($res)
	{
		$nb_results = $db->num_rows($res);

		if($nb_results > 0)
		{
			while($obj = $db->fetch_object($res))
			{
				$r = $obj->ref;
				if(! empty($obj->client)) $r.= ', '.$obj->client;

				$result[$obj->rowid] = $r;
			}
		}
	}

	return $result;
}

/**
 * Print related objects block
 *
 * @param   $object   object
 */
function show_related_objects($object)
{
	global $langs, $db;

	dolibase_include_once('/core/class/query_builder.php');

	$action = GETPOST('action');

	// Actions
	if ($action == 'add_related_link')
	{
		$type = GETPOST('related_object_type');
		$id = GETPOST('related_object_id');

		if ($type == 'projet') $type = 'project';
		else if ($type == 'invoice') $type = 'facture';
		else if ($type == 'company') $type = 'societe';
		else if ($type == 'facture_fournisseur') $type = 'invoice_supplier';
		else if ($type == 'commande_fournisseur') $type = 'order_supplier';

		$res = $object->add_object_linked($type, $id);

		if ($res) {
			setEventMessage($langs->trans('RelationAdded'));
		}
		else {
			setEventMessage($langs->trans('RelationCantBeAdded'), 'errors');
		}
	}
	else if ($action == 'delete_related_link')
	{
		$idLink = GETPOST('id_link');

		if($idLink) {
			$res = QueryBuilder::getInstance()->delete('element_element')->where("rowid = $idLink")->execute();
		}
	}

	// Load linked objects
	if (empty($object->linkedObjects)) {
		$object->fetchObjectLinked();
	}
	//var_dump($object->linkedObjectsIds);

	// Show linked objects

	?>
	<div class="relatedobjects_block">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="action" value="add_related_link" />
			<input type="hidden" name="id" value="<?php echo $object->id; ?>" />
			<input type="hidden" id="related_object_id" name="related_object_id" value="" />
			<input type="hidden" id="related_object_type" name="related_object_type" value="" />

			<div align="left" class="titre"><?php echo $langs->trans('ElementToLink'); ?></div>

			<table class="noborder allwidth">
				<tr class="liste_titre">
					<td><?php echo $langs->trans("Ref"); ?> <input type="text" id="add_related_object" name="add_related_object" value="" class="flat" /> <input type="submit" id="add_related_object_btn" name="add_related_object_btn" class="button hidden" value="<?php echo $langs->trans('AddRelated') ?>" /></td>
					<td align="center"><?php echo $langs->trans("Date"); ?></td>
					<td align="center"><?php echo $langs->trans("Status"); ?></td>
					<td align="center"><?php echo $langs->trans("Action"); ?></td>
				</tr>
				<?php
					$class = 'pair';

					foreach($object->linkedObjectsIds as $sourcetype => $objectsid)
					{
						foreach($objectsid as $sourceid)
						{
							$obj = get_object_by_type($sourcetype);

							$object_date  = 0;
							$classname    = $obj['name'];
							$classpath    = $obj['class_file'];
							$date_field   = $obj['date_field'];
							$statut       = 'N/A';

							if (! empty($classpath)) {
								dol_include_once($classpath);
							}

							if (! class_exists($classname)) {
								$link = 'CantInstanciateClass '.$classname;
							}
							else {
								$subobject = new $classname($db);
								$subobject->fetch($sourceid);

								if(method_exists($subobject, 'getNomUrl')) {
									$link = $subobject->getNomUrl(1);
								}
								else{
									$link = $sourceid.'/'.$classname;
								}

								$class = ($class == 'impair') ? 'pair' : 'impair';

								if(!empty($date_field) && !empty($subobject->{$date_field})) $object_date = $subobject->{$date_field};
								if(empty($object_date) && !empty($subobject->date_creation)) $object_date = $subobject->date_creation;
								if(empty($object_date) && !empty($subobject->date_create)) $object_date = $subobject->date_create;
								if(empty($object_date) && !empty($subobject->date_c)) $object_date = $subobject->date_c;
								if(empty($object_date) && !empty($subobject->datec)) $object_date = $subobject->datec;

								if(method_exists($subobject, 'getLibStatut')) $statut = $subobject->getLibStatut(3);
							}

							// Fetch relation
							$relation = QueryBuilder::getInstance()->select('rowid as id')->from('element_element')->where("fk_source = $sourceid AND fk_target = $object->id AND sourcetype = '$sourcetype' AND targettype = '$object->element'")->result()[0];

							?>
							<tr class="<?php echo $class; ?>">
								<td align="left"><?php echo $link; ?></td>
								<td align="center"><?php echo ! empty($object_date) ? dol_print_date($object_date, 'day') : ''; ?></td>
								<td align="center"><?php echo $statut; ?></td>
								<td align="center"><a href="?id=<?php echo $object->id; ?>&action=delete_related_link&id_link=<?php echo $relation->id; ?>"><?php echo img_picto($langs->trans("Delete"), 'delete.png') ?></a></td>
							</tr>
							<?php

						}

					}

				?>
				</table>
		</form>
	</div>

	<script type="text/javascript">

		$(document).ready(function() {

			$('#add_related_object').autocomplete({
				source: function(request, response) {
					$.ajax({
						url: "<?php echo dolibase_buildurl('/core/ajax/related_objects.php'); ?>",
						dataType: "json",
						data: {
							key: request.term,
							action: 'search'
						},
						success: function(data) {
							var c = [];
							$.each(data, function (i, cat) {

								var first = true;
								$.each(cat, function(j, label) {

									if(first) {
										c.push({value:i, label:i, object:'title'});
										first = false;
									}

									c.push({ value: j, label:'  '+label, object:i});
								});
							});

							response(c);
						}
					});
				},
				minLength: 1,
				select: function(event, ui) {

					if(ui.item.object == 'title') return false;
					else {
						$('#related_object_id').val(ui.item.value);
						$('#add_related_object').val(ui.item.label.trim());
						$('#related_object_type').val(ui.item.object);

						$('#add_related_object_btn').removeClass('hidden');

						return false;
					}
				},
				open: function(event, ui) {
					$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
				},
				close: function() {
					$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
				}
			});

			$("#add_related_object").autocomplete().data("uiAutocomplete")._renderItem = function(ul, item) {

				$li = $( "<li />" )
					.attr( "data-value", item.value )
					.append( item.label )
					.appendTo( ul );

				if(item.object=="title") $li.css("font-weight","bold");

				return $li;
			};

			var blockrelated = $('div.tabsAction .relatedobjects_block');
			if (blockrelated.length == 1)
			{
				if ($('.relatedobjects_block').length > 1)
				{
					blockrelated.remove();
				}
				else
				{
					blockrelated.appendTo($('div.tabsAction'));
				}
			}
		});

	</script>

	<?php

} // end show function
