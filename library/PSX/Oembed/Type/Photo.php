<?php
/*
 *  $Id: Photo.php 480 2012-05-01 18:13:54Z k42b3.x@googlemail.com $
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

namespace PSX\Oembed\Type;

use PSX\Oembed\TypeAbstract;

/**
 * PSX_Oembed_Type_Photo
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Oembed
 * @version    $Revision: 480 $
 */
class Photo extends TypeAbstract
{
	public $url;
	public $width;
	public $height;

	public function getName()
	{
		return 'photo';
	}

	public function getFields()
	{
		return array_merge(parent::getFields(), array(

			'url'    => $this->url,
			'width'  => $this->width,
			'height' => $this->height,

		));
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function setWidth($width)
	{
		$this->width = (integer) $width;
	}

	public function setHeight($height)
	{
		$this->height = (integer) $height;
	}
}
