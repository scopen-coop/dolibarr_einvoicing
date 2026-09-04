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
 * \file    einvoicing/class/utils/SupplierOrderLineImporter.class.php
 * \ingroup einvoicing
 * \brief   Replace the free lines of a received supplier e-invoice with lines taken from
 *          supplier orders of the same vendor, the way the core does when a supplier invoice
 *          is created from a supplier order with a selection of lines.
 */

dol_include_once('einvoicing/class/utils/SupplierInvoiceHelper.class.php');
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.commande.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.orderline.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';

/**
 * Class SupplierOrderLineImporter
 */
class SupplierOrderLineImporter
{
	/**
	 * Extrafield names shown as dedicated columns when they exist on supplier order lines.
	 * wrike_project and project_analytic are the names used by installations that keep a Wrike
	 * project and an analytical code on each line; they are looked up, not required.
	 *
	 * @var string[]
	 */
	const PREFERRED_LINE_EXTRAFIELDS = array('wrike_project', 'project_analytic');

	/**
	 * Whether the feature is switched on.
	 *
	 * @return bool
	 */
	public static function isEnabled()
	{
		return (bool) getDolGlobalInt('EINVOICING_IMPORT_SUPPLIER_ORDER_LINES');
	}

	/**
	 * Supplier-order statuses whose lines can be imported: the order has been sent to the vendor
	 * (Commandée) or already received, partially or in full. Draft, approved-but-not-sent, cancelled
	 * and refused orders are left out.
	 *
	 * @return int[]
	 */
	public static function eligibleOrderStatuses()
	{
		return array(
			CommandeFournisseur::STATUS_ORDERSENT,
			CommandeFournisseur::STATUS_RECEIVED_PARTIALLY,
			CommandeFournisseur::STATUS_RECEIVED_COMPLETELY,
		);
	}

	/**
	 * A draft supplier invoice that came from the Access Point, on which the operator can still
	 * replace free lines with supplier-order lines.
	 *
	 * @param	FactureFournisseur	$invoice	Invoice on the card
	 * @return	bool
	 */
	public static function isEligibleInvoice(FactureFournisseur $invoice)
	{
		if (!self::isEnabled()) {
			return false;
		}
		if (empty($invoice->id) || $invoice->element != 'invoice_supplier') {
			return false;
		}
		$status = isset($invoice->status) ? (int) $invoice->status : (int) $invoice->statut;
		if ($status != FactureFournisseur::STATUS_DRAFT) {
			return false;
		}
		if (getDolGlobalString('EINVOICING_DISABLE_SYNC_AP_TO_DOLI')) {
			return false;
		}

		return SupplierInvoiceHelper::isEInvoice((int) $invoice->id);
	}

	/**
	 * A free line of a received e-invoice: no product, not a subtotal, not a deposit/discount.
	 *
	 * @param	object	$line	A supplier invoice line
	 * @return	bool
	 */
	public static function isFreeInvoiceLine($line)
	{
		if (!empty($line->fk_product)) {
			return false;
		}
		if ((int) $line->product_type >= 9) {
			return false;
		}
		if (!empty($line->fk_remise_except)) {
			return false;
		}

		return true;
	}

	/**
	 * Extrafields of supplier order lines that this screen shows as their own column.
	 *
	 * @param	ExtraFields	$extrafields	Loaded extrafields
	 * @return	string[]					Extrafield names, in display order
	 */
	public static function visibleLineExtrafieldNames(ExtraFields $extrafields)
	{
		$element = 'commande_fournisseurdet';
		if (empty($extrafields->attributes[$element]['label']) || !is_array($extrafields->attributes[$element]['label'])) {
			return array();
		}

		$names = array();
		foreach (self::PREFERRED_LINE_EXTRAFIELDS as $name) {
			if (!preg_match('/^[a-z0-9_]+$/i', $name)) {
				continue;
			}
			if (array_key_exists($name, $extrafields->attributes[$element]['label'])) {
				$names[] = $name;
			}
		}

		return $names;
	}

	/**
	 * Delete the free lines of a draft supplier invoice.
	 *
	 * @param	FactureFournisseur	$invoice	Draft invoice
	 * @param	User				$user		User performing the import (unused, kept for callers)
	 * @return	int								Number of lines deleted, or <0 on error
	 */
	public static function deleteFreeLines(FactureFournisseur $invoice, User $user)
	{
		if ($invoice->fetch_lines() < 0) {
			return -1;
		}

		$deleted = 0;
		foreach ($invoice->lines as $line) {
			if (!self::isFreeInvoiceLine($line)) {
				continue;
			}
			$result = $invoice->deleteLine($line->id);
			if ($result < 0) {
				return -1;
			}
			$deleted++;
		}

		return $deleted;
	}

	/**
	 * Replace the free lines of the invoice with the selected supplier-order lines, identically
	 * to creating a supplier invoice from a supplier order and ticking those lines.
	 *
	 * The selected lines must belong to orders of the same vendor, in an eligible status. Extrafields
	 * of the order line (Wrike project, analytical code, ...) are copied onto the invoice line when
	 * the same extrafields exist on facture_fourn_det. Each distinct order is linked to the invoice.
	 *
	 * @param	FactureFournisseur	$invoice		Draft received e-invoice
	 * @param	User				$user			User performing the import
	 * @param	int[]				$orderLineIds	Ids of commande_fournisseurdet rows
	 * @return	int									Number of lines imported, or <0 on error (see $invoice->error / $invoice->errors)
	 */
	public static function importSelectedLines(FactureFournisseur $invoice, User $user, array $orderLineIds)
	{
		global $db, $conf, $langs;

		if (!self::isEligibleInvoice($invoice)) {
			$invoice->error = $langs->trans('SupplierOrderLineImportNotEligible');
			$invoice->errors[] = $invoice->error;
			return -1;
		}

		$ids = array();
		foreach ($orderLineIds as $orderLineId) {
			$id = (int) $orderLineId;
			if ($id > 0) {
				$ids[$id] = $id;
			}
		}
		if (empty($ids)) {
			$invoice->error = $langs->trans('NoSupplierOrderLineSelected');
			$invoice->errors[] = $invoice->error;
			return -1;
		}

		$invoice->fetch_thirdparty();

		$db->begin();

		$deleted = self::deleteFreeLines($invoice, $user);
		if ($deleted < 0) {
			$db->rollback();
			return -1;
		}

		$sql = "SELECT cd.rowid, cd.fk_commande, c.fk_soc, c.fk_statut";
		$sql .= " FROM ".$db->prefix()."commande_fournisseurdet as cd";
		$sql .= " INNER JOIN ".$db->prefix()."commande_fournisseur as c ON c.rowid = cd.fk_commande";
		$sql .= " WHERE cd.rowid IN (".implode(',', array_map('intval', $ids)).")";
		$sql .= " AND c.entity IN (".getEntity('supplier_order').")";

		$resql = $db->query($sql);
		if (!$resql) {
			$invoice->error = $db->lasterror();
			$invoice->errors[] = $invoice->error;
			$db->rollback();
			return -1;
		}

		$byOrder = array();
		$eligibleStatuses = self::eligibleOrderStatuses();
		while ($obj = $db->fetch_object($resql)) {
			if ((int) $obj->fk_soc != (int) $invoice->socid) {
				$db->free($resql);
				$invoice->error = $langs->trans('SupplierOrderLineImportWrongSupplier');
				$invoice->errors[] = $invoice->error;
				$db->rollback();
				return -1;
			}
			if (!in_array((int) $obj->fk_statut, $eligibleStatuses, true)) {
				$db->free($resql);
				$invoice->error = $langs->trans('SupplierOrderLineImportWrongStatus');
				$invoice->errors[] = $invoice->error;
				$db->rollback();
				return -1;
			}
			$byOrder[(int) $obj->fk_commande][] = (int) $obj->rowid;
		}
		$db->free($resql);

		if (empty($byOrder)) {
			$invoice->error = $langs->trans('NoSupplierOrderLineSelected');
			$invoice->errors[] = $invoice->error;
			$db->rollback();
			return -1;
		}

		$imported = 0;
		foreach ($byOrder as $orderId => $lineIdsOfOrder) {
			$srcobject = new CommandeFournisseur($db);
			if ($srcobject->fetch($orderId) <= 0) {
				$invoice->error = $srcobject->error;
				$invoice->errors = array_merge((array) $invoice->errors, (array) $srcobject->errors);
				$db->rollback();
				return -1;
			}
			if (empty($srcobject->lines) && method_exists($srcobject, 'fetch_lines')) {
				$srcobject->fetch_lines();
			}

			$lineIdsOfOrder = array_flip($lineIdsOfOrder);
			foreach ($srcobject->lines as $line) {
				if (!isset($lineIdsOfOrder[(int) $line->id])) {
					continue;
				}

				$result = self::addInvoiceLineFromOrderLine($invoice, $line);
				if ($result < 0) {
					$db->rollback();
					return -1;
				}
				$imported++;
			}

			$invoice->fetchObjectLinked();
			$alreadyLinked = false;
			if (!empty($invoice->linkedObjectsIds['order_supplier'])) {
				$alreadyLinked = in_array($orderId, $invoice->linkedObjectsIds['order_supplier'], false);
			}
			if (!$alreadyLinked) {
				$linkResult = $invoice->add_object_linked('order_supplier', $orderId);
				if ($linkResult < 0) {
					$db->rollback();
					return -1;
				}
			}
		}

		$db->commit();

		$invoice->fetch($invoice->id);
		$invoice->fetch_lines();

		return $imported;
	}

	/**
	 * Add one supplier-order line onto a supplier invoice, matching fourn/facture/card.php when
	 * the invoice is created from an order with a selection of lines.
	 *
	 * @param	FactureFournisseur			$invoice	Target draft invoice
	 * @param	CommandeFournisseurLigne	$line		Source order line
	 * @return	int										>0 if OK, <0 if KO
	 */
	public static function addInvoiceLineFromOrderLine(FactureFournisseur $invoice, $line)
	{
		global $conf;

		$desc = ($line->desc ? $line->desc : $line->product_label);
		$product_type = ($line->product_type ? $line->product_type : 0);

		if (method_exists($line, 'fetch_optionals')) {
			$line->fetch_optionals();
		}

		$date_start = 0;
		$date_end = 0;
		if (!empty($line->date_debut_prevue)) {
			$date_start = $line->date_debut_prevue;
		}
		if (!empty($line->date_debut_reel)) {
			$date_start = $line->date_debut_reel;
		}
		if (!empty($line->date_start)) {
			$date_start = $line->date_start;
		}
		if (!empty($line->date_fin_prevue)) {
			$date_end = $line->date_fin_prevue;
		}
		if (!empty($line->date_fin_reel)) {
			$date_end = $line->date_fin_reel;
		}
		if (!empty($line->date_end)) {
			$date_end = $line->date_end;
		}

		$tva_tx = $line->tva_tx;
		if (!empty($line->vat_src_code) && !preg_match('/\(/', (string) $tva_tx)) {
			$tva_tx .= ' ('.$line->vat_src_code.')';
		}

		if ($invoice->multicurrency_code != $conf->currency || $invoice->multicurrency_tx != 1) {
			$pu = 0;
			$pu_currency = $line->multicurrency_subprice;
		} else {
			$pu = $line->subprice;
			$pu_currency = 0;
		}

		$array_options = (!empty($line->array_options) && is_array($line->array_options)) ? $line->array_options : array();

		$result = $invoice->addline(
			$desc,
			$pu,
			$tva_tx,
			$line->localtax1_tx,
			$line->localtax2_tx,
			$line->qty,
			$line->fk_product,
			$line->remise_percent,
			(int) $date_start,
			(int) $date_end,
			0,
			$line->info_bits,
			'HT',
			$product_type,
			isset($line->rang) ? $line->rang : -1,
			0,
			$array_options,
			$line->fk_unit,
			$line->id,
			$pu_currency,
			$line->ref_supplier,
			isset($line->special_code) ? $line->special_code : 0
		);

		return $result;
	}
}
