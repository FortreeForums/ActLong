<?php

namespace apathy\ActLong\XF\Entity;

use XF\Mvc\Entity\Structure;

class Post extends XFCP_Post
{
	protected function adjustUserMessageCountIfNeeded($amount)
	{
		$parent = parent::adjustUserMessageCountIfNeeded($amount);
		
		$this->User->fastUpdate('ap_activity_meter', max(0, $this->User->ap_activity_meter + $amount));
	}
}
