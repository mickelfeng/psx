<?php
/*
 *  $Id: Json.php 480 2012-05-01 18:13:54Z k42b3.x@googlemail.com $
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

namespace PSX\Data\Writer;

use PSX\Data\RecordInterface;
use PSX\Data\WriterInterface;
use PSX\Data\WriterResult;

/**
 * PSX_Data_Writer_Json
 *
 * @author     Christoph Kappestein <k42b3.x@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GPLv3
 * @link       http://phpsx.org
 * @category   PSX
 * @package    PSX_Data
 * @version    $Revision: 480 $
 */
class Json implements WriterInterface
{
	public static $mime = 'application/json';

	public $writerResult;

	public function write(RecordInterface $record)
	{
		$this->writerResult = new WriterResult(WriterInterface::JSON, $this);

		echo \PSX\Json::encode($this->recJsonEncode($record->export($this->writerResult)));
	}

	protected function recJsonEncode(array $fields)
	{
		$data = array();

		foreach($fields as $k => $v)
		{
			if($v instanceof RecordInterface)
			{
				$data[$k] = $this->recJsonEncode($v->export($this->writerResult));
			}
			else if(is_array($v))
			{
				$data[$k] = $this->recJsonEncode($v);
			}
			else
			{
				$data[$k] = $v;
			}
		}

		return $data;
	}
}

