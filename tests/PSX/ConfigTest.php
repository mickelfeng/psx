<?php
/*
 *  $Id: ConfigTest.php 497 2012-06-02 18:42:47Z k42b3.x@googlemail.com $
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

namespace PSX;

/**
 * PSX_ConfigTest
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   tests
 * @version    $Revision: 497 $
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
	private $config;

	protected function setUp()
	{
		$this->config = getConfig();
	}

	protected function tearDown()
	{
		unset($this->config);
	}

	public function testConfigOffsetSet()
	{
		$this->config['foo'] = 'bar';

		$this->assertEquals('bar', $this->config['foo']);
	}

	public function testConfigOffsetExists()
	{
		$this->assertEquals(false, isset($this->config['foobar']));

		$this->config['foobar'] = 'test';

		$this->assertEquals(true, isset($this->config['foobar']));
	}

	public function testConfigOffsetUnset()
	{
		$this->config['bar'] = 'test';

		unset($this->config['bar']);

		$this->assertEquals(true, !isset($this->config['bar']));
	}

	public function testConfigOffsetGet()
	{
		$this->config['bar'] = 'test';

		$this->assertEquals('test', $this->config['bar']);
	}
}




