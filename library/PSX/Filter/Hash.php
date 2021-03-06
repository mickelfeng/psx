<?php
/*
 *  $Id: Md5.php 480 2012-05-01 18:13:54Z k42b3.x@googlemail.com $
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

namespace PSX\Filter;

use InvalidArgumentException;
use PSX\FilterAbstract;

/**
 * PSX_Filter_Md5
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Filter
 * @version    $Revision: 480 $
 */
class Hash extends FilterAbstract
{
	protected $algo;

	public function __construct($algo = 'sha1')
	{
		if(in_array($algo, hash_algos()))
		{
			$this->algo = $algo;
		}
		else
		{
			throw new InvalidArgumentException('Unsupported hash algorithm');
		}
	}

	/**
	 * Returns an representation of $value depending on the selected algorithm
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	public function apply($value)
	{
		return hash($this->algo, (string) $value);
	}
}
