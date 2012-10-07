<?php
/*
 *  $Id: Base.php 661 2012-10-07 16:31:07Z k42b3.x@googlemail.com $
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
 * PSX_Base
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Base
 * @version    $Revision: 661 $
 */
class PSX_Base
{
	const VERSION = '0.4.7';

	/**
	 * The current request method
	 *
	 * @var string
	 */
	private static $requestMethod;

	/**
	 * Indicates whether the method was overwritten or not
	 *
	 * @var boolean
	 */
	private static $methodOverride = false;

	/**
	 * Caches the request header if the getRequestHeader method is called
	 *
	 * @var array
	 */
	private static $headers;

	/**
	 * Contains the raw request
	 *
	 * @var string
	 */
	private static $rawInput;

	/**
	 * Contains the instance
	 *
	 * @var PSX_Base
	 */
	private static $instance;

	/**
	 * Contains the absolute url to the script using the psx_url from the
	 * configuration
	 *
	 * @var string
	 */
	protected $self;

	/**
	 * The host of the value of psx_url
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * Contains the current http request object
	 *
	 * @var PSX_Http_Request
	 */
	protected $request;

	protected $config;
	protected $loader;

	private function __construct(PSX_Config $config)
	{
		// set config
		$this->config = $config;

		// assign the host
		$url = $this->config['psx_url'];

		if(!empty($url))
		{
			$parts = parse_url($url);

			if($parts !== false && isset($parts['scheme']) && isset($parts['host']))
			{
				$this->self = $parts['scheme'] . '://' . $parts['host'] . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
				$this->host = $parts['host'];
			}
			else
			{
				throw new PSX_Base_Exception('Invalid PSX url');
			}
		}
		else
		{
			throw new PSX_Base_Exception('PSX url not set');
		}

		// call global setup
		$this->setup();
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function getLoader()
	{
		return $this->loader;
	}

	public function setup()
	{
		$this->loader = new PSX_Loader($this);
	}

	/**
	 * Returns the absolute url of the current requested url
	 *
	 * @return string
	 */
	public function getSelf()
	{
		return $this->self;
	}

	/**
	 * Returns the host name of the url
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Generates an urn in the psx namespace for this host
	 *
	 * @return string
	 */
	public function getUrn()
	{
		return PSX_Urn::buildUrn(array_merge(array('psx', $this->host), func_get_args()));
	}

	/**
	 * Generates an tag uri based on the host
	 *
	 * @return string
	 */
	public function getTag(DateTime $date, $specific)
	{
		return PSX_Uri::buildTag($this->host, $date, $specific);
	}

	/**
	 * Generates an Name-Based UUID where the namespace is the host of this
	 * domain
	 *
	 * @return string
	 */
	public function getUUID($name)
	{
		return PSX_Util_Uuid::nameBased($this->host . $name);
	}

	/**
	 * Returns the current http request
	 *
	 * @return PSX_Http_Request
	 */
	public function getRequest()
	{
		if($this->request === null)
		{
			$url    = new PSX_Url($this->self);
			$method = self::getRequestMethod();
			$header = self::getRequestHeader();
			$body   = self::getRawInput();

			$this->request = new PSX_Http_Request($url, $method, $header, $body);
		}

		return $this->request;
	}

	public static function initInstance(PSX_Config $config)
	{
		return self::$instance = new static($config);
	}

	public static function getInstance()
	{
		return self::$instance;
	}

	/**
	 * Tries to detect the current request method. It considers the
	 * X-HTTP-METHOD-OVERRIDE header.
	 *
	 * @return string
	 */
	public static function getRequestMethod()
	{
		if(self::$requestMethod === null)
		{
			if(isset($_SERVER['REQUEST_METHOD']))
			{
				// check for X-HTTP-Method-Override
				if(isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) && in_array($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'], array('OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'TRACE', 'CONNECT')))
				{
					self::$methodOverride = true;
					self::$requestMethod  = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
				}
				else
				{
					self::$methodOverride = false;
					self::$requestMethod  = $_SERVER['REQUEST_METHOD'];
				}
			}
			else
			{
				self::$requestMethod = 'GET';
			}
		}

		return self::$requestMethod;
	}

	/**
	 * Tells whether the method was overwriten by an X-HTTP-METHOD-OVERRIDE
	 * header or not
	 *
	 * @return boolean
	 */
	public static function isOverride()
	{
		return self::$methodOverride;
	}

	/**
	 * Returns all or a specific request header. Note if you get all request
	 * headers as array all keys are lowercase since the specification says that
	 * http headers are case-insensitive so you dont have to search the complete
	 * array instead you can acces it with the key i.e.:
	 * <code>
	 * $headers = PSX_Base::getRequestHeader();
	 *
	 * echo $headers['Content-Type'];
	 * </code>
	 *
	 * If the parameter $key is defined it searches for the specific header and
	 * returns the value if its exists else false
	 *
	 * @param string $key
	 * @return string|array
	 */
	public static function getRequestHeader($key = false)
	{
		if(self::$headers === null)
		{
			if(function_exists('apache_request_headers'))
			{
				$headers = apache_request_headers();

				foreach($headers as $k => $v)
				{
					$k = strtolower($k);

					self::$headers[$k] = $v;
				}
			}
			else
			{
				foreach($_SERVER as $k => $v)
				{
					if(substr($k, 0, 5) == 'HTTP_')
					{
						$k = str_replace('_', '-', strtolower(substr($k, 5)));

						self::$headers[$k] = $v;
					}
				}
			}
		}

		if($key === false)
		{
			return self::$headers;
		}
		else
		{
			$key = strtolower($key);

			return isset(self::$headers[$key]) ? self::$headers[$key] : false;
		}
	}

	/**
	 * Returns the raw input of the current request. Caches the request so
	 * multiple calls will only read onces the input
	 *
	 * @return string
	 */
	public static function getRawInput()
	{
		if(self::$rawInput === null)
		{
			self::$rawInput = file_get_contents('php://input');
		}

		return self::$rawInput;
	}

	/**
	 * Checks whether an specific header was already send
	 *
	 * @return boolean
	 */
	public static function hasHeaderSent($key)
	{
		$key     = strtolower($key);
		$headers = headers_list();

		foreach($headers as $header)
		{
			if(strtolower(substr($header, 0, strpos($header, ':'))) === $key)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the supported http version by the underlying webserver. If no
	 * protocol is found "HTTP/1.0" is returned
	 *
	 * @return string
	 */
	public static function getProtocol()
	{
		return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
	}

	/**
	 * Sets the http response code
	 *
	 * @return void
	 */
	public static function setResponseCode($code)
	{
		if(isset(PSX_Http::$codes[$code]))
		{
			header(self::getProtocol() . ' ' . $code . ' ' . PSX_Http::$codes[$code]);
		}
		else
		{
			throw new PSX_Exception('Invalid http code');
		}
	}

	/**
	 * Returns the version of the framework
	 *
	 * @return string
	 */
	public static function getVersion()
	{
		return self::VERSION;
	}
}
