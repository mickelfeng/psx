<?php
/*
 *  $Id: ClientCredentials.php 662 2012-10-07 16:45:03Z k42b3.x@googlemail.com $
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

namespace PSX\Oauth2\Authorization;

use PSX\Base;
use PSX\Oauth2\AuthorizationAbstract;

/**
 * PSX_Oauth2_Authorization_ClientCredentials
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Oauth2
 * @version    $Revision: 662 $
 */
class ClientCredentials extends AuthorizationAbstract
{
	public function getAccessToken()
	{
		// request data
		$data = array(

			'grant_type' => 'client_credentials',

		);

		// authentication
		$header = array(
			'Accept'     => 'application/json',
			'User-Agent' => __CLASS__ . ' ' . Base::VERSION,
		);

		if($this->type == self::AUTH_BASIC)
		{
			$header['Authorization'] = 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret);
		}

		if($this->type == self::AUTH_POST)
		{
			$data['client_id']     = $this->clientId;
			$data['client_secret'] = $this->clientSecret;
		}

		// send request
		return $this->request($header, $data);
	}
}
