<?php

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
