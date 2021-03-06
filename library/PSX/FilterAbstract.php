<?php
/*
 *  $Id: FilterAbstract.php 617 2012-08-25 11:16:32Z k42b3.x@googlemail.com $
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
 * The abstract class wich each filter class must extend. If the method apply
 * returns false the validation fails if it returns true the validation is
 * successful else the response value of the filter is returned
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Filter
 * @version    $Revision: 617 $
 */
abstract class FilterAbstract
{
	/**
	 * Applies the current filter to the $value
	 *
	 * @param string $value
	 * @return boolean
	 */
	abstract public function apply($value);

	/**
	 * A filter can overwrite this method so if the validation class applies
	 * the filter and it returns false it gets an error message string
	 *
	 * @return string
	 */
	public function getErrorMsg()
	{
		return null;
	}
}


