<?php
/*
 *  $Id: PutRequestTest.php 579 2012-08-14 18:22:10Z k42b3.x@googlemail.com $
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

/**
 * PSX_Http_PutRequestTest
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   tests
 * @version    $Revision: 579 $
 */
class PSX_Http_PutRequestTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$handler = new PSX_Http_Handler_Curl();

		$this->http = new PSX_Http($handler);
	}

	protected function tearDown()
	{
	}

	public function testPutRequest()
	{
		$request  = new PSX_Http_PutRequest(new PSX_Url(PSX_HttpTest::URL . '/put'));
		$response = $this->http->request($request);

		$this->assertEquals('HTTP/1.1', $response->getScheme());
		$this->assertEquals(200, $response->getCode());
		$this->assertEquals('OK', $response->getMessage());
		$this->assertEquals('SUCCESS', $response->getBody());
	}

	/**
	 * @expectedException PSX_Url_Exception
	 */
	public function testWrongUrl()
	{
		new PSX_Http_PutRequest('foobar');
	}
}

