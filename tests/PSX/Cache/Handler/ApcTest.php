<?php
/*
 *  $Id: ApcTest.php 637 2012-09-01 10:33:34Z k42b3.x@googlemail.com $
 *
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2012 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Cache\Handler;

use PSX\CacheTest;
use PSX\Exception;

/**
 * PSX_Cache_Handler_ApcTest
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   tests
 * @version    $Revision: 637 $
 */
class ApcTest extends CacheTest
{
	protected $table;
	protected $sql;

	protected function setUp()
	{
		parent::setUp();

		try
		{
			if(!function_exists('apc_store'))
			{
				throw new Exception('APC extension is not available');
			}
		}
		catch(Exception $e)
		{
			$this->markTestSkipped($e->getMessage());
		}
	}

	protected function getHandler()
	{
		return new Apc();
	}
}

