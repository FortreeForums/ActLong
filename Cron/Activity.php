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

namespace apathy\ActLong\Cron;

class Activity
{
	public static function resetActivityMeter()
	{
		$options = \XF::options();
		
		if(!$options->ap_actlong_disable_activity)
		{
			$finder = \XF::finder('XF:User');
			$users = $finder->where('user_state', 'valid')
					->where('ap_activity_meter', '>=', 1)
					->fetch();
		
			foreach($users as $user)
			{
				$user->fastUpdate('ap_activity_meter', 0);
			}
		}
	}
}
