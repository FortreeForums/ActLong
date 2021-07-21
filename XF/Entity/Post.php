<?php

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
	}
}
