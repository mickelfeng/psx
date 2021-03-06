<?php
/*
 *  $Id: LoaderTest.php 658 2012-10-06 22:39:32Z k42b3.x@googlemail.com $
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

use ReflectionClass;
use ReflectionMethod;
use PSX\Dependency;
use PSX\Loader\Location;
use PSX\Loader\LocationFinder\FileSystem;

/**
 * PSX_LoaderTest
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   tests
 * @version    $Revision: 658 $
 */
class LoaderTest extends \PHPUnit_Framework_TestCase
{
	protected $loader;
	protected $path = 'tests/PSX/Loader/module';

	protected function setUp()
	{
		$container = new Dependency\Request(getConfig());

		$loader = new PublicLoader($container->getBase());
		$loader->setLocationFinder($this->getLocationFinder());
		$loader->setDefault('foo');

		$this->loader = $loader;
	}

	protected function tearDown()
	{
		unset($this->loader);
	}

	protected function getLocationFinder()
	{
		return new FileSystem($this->path);
	}

	/**
	 * The resolvePath method returns an array with the following values:
	 *
	 * <code>
	 * array(
	 * 	$location, // the location object returned by the location finder
	 * 	$method, // the method wich is called or false
	 * 	$uriFragments, // the uri fragments as array
	 * )
	 * </code>
	 */
	public function testResolvePath()
	{
		list($location, $method, $uriFragments) = $this->loader->resolvePathPublic('foo');

		$this->assertEquals(true, $location instanceof Location);
		$this->assertEquals(true, $location->getId() != "");
		$this->assertEquals('', $location->getPath());
		$this->assertEquals(true, $location->getClass() instanceof ReflectionClass);
		$this->assertEquals('foo', $location->getClass()->getName());
		$this->assertEquals(false, $method);
		$this->assertEquals(array(), $uriFragments);

		list($location, $method, $uriFragments) = $this->loader->resolvePathPublic('foo/test');

		$this->assertEquals(true, $location instanceof Location);
		$this->assertEquals(true, $location->getId() != "");
		$this->assertEquals('test', $location->getPath());
		$this->assertEquals(true, $location->getClass() instanceof ReflectionClass);
		$this->assertEquals('foo', $location->getClass()->getName());
		$this->assertEquals(true, $method instanceof ReflectionMethod);
		$this->assertEquals('test', $method->getName());
		$this->assertEquals(array('foo' => null), $uriFragments);

		list($location, $method, $uriFragments) = $this->loader->resolvePathPublic('foo/test/bar');

		$this->assertEquals(true, $location instanceof Location);
		$this->assertEquals(true, $location->getId() != "");
		$this->assertEquals('test/bar', $location->getPath());
		$this->assertEquals(true, $location->getClass() instanceof ReflectionClass);
		$this->assertEquals('foo', $location->getClass()->getName());
		$this->assertEquals(true, $method instanceof ReflectionMethod);
		$this->assertEquals('test', $method->getName());
		$this->assertEquals(array('foo' => 'bar'), $uriFragments);
	}

	public function testCustomRoutes()
	{
		$this->loader->addRoute('.host-meta/well-known', 'foo');

		list($location, $method, $uriFragments) = $this->loader->resolvePathPublic('.host-meta/well-known');

		$this->assertEquals(true, $location instanceof Location);
		$this->assertEquals(true, $location->getId() != "");
		$this->assertEquals('', $location->getPath());
		$this->assertEquals(true, $location->getClass() instanceof ReflectionClass);
		$this->assertEquals('foo', $location->getClass()->getName());
		$this->assertEquals(false, $method);
		$this->assertEquals(array(), $uriFragments);
	}
}

class PublicLoader extends Loader
{
	public function resolvePathPublic($x)
	{
		return $this->resolvePath($x);
	}
}
