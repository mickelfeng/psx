<?php
/*
 *  $Id: CollectionAbstract.php 560 2012-07-29 02:42:22Z k42b3.x@googlemail.com $
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
 * PSX_Html_Filter_CollectionAbstract
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Html
 * @version    $Revision: 560 $
 */
abstract class PSX_Html_Filter_CollectionAbstract extends ArrayObject
{
	protected $container = array();

	public function __construct()
	{
		parent::__construct($this->container);

		$this->loadElements();
	}

	public function add(PSX_Html_Filter_Element $element)
	{
		$this->offsetSet($element->getName(), $element);
	}

	/**
	 * Method wich should add all elements to the collection
	 *
	 * @return void
	 */
	abstract public function loadElements();
}
