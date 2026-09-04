<?php
/* Copyright (C) 2026
 *
 * This program is free software; you can redistribute it and/or modify
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * or see https://www.gnu.org/
 */

/**
 *      \file       test/phpunit/SupplierOrderLineImporterTest.php
 *      \ingroup    test
 *      \brief      PHPUnit test for replacing the free lines of a received supplier e-invoice
 *                  with supplier-order lines, and for the three-total check that gates validation.
 *      \remarks    To run this script as CLI: phpunit filename.php
 */

global $conf, $user, $langs, $db;

$dolibarrHtdocs = getenv('DOLIBARR_HTDOCS');
if (!$dolibarrHtdocs) {
	$dolibarrHtdocs = dirname(__FILE__) . '/../../htdocs';
}
if (!file_exists($dolibarrHtdocs . '/master.inc.php')) {
	throw new \RuntimeException('Could not locate master.inc.php under "' . $dolibarrHtdocs . '/". Set the environment variable (export DOLIBARR_HTDOCS=...) to the htdocs directory of the Dolibarr instance to test against.');
}

require_once $dolibarrHtdocs . '/master.inc.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.commande.class.php';
dol_include_once('einvoicing/class/utils/SupplierInvoiceHelper.class.php');
dol_include_once('einvoicing/class/utils/SupplierOrderLineImporter.class.php');
require_once __DIR__ . '/CommonClassTestCompat.inc.php';

$langs->load('einvoicing@einvoicing');

/**
 * Class for PHPUnit tests
 *
 * @backupGlobals disabled
 * @backupStaticAttributes enabled
 */
class SupplierOrderLineImporterTest extends CommonClassTest
{
	/**
	 * Matching HT, VAT and TTC are accepted.
	 *
	 * @return void
	 */
	public function testCompareThreeTotalsIdentical()
	{
		$result = SupplierInvoiceHelper::compareThreeTotals(100.00, 20.00, 120.00, 100.00, 20.00, 120.00);
		$this->assertFalse($result['unavailable']);
		$this->assertTrue($result['identical']);
		$this->assertEmpty($result['errors']);
	}

	/**
	 * Rounding to MAIN_MAX_DECIMALS_TOT must not turn 100.001 into a mismatch with 100.00.
	 *
	 * @return void
	 */
	public function testCompareThreeTotalsRoundsToInvoicePrecision()
	{
		global $conf;

		$conf->global->MAIN_MAX_DECIMALS_TOT = 2;
		$result = SupplierInvoiceHelper::compareThreeTotals(100.001, 20.00, 120.00, 100.00, 20.00, 120.00);
		$this->assertTrue($result['identical'], 'Amounts that round to the same total must match');
	}

	/**
	 * A difference on any of the three totals is a mismatch.
	 *
	 * @return void
	 */
	public function testCompareThreeTotalsDetectsEachTotal()
	{
		$ht = SupplierInvoiceHelper::compareThreeTotals(99.00, 20.00, 120.00, 100.00, 20.00, 120.00);
		$this->assertFalse($ht['identical']);
		$this->assertNotEmpty($ht['errors']);

		$vat = SupplierInvoiceHelper::compareThreeTotals(100.00, 19.00, 120.00, 100.00, 20.00, 120.00);
		$this->assertFalse($vat['identical']);

		$ttc = SupplierInvoiceHelper::compareThreeTotals(100.00, 20.00, 119.00, 100.00, 20.00, 120.00);
		$this->assertFalse($ttc['identical']);
	}

	/**
	 * A credit note stores negative totals in Dolibarr and positive ones on the Access Point.
	 *
	 * @return void
	 */
	public function testCompareThreeTotalsCreditNoteUsesAbsoluteValue()
	{
		$result = SupplierInvoiceHelper::compareThreeTotals(-100.00, -20.00, -120.00, 100.00, 20.00, 120.00, true);
		$this->assertTrue($result['identical']);
	}

	/**
	 * A free line is a line with no product, not a subtotal and not a deposit.
	 *
	 * @return void
	 */
	public function testIsFreeInvoiceLine()
	{
		$free = new stdClass();
		$free->fk_product = 0;
		$free->product_type = 0;
		$free->fk_remise_except = 0;
		$this->assertTrue(SupplierOrderLineImporter::isFreeInvoiceLine($free));

		$product = clone $free;
		$product->fk_product = 12;
		$this->assertFalse(SupplierOrderLineImporter::isFreeInvoiceLine($product));

		$subtotal = clone $free;
		$subtotal->product_type = 9;
		$this->assertFalse(SupplierOrderLineImporter::isFreeInvoiceLine($subtotal));

		$deposit = clone $free;
		$deposit->fk_remise_except = 4;
		$this->assertFalse(SupplierOrderLineImporter::isFreeInvoiceLine($deposit));
	}

	/**
	 * Eligible supplier orders are those already sent to the vendor, including received ones.
	 *
	 * @return void
	 */
	public function testEligibleOrderStatuses()
	{
		$statuses = SupplierOrderLineImporter::eligibleOrderStatuses();
		$this->assertContains(CommandeFournisseur::STATUS_ORDERSENT, $statuses);
		$this->assertContains(CommandeFournisseur::STATUS_RECEIVED_PARTIALLY, $statuses);
		$this->assertContains(CommandeFournisseur::STATUS_RECEIVED_COMPLETELY, $statuses);
		$this->assertNotContains(CommandeFournisseur::STATUS_DRAFT, $statuses);
		$this->assertNotContains(CommandeFournisseur::STATUS_CANCELED, $statuses);
	}

	/**
	 * The feature stays off until the setup option is enabled.
	 *
	 * @return void
	 */
	public function testFeatureDisabledByDefault()
	{
		global $conf;

		unset($conf->global->EINVOICING_IMPORT_SUPPLIER_ORDER_LINES);
		$this->assertFalse(SupplierOrderLineImporter::isEnabled());

		$conf->global->EINVOICING_IMPORT_SUPPLIER_ORDER_LINES = 1;
		$this->assertTrue(SupplierOrderLineImporter::isEnabled());
		unset($conf->global->EINVOICING_IMPORT_SUPPLIER_ORDER_LINES);
	}

	/**
	 * A non-draft invoice is never eligible.
	 *
	 * @return void
	 */
	public function testValidatedInvoiceIsNotEligible()
	{
		global $conf, $db;

		$conf->global->EINVOICING_IMPORT_SUPPLIER_ORDER_LINES = 1;
		$invoice = new FactureFournisseur($db);
		$invoice->id = 1;
		$invoice->element = 'invoice_supplier';
		$invoice->status = FactureFournisseur::STATUS_VALIDATED;
		$invoice->statut = FactureFournisseur::STATUS_VALIDATED;
		$this->assertFalse(SupplierOrderLineImporter::isEligibleInvoice($invoice));
		unset($conf->global->EINVOICING_IMPORT_SUPPLIER_ORDER_LINES);
	}
}
