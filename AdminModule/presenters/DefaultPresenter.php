<?php

namespace App\ToolsModule\AdminModule;

use Nette\Forms\Form;
use Nette\Web\Html;

/**
 * @author Josef Kříž
 * 
 * @secured
 */
class DefaultPresenter extends \Venne\Application\UI\AdminPresenter {


	/** @persistent */
	public $id;



	public function startup()
	{
		parent::startup();

		$this->addPath("Tools", $this->link(":Tools:Admin:Default:"));
	}


	public function createComponentForm($name)
	{
		$form = new \App\ToolsModule\DoctrineForm($this->context->entitiesGeneratorService, $this->context->parameters["rootDir"]);
		$form->setFlashMessage("Entities has been generated");
		return $form;
	}



	public function beforeRender()
	{
		parent::beforeRender();
		$this->setTitle("Venne:CMS | Tools administration");
		$this->setKeywords("tools administration");
		$this->setDescription("tools administration");
		$this->setRobots(self::ROBOTS_NOINDEX | self::ROBOTS_NOFOLLOW);
	}



}