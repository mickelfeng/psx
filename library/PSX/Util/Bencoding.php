<?php
/*
 *  $Id: Bencoding.php 480 2012-05-01 18:13:54Z k42b3.x@googlemail.com $
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

namespace PSX\Util;

use InvalidArgumentException;

/**
 * PSX_Util_Bencoding
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Util
 * @version    $Revision: 480 $
 */
class Bencoding
{
	public static function encode($value)
	{
		$type = gettype($value);
		$out  = '';

		switch($type)
		{
			case 'integer':

				$out.= 'i' . $value . 'e';

				break;

			case 'string':

				$out.= strlen($value) . ':' . utf8_encode($value);

				break;

			case 'array':

				if(!self::isAssociative($value))
				{
					$out.= 'l';

					foreach($value as $entry)
					{
						$out.= self::encode($entry);
					}

					$out.= 'e';
				}
				else
				{
					$out.= 'd';

					foreach($value as $key => $entry)
					{
						$out.= self::encode($key) . self::encode($entry);
					}

					$out.= 'e';
				}

				break;

			default:

				throw new InvalidArgumentException('Type must be integer / string or array');
				break;
		}

		return $out;
	}

	public static function decode($value)
	{
		list($v, $r) = self::recDecode($value);

		return $v;
	}

	private static function recDecode($value)
	{
		switch($value[0])
		{
			# list
			case 'l':

				$value = substr($value, 1, -1);
				$out   = array();

				while(!empty($value))
				{
					list($v, $r) = self::recDecode($value);

					$value = $r;

					if(!empty($v))
					{
						$out[] = $v;
					}
				}

				return array($out, false);

				break;

			# dictonary
			case 'd':

				$value = substr($value, 1, -1);
				$out   = array();

				while(!empty($value))
				{
					list($k, $r) = self::recDecode($value);

					$value = $r;

					list($v, $r) = self::recDecode($value);

					$value = $r;

					if(!empty($k) && !empty($v))
					{
						$out[$k] = $v;
					}
				}

				return array($out, false);

				break;

			# integer
			case 'i':

				return self::decodeInt($value);

				break;

			# string
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':

				return self::decodeStr($value);

				break;

			default:

				return false;
		}
	}

	private static function decodeInt($value)
	{
		if(isset($value[0]) && $value[0] == 'i')
		{
			$i      = 1;
			$length = '';

			while($value[$i] != 'e')
			{
				$length.= $value[$i];

				$i++;
			}

			$result = intval($length);
			$value  = substr($value, strlen($length) + 2);

			return array($result, $value);
		}

		return array(false, false);
	}

	private static function decodeStr($value)
	{
		if(is_numeric($value[0]))
		{
			$i      = 0;
			$length = '';

			while($value[$i] != ':')
			{
				$length.= $value[$i];

				$i++;
			}

			$length = intval($length);
			$result = substr($value, $i + 1, $length);
			$value  = substr($value, strlen($length) + 1 + $length);

			return array($result, $value);
		}

		return array(false, false);
	}

	private static function isAssociative($array)
	{
		for($i = 0; $i < count($array); $i++)
		{
			if(!isset($array[$i]))
			{
				return true;
			}
		}

		return false;
	}
}