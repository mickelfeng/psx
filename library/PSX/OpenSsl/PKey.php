<?php
/*
 *  $Id: PKey.php 480 2012-05-01 18:13:54Z k42b3.x@googlemail.com $
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

namespace PSX\OpenSsl;

/**
 * PSX_OpenSsl_PKey
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_OpenSsl
 * @version    $Revision: 480 $
 */
class PKey
{
	private $res;

	public function __construct(array $configargs = array())
	{
		$res = openssl_pkey_new($configargs);

		if($res !== false)
		{
			$this->res = $res;
		}
		else
		{
			throw new Exception('Could not create private key');
		}
	}

	public function free()
	{
		openssl_pkey_free($this->res);
	}

	public function getDetails()
	{
		$details = openssl_pkey_get_details($this->res);

		if($details !== false)
		{
			return $details;
		}
		else
		{
			throw new Exception('Could not get details');
		}
	}

	public function getResource()
	{
		return $this->res;
	}

	public function export(&$out, $passphrase = null, array $configargs = array())
	{
		return openssl_pkey_export($this->res, $out, $passphrase, $configargs);
	}

	public function exportToFile($outfilename, $passphrase = null, array $configargs = array())
	{
		return openssl_pkey_export_to_file($this->res, $outfilename, $passphrase, $configargs);
	}

	public static function getPrivate($key, $passphrase = null)
	{
		$res = openssl_pkey_get_private($key, $passphrase);

		if($res !== false)
		{
			return $res;
		}
		else
		{
			throw new Exception('Could not get private');
		}
	}

	public static function getPublic($certificate)
	{
		$res = openssl_pkey_get_public($certificate);

		if($res !== false)
		{
			return $res;
		}
		else
		{
			throw new Exception('Could not get private');
		}
	}
}
