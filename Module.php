<?php

/**
 * Venne:CMS (version 2.0-dev released on $WCDATE$)
 *
 * Copyright (c) 2011 Josef Kříž pepakriz@gmail.com
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\ToolsModule;

use App\CoreModule\NavigationEntity;

/**
 * @author Josef Kříž
 */
class Module extends \Venne\Module\AutoModule {



	public function getName()
	{
		return "tools";
	}



	public function getDescription()
	{
		return "Collections of tools for developers.";
	}



	public function getVersion()
	{
		return "2.0";
	}



	public function configure(\Nette\DI\Container $container, \App\CoreModule\CmsManager $manager)
	{
		parent::configure($container, $manager);

		$manager->addService("entitiesGenerator", function() use ($container) {
					return new EntitiesGeneratorService($container->entityManager, $container->schemaManager);
				});
		$manager->addEventListener(array(\App\CoreModule\Events::onAdminMenu), $this);
	}



	public function onAdminMenu($menu)
	{
		$nav = new NavigationEntity("Tools");
		$nav->setLink(":Tools:Admin:Default:");
		$nav->setMask(":Tools:Admin:*:*");
		$menu->addNavigation($nav);
	}

}
