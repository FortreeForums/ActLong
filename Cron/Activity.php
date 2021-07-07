<?php

namespace apathy\ActLong\Cron;

class Activity
{
	public static function resetActivityMeter()
	{
		$finder = \XF::finder('XF:User');
		$users = $finder->where('user_state', 'valid')->fetch();
		
		foreach($users as $user)
		{
			$user->fastUpdate('ap_activity_meter', 0);
		}
	}
}