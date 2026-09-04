<?php
/* Copyright (C) 2026
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
 *      \file       test/phpunit/SupplierInvoiceFreeLineImportTest.php
 *      \ingroup    test
 *      \brief      PHPUnit test for importing every Access Point supplier-invoice line as a free
 *                  description line (EINVOICING_IMPORT_ALL_AS_FREE_LINES), even when a product
 *                  would match. The existing EINVOICING_IMPORT_AS_FREE_LINES option only applies
 *                  after matching has failed; this one skips matching altogether.
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
dol_include_once('einvoicing/class/protocols/CIIProtocol.class.php');
require_once __DIR__ . '/CommonClassTestCompat.inc.php';

$conf->global->MAIN_DISABLE_ALL_MAILS = 1;

/**
 * Class for PHPUnit tests
 *
 * @backupGlobals disabled
 * @backupStaticAttributes enabled
 * @remarks	backupGlobals must be disabled to have db,conf,user and lang not erased.
 */
class SupplierInvoiceFreeLineImportTest extends CommonClassTest
{
	/** @var array<string,string|null> Constants this test overwrites, as they were before */
	private $savedconstants = array();

	/**
	 * CommonClassTest keeps a reference to $conf, not a copy, so the constants written here would
	 * survive the class and reach whatever runs next. Give them back their initial value.
	 *
	 * @return void
	 */
	protected function tearDown(): void
	{
		global $conf;

		foreach ($this->savedconstants as $key => $value) {
			if ($value === null) {
				unset($conf->global->$key);
			} else {
				$conf->global->$key = $value;
			}
		}
		$this->savedconstants = array();

		parent::tearDown();
	}

	/**
	 * @param	string		$key	Constant name
	 * @param	int|string	$value	Value to set on $conf->global
	 * @return	void
	 */
	private function setConstant($key, $value)
	{
		global $conf;

		if (!array_key_exists($key, $this->savedconstants)) {
			$this->savedconstants[$key] = isset($conf->global->$key) ? $conf->global->$key : null;
		}
		$conf->global->$key = $value;
	}

	/**
	 * @return CIIProtocol
	 */
	private function protocol()
	{
		global $db;

		return new CIIProtocol($db);
	}

	/**
	 * Off by default: matching stays the import path.
	 *
	 * @return void
	 */
	public function testForceFreeLineOffByDefault()
	{
		$this->setConstant('EINVOICING_IMPORT_ALL_AS_FREE_LINES', 0);
		$this->setConstant('EINVOICING_IMPORT_SUPPLIER_ORDER_LINES', 0);

		$this->assertFalse($this->protocol()->shouldForceFreeDescriptionLine());
	}

	/**
	 * EINVOICING_IMPORT_ALL_AS_FREE_LINES skips matching even when a product would match.
	 *
	 * @return void
	 */
	public function testForceFreeLineWhenAllAsFreeLinesIsOn()
	{
		$this->setConstant('EINVOICING_IMPORT_ALL_AS_FREE_LINES', 1);
		$this->setConstant('EINVOICING_IMPORT_SUPPLIER_ORDER_LINES', 0);

		$this->assertTrue($this->protocol()->shouldForceFreeDescriptionLine());
	}

	/**
	 * Replacing received lines with supplier-order lines also starts from free description lines.
	 *
	 * @return void
	 */
	public function testForceFreeLineWhenSupplierOrderLineImportIsOn()
	{
		$this->setConstant('EINVOICING_IMPORT_ALL_AS_FREE_LINES', 0);
		$this->setConstant('EINVOICING_IMPORT_SUPPLIER_ORDER_LINES', 1);

		$this->assertTrue($this->protocol()->shouldForceFreeDescriptionLine());
	}

	/**
	 * The existing "free line when no product is found" option does not skip matching: a product
	 * that matches is still linked.
	 *
	 * @return void
	 */
	public function testImportAsFreeLinesWhenUnmatchedDoesNotSkipMatching()
	{
		$this->setConstant('EINVOICING_IMPORT_ALL_AS_FREE_LINES', 0);
		$this->setConstant('EINVOICING_IMPORT_SUPPLIER_ORDER_LINES', 0);
		$this->setConstant('EINVOICING_IMPORT_AS_FREE_LINES', 1);

		$this->assertFalse($this->protocol()->shouldForceFreeDescriptionLine());
	}

	/**
	 * With EINVOICING_IMPORT_ALL_AS_FREE_LINES, the product resolver returns res=0 (free line)
	 * without looking up a product, including when auto-creation of products is on.
	 *
	 * @return void
	 */
	public function testFindOrCreateProductReturnsFreeLineWhenAllAsFreeLinesIsOn()
	{
		$this->setConstant('EINVOICING_IMPORT_ALL_AS_FREE_LINES', 1);
		$this->setConstant('EINVOICING_IMPORT_SUPPLIER_ORDER_LINES', 0);
		$this->setConstant('EINVOICING_PRODUCTS_AUTO_GENERATION', 1);

		$method = new ReflectionMethod(CIIProtocol::class, '_findOrCreateProductFromEinvoiceLine');
		$method->setAccessible(true);

		$result = $method->invoke($this->protocol(), array(
			'prodsellerid' => 'VENDOR-REF-THAT-WOULD-MATCH',
			'prodname' => 'A product that would match',
			'supplierId' => 1,
		), 'flow-test');

		$this->assertSame(0, $result['res']);
		$this->assertSame('forcedfreeline', $result['matchtype']);
	}
}
