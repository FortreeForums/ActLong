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

namespace apathy\ActLong;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;
	
	public function installStep1()
	{
		$this->schemaManager()->alterTable('xf_user', function(Alter $table)
		{
			$table->addColumn('ap_activity_meter', 'int')->nullable()->setDefault(null);
		});
	}
	
	public function uninstallStep1()
	{
 		$this->schemaManager()->alterTable('xf_user', function(Alter $table)
    		{
			$table->dropColumns('ap_activity_meter');
   		});
	}
	
	public function installStep2()
	{
		/* Fetch User ID 1's registration date
		   and store it in SimpleCache.
		   This will be used as a "start date" for
		   the forum, and Longevity meters are
		   based around this value. */
		   
		   $app = \XF::app();
		   $finder = \XF::finder('XF:User');
		   $date = $finder->where('user_id', 1)->fetchOne()->register_date;
		   $simpleCache = $app->simpleCache();
		   
		   $simpleCache['apathy/ActLong']['forum_start'] = $date;
	}
	
	public function uninstallStep2()
	{
		   $app = \XF::app();
		   $simpleCache = $app->simpleCache();
		   
		   $simpleCache['apathy/ActLong']['forum_start'] = NULL;		
	}
}
