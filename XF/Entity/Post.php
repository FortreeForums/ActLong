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
		
		if(!in_array($nodeId, $forums))
		{
			$this->User->fastUpdate('ap_activity_meter', max(0, $this->User->ap_activity_meter + $amount));
		}
	}
}
