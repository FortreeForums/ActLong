<?php

namespace apathy\ActLong;

use XF\Mvc\Entity\Entity;

class Listener
{
	public static function userEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['ap_activity_meter'] = ['type' => Entity::UINT, 'default' => NULL];
	}
}
