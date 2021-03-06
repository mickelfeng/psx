<?php
/*
 *  $Id: PagingTest.php 635 2012-08-25 12:55:31Z k42b3.x@googlemail.com $
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

namespace PSX\Html;

use PSX\Url;
use PSX\Data\ResultSet;
use PSX\Html\Paging;

/**
 * PSX_Html_PagingTest
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   tests
 * @version    $Revision: 635 $
 */
class PagingTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}

	public function testPagingUrls()
	{
		$url    = new Url('http://foobar.com');
		$result = new ResultSet(12, 0, 4, array());
		$paging = new Paging($url, $result);

		$this->assertEquals('http://foobar.com?startIndex=0', $paging->getFirstUrl());
		$this->assertEquals('http://foobar.com?startIndex=0', $paging->getPrevUrl());
		$this->assertEquals('http://foobar.com?startIndex=4', $paging->getNextUrl());
		$this->assertEquals('http://foobar.com?startIndex=8', $paging->getLastUrl());


		$url    = new Url('http://foobar.com');
		$result = new ResultSet(32, 16, 4, array());
		$paging = new Paging($url, $result);

		$this->assertEquals('http://foobar.com?startIndex=0', $paging->getFirstUrl());
		$this->assertEquals('http://foobar.com?startIndex=12', $paging->getPrevUrl());
		$this->assertEquals('http://foobar.com?startIndex=20', $paging->getNextUrl());
		$this->assertEquals('http://foobar.com?startIndex=28', $paging->getLastUrl());
	}

	public function testPagingIterator()
	{
		$url    = new Url('http://foobar.com');
		$result = new ResultSet(12, 0, 4, array());
		$paging = new Paging($url, $result);

		$this->assertEquals(1, $paging->getPage());
		$this->assertEquals(3, $paging->getPages());
		$this->assertEquals(0, $paging->getFirst());
		$this->assertEquals(0, $paging->getPrev());
		$this->assertEquals(0, $paging->getMin());
		$this->assertEquals(2, $paging->getMax());
		$this->assertEquals(1, $paging->getNext());
		$this->assertEquals(2, $paging->getLast());

		// countable
		$this->assertEquals(3, count($paging));

		// iterator
		$paging->rewind();

		$this->assertEquals(true, $paging->valid());
		$this->assertEquals(array('name' => 1, 'current' => true, 'href' => 'http://foobar.com?startIndex=0'), $paging->current());
		$this->assertEquals(0, $paging->key());

		$paging->next();

		$this->assertEquals(true, $paging->valid());
		$this->assertEquals(array('name' => 2, 'current' => false, 'href' => 'http://foobar.com?startIndex=4'), $paging->current());
		$this->assertEquals(1, $paging->key());

		$paging->next();

		$this->assertEquals(true, $paging->valid());
		$this->assertEquals(array('name' => 3, 'current' => false, 'href' => 'http://foobar.com?startIndex=8'), $paging->current());
		$this->assertEquals(2, $paging->key());

		foreach($paging as $key => $page)
		{
		}
	}
}

