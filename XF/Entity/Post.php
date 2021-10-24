<?php

//  ▄▄▄·  ▄▄▄· ▄▄▄· ▄▄▄▄▄ ▄ .▄ ▄· ▄▌
// ▐█ ▀█ ▐█ ▄█▐█ ▀█ •██  ██▪▐█▐█▪██▌
// ▄█▀▀█  ██▀·▄█▀▀█  ▐█.▪██▀▐█▐█▌▐█▪
// ▐█ ▪▐▌▐█▪·•▐█ ▪▐▌ ▐█▌·██▌▐▀ ▐█▀·.
//  ▀  ▀ .▀    ▀  ▀  ▀▀▀ ▀▀▀ ·  ▀ •
//  https://fortreeforums.xyz
//  Licensed under GPL-3.0-or-later 2021
//
//  This file is part of [AP] Activity/Longevity Meters ("ActLong").
//
//  ActLong is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  ActLong is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with ActLong.  If not, see <https://www.gnu.org/licenses/>.

namespace apathy\ActLong\XF\Entity;

use XF\Mvc\Entity\Structure;

class Post extends XFCP_Post
{
	protected function adjustUserMessageCountIfNeeded($amount)
	{
		$nodeId = $this->Thread->node_id;
		$options = \XF::options();
		$parent = parent::adjustUserMessageCountIfNeeded($amount);
		
		$forums = $options->ap_actlong_exclude_nodes;
		
		/* Check if [AP] Minimum Characters for Post Count is installed */
		/* and only increase the meters level if the charcount check is met */
		$addons = \XF::app()->container('addon.cache');
		
		if(!in_array($nodeId, $forums))
		{
			if(array_key_exists('apathy/MinChars', $addons) 
			&& $addons['apathy/MinChars'] >= 1000070)
			{
				if($this->chars >= $options->ap_char_limit)
				{
					$this->User->fastUpdate('ap_activity_meter', max(0, $this->User->ap_activity_meter + $amount));
				}
			}
			else
			{
				$this->User->fastUpdate('ap_activity_meter', max(0, $this->User->ap_activity_meter + $amount));
			}
		}
		
		return $parent;
	}
}
