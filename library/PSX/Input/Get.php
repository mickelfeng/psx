<?php
/*
 *  $Id: Get.php 622 2012-08-25 11:18:20Z k42b3.x@googlemail.com $
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

namespace PSX\Input;

use PSX\Input;
use PSX\Validate;

/**
 * A class to get the $_GET variables. Here a short example how to get values.
 * <code>
 * $get = new PSX_Input_Get();
 *
 * // returns (integer) $_GET['foo'] or false if foo ist not set
 * $bar = $get->foo('integer');
 *
 * // foo must be an integer value >= 3 and <= 64
 * $bar = $get->foo('integer', array(new PSX_Filter_Length(3, 64)));
 *
 * // returns an md5 representation of foo if it contains only alphanumeric
 * // signs else it returns false
 * $bar = $get->foo('string', array(new PSX_Filter_Alnum(), new PSX_Filter_Md5()));
 * </code>
 *
 * If the $_GET variable id is available it contains an integer representation
 * else false
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Input
 * @version    $Revision: 622 $
 */
class Get extends Input
{
	public function __construct(Validate $validate = null)
	{
		parent::__construct($_GET, $validate);
	}
}

