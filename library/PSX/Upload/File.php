<?php
/*
 *  $Id: File.php 513 2012-06-09 10:03:38Z k42b3.x@googlemail.com $
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
 * PSX_Upload_File
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Upload
 * @version    $Revision: 513 $
 */
class PSX_Upload_File
{
	private $name;
	private $type;
	private $size;
	private $tmpName;
	private $error;

	private $_tmpFile;
	private $_tmpContent;

	public function __construct(array $file)
	{
		if($this->isValidUpload($file))
		{
			$this->name    = isset($file['name'])     ? $file['name']     : null;
			$this->type    = isset($file['type'])     ? $file['type']     : null;
			$this->size    = isset($file['size'])     ? $file['size']     : null;
			$this->tmpName = isset($file['tmp_name']) ? $file['tmp_name'] : null;
			$this->error   = isset($file['error'])    ? $file['error']    : null;
		}
		else
		{
			throw new PSX_Upload_Exception('File was not uploaded');
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getTmpName()
	{
		return $this->tmpName;
	}

	public function getError()
	{
		return $this->error;
	}

	public function move($path)
	{
		return move_uploaded_file($this->tmpName, $path);
	}

	public function getTmpSize()
	{
		return filesize($this->tmpName);
	}

	public function getTmpFile($mode = 'a')
	{
		if($this->_tmpFile === null)
		{
			$this->_tmpFile = PSX_File::open($this->tmpName, $mode);
		}

		return $this->_tmpFile;
	}

	public function getTmpContent()
	{
		if($this->_tmpContent === null)
		{
			$this->_tmpContent = PSX_File::getContents($this->tmpName);
		}

		return $this->_tmpContent;
	}

	public function __toString()
	{
		return $this->getTmpContent();
	}

	private function isValidUpload(array $file)
	{
		$headers = PSX_Base::getRequestHeader();

		if(strpos($headers['content-type'], 'multipart/form-data') !== false)
		{
			switch($file['error'])
			{
				case UPLOAD_ERR_OK:

					return is_uploaded_file($file['tmp_name']);

					break;

				case UPLOAD_ERR_INI_SIZE:

					throw new PSX_Upload_Exception('The uploaded file exceeds the upload_max_filesize directive in php.ini');

					break;

				case UPLOAD_ERR_FORM_SIZE:

					throw new PSX_Upload_Exception('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');

					break;

				case UPLOAD_ERR_PARTIAL:

					throw new PSX_Upload_Exception('The uploaded file was only partially uploaded');

					break;

				case UPLOAD_ERR_NO_FILE:

					throw new PSX_Upload_Exception('No file was uploaded');

					break;

				case UPLOAD_ERR_NO_TMP_DIR:

					throw new PSX_Upload_Exception('Missing a temporary folder');

					break;

				case UPLOAD_ERR_CANT_WRITE:

					throw new PSX_Upload_Exception('Failed to write file to disk');

					break;

				case UPLOAD_ERR_EXTENSION:

					throw new PSX_Upload_Exception('A PHP extension stopped the file upload');

					break;

				default:

					throw new PSX_Upload_Exception('Invalid error code');

					break;
			}
		}
		else
		{
			throw new PSX_Upload_Exception('Invalid content type');
		}
	}
}

