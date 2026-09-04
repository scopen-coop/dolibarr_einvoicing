<?php
/* Copyright (C) 2026
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *   	\file       supplierinvoice_importorderlines.php
 *		\ingroup    einvoicing
 *		\brief      List of supplier-order lines of the same vendor, to replace the free lines
 *					of a received e-invoice (draft) with the selected order lines.
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) {
	$res = @include "../main.inc.php";
}
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res && file_exists("../../../../main.inc.php")) {
	$res = @include "../../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}
/**
 * The main.inc.php has been included so the following variable are now defined:
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fourn.lib.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.commande.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
dol_include_once('einvoicing/class/utils/SupplierInvoiceHelper.class.php');
dol_include_once('einvoicing/class/utils/SupplierOrderLineImporter.class.php');

$langs->loadLangs(array('einvoicing@einvoicing', 'bills', 'orders', 'products', 'projects', 'other', 'companies'));

$hookmanager->initHooks(array('invoicesuppliercard', 'globalcard'));

$id = GETPOSTINT('id') ? GETPOSTINT('id') : GETPOSTINT('facid');
$ref = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'aZ09');
if (GETPOST('importselected')) {
	$action = 'import';
}
$confirm = GETPOST('confirm', 'alpha');
$toselect = GETPOST('toselect', 'array');
$contextpage = GETPOST('contextpage', 'aZ') ? GETPOST('contextpage', 'aZ') : 'einvoicingsupplierorderlineimport';

$search_ref = GETPOST('search_ref', 'alphanohtml');
$search_product = GETPOST('search_product', 'alphanohtml');
$search_qty = GETPOST('search_qty', 'alphanohtml');
$search_subprice = GETPOST('search_subprice', 'alphanohtml');
$search_total_ht = GETPOST('search_total_ht', 'alphanohtml');
$search_project = GETPOST('search_project', 'alphanohtml');
$search_extra = array();

$limit = GETPOSTINT('limit') ? GETPOSTINT('limit') : $conf->liste_limit;
$sortfield = GETPOST('sortfield', 'aZ09comma');
$sortorder = GETPOST('sortorder', 'aZ09comma');
$page = GETPOSTISSET('pageplusone') ? (GETPOSTINT('pageplusone') - 1) : GETPOSTINT('page');
if (empty($page) || $page < 0 || GETPOST('button_search', 'alpha') || GETPOST('button_removefilter', 'alpha')) {
	$page = 0;
}
$offset = $limit * $page;
if (!$sortfield) {
	$sortfield = 'c.ref,cd.rowid';
}
if (!$sortorder) {
	$sortorder = 'ASC';
}

$form = new Form($db);
$object = new FactureFournisseur($db);

if ($id > 0 || $ref) {
	$result = $object->fetch($id, $ref);
	if ($result <= 0) {
		accessforbidden($langs->trans('ErrorRecordNotFound'), 0, 0, 1);
	}
	$id = (int) $object->id;
} else {
	accessforbidden();
}

$permissiontoimport = $user->hasRight('fournisseur', 'facture', 'creer');

if (!isModEnabled('einvoicing') || (!isModEnabled('fournisseur') && !isModEnabled('supplier'))) {
	accessforbidden();
}
if (!$user->hasRight('fournisseur', 'facture', 'lire')) {
	accessforbidden();
}
if (!SupplierOrderLineImporter::isEligibleInvoice($object)) {
	accessforbidden($langs->trans('SupplierOrderLineImportNotEligible'), 0, 0, 1);
}

$extrafields = new ExtraFields($db);
$extrafields->fetch_name_optionals_label('commande_fournisseurdet');
$visibleExtrafields = SupplierOrderLineImporter::visibleLineExtrafieldNames($extrafields);
foreach ($visibleExtrafields as $efname) {
	$search_extra[$efname] = GETPOST('search_ef_'.$efname, 'alphanohtml');
}

$showProjectColumn = !in_array('wrike_project', $visibleExtrafields, true);

$param = '&id='.((int) $object->id);
if ($limit > 0 && $limit != $conf->liste_limit) {
	$param .= '&limit='.((int) $limit);
}
if ($search_ref != '') {
	$param .= '&search_ref='.urlencode($search_ref);
}
if ($search_product != '') {
	$param .= '&search_product='.urlencode($search_product);
}
if ($search_qty != '') {
	$param .= '&search_qty='.urlencode($search_qty);
}
if ($search_subprice != '') {
	$param .= '&search_subprice='.urlencode($search_subprice);
}
if ($search_total_ht != '') {
	$param .= '&search_total_ht='.urlencode($search_total_ht);
}
if ($search_project != '') {
	$param .= '&search_project='.urlencode($search_project);
}
foreach ($search_extra as $efname => $efvalue) {
	if ($efvalue !== '' && $efvalue !== null) {
		$param .= '&search_ef_'.$efname.'='.urlencode($efvalue);
	}
}


/*
 * Actions
 */

if (GETPOST('button_removefilter_x', 'alpha') || GETPOST('button_removefilter.x', 'alpha') || GETPOST('button_removefilter', 'alpha')) {
	$search_ref = '';
	$search_product = '';
	$search_qty = '';
	$search_subprice = '';
	$search_total_ht = '';
	$search_project = '';
	foreach ($visibleExtrafields as $efname) {
		$search_extra[$efname] = '';
	}
	$toselect = array();
}

if ($action == 'confirm_import' && $confirm == 'yes' && $permissiontoimport) {
	$result = SupplierOrderLineImporter::importSelectedLines($object, $user, is_array($toselect) ? $toselect : array());
	if ($result < 0) {
		setEventMessages($object->error, $object->errors, 'errors');
	} else {
		setEventMessages($langs->trans('SupplierOrderLinesImported', $result), null, 'mesgs');
		header('Location: '.DOL_URL_ROOT.'/fourn/facture/card.php?id='.((int) $object->id));
		exit;
	}
}


/*
 * Load the lines
 */

$eligibleStatuses = SupplierOrderLineImporter::eligibleOrderStatuses();

$sql = "SELECT cd.rowid, cd.fk_commande, cd.fk_product, cd.qty, cd.subprice, cd.total_ht, cd.description, cd.product_type,";
$sql .= " c.ref as order_ref, c.fk_statut as order_status, c.fk_projet,";
$sql .= " p.ref as product_ref, p.label as product_label,";
$sql .= " pjt.ref as project_ref, pjt.title as project_title";
foreach ($visibleExtrafields as $efname) {
	$sql .= ", efd.".$efname." as ef_".$efname;
}
$sql .= " FROM ".$db->prefix()."commande_fournisseurdet as cd";
$sql .= " INNER JOIN ".$db->prefix()."commande_fournisseur as c ON c.rowid = cd.fk_commande";
$sql .= " LEFT JOIN ".$db->prefix()."product as p ON p.rowid = cd.fk_product";
$sql .= " LEFT JOIN ".$db->prefix()."projet as pjt ON pjt.rowid = c.fk_projet";
if (!empty($visibleExtrafields)) {
	$sql .= " LEFT JOIN ".$db->prefix()."commande_fournisseurdet_extrafields as efd ON efd.fk_object = cd.rowid";
}
$sql .= " WHERE c.fk_soc = ".((int) $object->socid);
$sql .= " AND c.fk_statut IN (".implode(',', array_map('intval', $eligibleStatuses)).")";
$sql .= " AND c.entity IN (".getEntity('supplier_order').")";
$sql .= " AND cd.product_type < 9";
if ($search_ref != '') {
	$sql .= natural_search('c.ref', $search_ref);
}
if ($search_product != '') {
	$sql .= natural_search(array('p.ref', 'p.label', 'cd.description'), $search_product);
}
if ($search_qty != '') {
	$sql .= natural_search('cd.qty', $search_qty, 1);
}
if ($search_subprice != '') {
	$sql .= natural_search('cd.subprice', $search_subprice, 1);
}
if ($search_total_ht != '') {
	$sql .= natural_search('cd.total_ht', $search_total_ht, 1);
}
if ($showProjectColumn && $search_project != '') {
	$sql .= natural_search(array('pjt.ref', 'pjt.title'), $search_project);
}
foreach ($visibleExtrafields as $efname) {
	if ($search_extra[$efname] === '' || $search_extra[$efname] === null) {
		continue;
	}
	if (!preg_match('/^[a-z0-9_]+$/i', $efname)) {
		continue;
	}
	if ($efname == 'project_analytic') {
		$sql .= " AND (efd.project_analytic IN (SELECT psearch.rowid FROM ".$db->prefix()."projet as psearch WHERE 1=1 ".natural_search('psearch.ref', $search_extra[$efname], 0).")";
		$sql .= " OR CAST(efd.project_analytic AS CHAR) LIKE '%".$db->escape($db->escapeforlike($search_extra[$efname]))."%'";
		$sql .= ")";
	} else {
		$sql .= natural_search('efd.'.$efname, $search_extra[$efname]);
	}
}

$nbtotalofrecords = '';
if (!getDolGlobalInt('MAIN_DISABLE_FULL_SCANLIST')) {
	$resql = $db->query($sql);
	$nbtotalofrecords = $resql ? $db->num_rows($resql) : 0;
	if (($page * $limit) > $nbtotalofrecords) {
		$page = 0;
		$offset = 0;
	}
}

$sql .= $db->order($sortfield, $sortorder);
$sql .= $db->plimit($limit + 1, $offset);

$resql = $db->query($sql);
if (!$resql) {
	dol_print_error($db);
	exit;
}
$num = $db->num_rows($resql);


/*
 * View
 */

$title = $langs->trans('ImportSupplierOrderLines');

llxHeader('', $title, '', '', 0, 0, '', '', '', 'mod-einvoicing page-supplierinvoice_importorderlines');

$head = facturefourn_prepare_head($object);
print dol_get_fiche_head($head, 'importorderlines', $langs->trans('SupplierInvoice'), -1, 'supplier_invoice');

$linkback = '<a href="'.DOL_URL_ROOT.'/fourn/facture/list.php?restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';
$morehtmlref = '';
dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'ref', $morehtmlref);

print '<div class="fichecenter">';
print '<div class="underbanner clearboth"></div>';

$comparison = SupplierInvoiceHelper::checkPaHeaderTotals($object, false);
print '<table class="border centpercent tableforfield">';
print '<tr><td class="titlefield">'.$langs->trans('PaTotalHT').'</td><td>';
print self_einvoicing_format_total_cell($comparison, 'total_ht');
print '</td></tr>';
print '<tr><td>'.$langs->trans('PaTotalVAT').'</td><td>';
print self_einvoicing_format_total_cell($comparison, 'total_tva');
print '</td></tr>';
print '<tr><td>'.$langs->trans('PaTotalTTC').'</td><td>';
print self_einvoicing_format_total_cell($comparison, 'total_ttc');
print '</td></tr>';
print '</table>';

print '</div>';
print dol_get_fiche_end();

print '<div class="info">'.$langs->trans('ImportSupplierOrderLinesHelpPage').'</div>';

if ($action == 'import' && $permissiontoimport && is_array($toselect) && count($toselect) > 0) {
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'?id='.((int) $object->id).'">';
	print '<input type="hidden" name="token" value="'.newToken().'">';
	print '<input type="hidden" name="action" value="confirm_import">';
	print '<input type="hidden" name="confirm" value="yes">';
	foreach ($toselect as $selected) {
		print '<input type="hidden" name="toselect[]" value="'.((int) $selected).'">';
	}
	print '<div class="warning">';
	print $langs->trans('ConfirmImportSupplierOrderLines', count($toselect)).'<br><br>';
	print '<input type="submit" class="button" value="'.$langs->trans('Import').'">';
	print ' <a class="button button-cancel" href="'.$_SERVER["PHP_SELF"].'?id='.((int) $object->id).'">'.$langs->trans('Cancel').'</a>';
	print '</div>';
	print '</form>';
	print '<br>';
}

print '<form method="POST" id="searchFormList" action="'.$_SERVER["PHP_SELF"].'?id='.((int) $object->id).'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="formfilteraction" id="formfilteraction" value="list">';
print '<input type="hidden" name="contextpage" value="'.dol_escape_htmltag($contextpage).'">';
print '<input type="hidden" name="sortfield" value="'.dol_escape_htmltag($sortfield).'">';
print '<input type="hidden" name="sortorder" value="'.dol_escape_htmltag($sortorder).'">';
print '<input type="hidden" name="id" value="'.((int) $object->id).'">';

$massactionbutton = '';
if ($permissiontoimport) {
	$massactionbutton = '<input type="submit" class="reposition button" name="importselected" value="'.$langs->trans('ImportSelectedSupplierOrderLines').'">';
}

print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, $massactionbutton, $num, $nbtotalofrecords, 'supplier_order', 0, '', '', $limit);

print '<div class="div-table-responsive">';
print '<table class="tagtable nobottomiftotal liste">';

$colspan = 6 + ($showProjectColumn ? 1 : 0) + count($visibleExtrafields) + 1;

// Filters
print '<tr class="liste_titre_filter">';
print '<td class="liste_titre"><input type="text" class="flat maxwidth100" name="search_ref" value="'.dol_escape_htmltag($search_ref).'"></td>';
print '<td class="liste_titre"><input type="text" class="flat maxwidth100" name="search_product" value="'.dol_escape_htmltag($search_product).'"></td>';
print '<td class="liste_titre right"><input type="text" class="flat maxwidth50 right" name="search_qty" value="'.dol_escape_htmltag($search_qty).'"></td>';
if ($showProjectColumn) {
	print '<td class="liste_titre"><input type="text" class="flat maxwidth100" name="search_project" value="'.dol_escape_htmltag($search_project).'"></td>';
}
foreach ($visibleExtrafields as $efname) {
	print '<td class="liste_titre"><input type="text" class="flat maxwidth100" name="search_ef_'.$efname.'" value="'.dol_escape_htmltag($search_extra[$efname]).'"></td>';
}
print '<td class="liste_titre right"><input type="text" class="flat maxwidth75 right" name="search_subprice" value="'.dol_escape_htmltag($search_subprice).'"></td>';
print '<td class="liste_titre right"><input type="text" class="flat maxwidth75 right" name="search_total_ht" value="'.dol_escape_htmltag($search_total_ht).'"></td>';
print '<td class="liste_titre center">';
print $form->showFilterButtons();
print '</td>';
print '</tr>';

// Titles
print '<tr class="liste_titre">';
print_liste_field_titre('RefOrderSupplier', $_SERVER['PHP_SELF'], 'c.ref', '', $param, '', $sortfield, $sortorder);
print_liste_field_titre('ProductRef', $_SERVER['PHP_SELF'], 'p.ref', '', $param, '', $sortfield, $sortorder);
print_liste_field_titre('Qty', $_SERVER['PHP_SELF'], 'cd.qty', '', $param, '', $sortfield, $sortorder, 'right ');
if ($showProjectColumn) {
	print_liste_field_titre('Project', $_SERVER['PHP_SELF'], 'pjt.ref', '', $param, '', $sortfield, $sortorder);
}
foreach ($visibleExtrafields as $efname) {
	$label = $extrafields->attributes['commande_fournisseurdet']['label'][$efname];
	print_liste_field_titre($langs->trans($label), $_SERVER['PHP_SELF'], 'efd.'.$efname, '', $param, '', $sortfield, $sortorder);
}
print_liste_field_titre('UnitPriceHT', $_SERVER['PHP_SELF'], 'cd.subprice', '', $param, '', $sortfield, $sortorder, 'right ');
print_liste_field_titre('TotalHT', $_SERVER['PHP_SELF'], 'cd.total_ht', '', $param, '', $sortfield, $sortorder, 'right ');
print '<th class="wrapcolumntitle liste_titre center maxwidthsearch">';
print '<input type="checkbox" class="uncheckall" id="checkallactions" name="checkallactions">';
print '</th>';
print '</tr>';

$orderstatic = new CommandeFournisseur($db);
$productstatic = new Product($db);
$projectstatic = new Project($db);

$i = 0;
while ($i < min($num, $limit)) {
	$obj = $db->fetch_object($resql);
	if (empty($obj)) {
		break;
	}

	print '<tr class="oddeven" data-total-ht="'.dol_escape_htmltag($obj->total_ht).'">';

	print '<td class="nowraponall">';
	$orderstatic->id = $obj->fk_commande;
	$orderstatic->ref = $obj->order_ref;
	$orderstatic->statut = $obj->order_status;
	$orderstatic->status = $obj->order_status;
	print $orderstatic->getNomUrl(1);
	print '</td>';

	print '<td class="tdoverflowmax200">';
	if ($obj->fk_product > 0) {
		$productstatic->id = $obj->fk_product;
		$productstatic->ref = $obj->product_ref;
		$productstatic->label = $obj->product_label;
		print $productstatic->getNomUrl(1);
	} else {
		print dol_escape_htmltag($obj->description);
	}
	print '</td>';

	print '<td class="right">'.price($obj->qty, 0, '', 0, 0).'</td>';

	if ($showProjectColumn) {
		print '<td class="tdoverflowmax150">';
		if ($obj->fk_projet > 0) {
			$projectstatic->id = $obj->fk_projet;
			$projectstatic->ref = $obj->project_ref;
			$projectstatic->title = $obj->project_title;
			print $projectstatic->getNomUrl(1);
		}
		print '</td>';
	}

	foreach ($visibleExtrafields as $efname) {
		$value = $obj->{'ef_'.$efname};
		print '<td class="tdoverflowmax150">';
		print $extrafields->showOutputField($efname, $value, '', 'commande_fournisseurdet');
		print '</td>';
	}

	print '<td class="right nowraponall">'.price($obj->subprice).'</td>';
	print '<td class="right nowraponall">'.price($obj->total_ht).'</td>';

	print '<td class="center">';
	if ($permissiontoimport) {
		$selected = (is_array($toselect) && in_array($obj->rowid, $toselect));
		print '<input class="flat checkforselect" type="checkbox" name="toselect[]" value="'.((int) $obj->rowid).'"'.($selected ? ' checked' : '').'>';
	}
	print '</td>';

	print '</tr>';
	$i++;
}

if ($num == 0) {
	print '<tr class="oddeven"><td colspan="'.$colspan.'"><span class="opacitymedium">'.$langs->trans('NoSupplierOrderLineToImport').'</span></td></tr>';
}

print '</table>';
print '</div>';

if ($permissiontoimport && $num > 0) {
	print '<div class="tabsAction">';
	print '<input type="submit" class="butAction" name="importselected" value="'.$langs->trans('ImportSelectedSupplierOrderLines').'">';
	print '</div>';
}

print '</form>';

print '<script>
jQuery(function($) {
	$("input.checkforselect").on("change", function() {
		var sum = 0;
		$("input.checkforselect:checked").each(function() {
			sum += parseFloat($(this).closest("tr").attr("data-total-ht") || 0);
		});
		if ($("#einvoicing-selected-ht").length) {
			$("#einvoicing-selected-ht").text(sum.toFixed(2));
		}
	});
});
</script>';

llxFooter();
$db->close();

/**
 * Format one total (PA vs Dolibarr) for the comparison table on the import page.
 *
 * @param	array{identical?:bool,unavailable?:bool,pa:?array<string,float>,doli:array<string,float>}	$comparison	Result of checkPaHeaderTotals()
 * @param	string	$key	total_ht, total_tva or total_ttc
 * @return	string
 */
function self_einvoicing_format_total_cell($comparison, $key)
{
	global $langs;

	$doli = isset($comparison['doli'][$key]) ? price($comparison['doli'][$key]) : '';
	if (!empty($comparison['unavailable']) || empty($comparison['pa'])) {
		return $doli.' <span class="opacitymedium">('.$langs->trans('PaTotalsUnavailable').')</span>';
	}
	$pa = price($comparison['pa'][$key]);
	$doliVal = (float) $comparison['doli'][$key];
	$paVal = (float) $comparison['pa'][$key];
	$match = (round($doliVal, getDolGlobalInt('MAIN_MAX_DECIMALS_TOT', 2)) === round($paVal, getDolGlobalInt('MAIN_MAX_DECIMALS_TOT', 2)));
	$class = $match ? 'ok' : 'error';
	return '<span class="'.$class.'">'.$langs->trans('PaTotalVsDoli', $pa, $doli).'</span>';
}
