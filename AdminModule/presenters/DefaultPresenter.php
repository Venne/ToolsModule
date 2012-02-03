<?php

namespace App\ToolsModule\AdminModule;

use Nette\Forms\Form;
use Nette\Web\Html;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
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
		$form = $this->context->tools->createDoctrineForm();
		$form->setRoot($this->context->parameters["rootDir"]);
		$form->addSubmit("_submit", "Save");
		$form->onSuccess[] = function()
		{
			$form->save();
			$form->presenter->flashMessage("Entities has been generated");
			$form->presenter->redirect("this");
		};
		return $form;
	}

}