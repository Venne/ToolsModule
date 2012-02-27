<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\ToolsModule\AdminModule;

use Nette\Forms\Form;
use Nette\Web\Html;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class DefaultPresenter extends \App\CoreModule\Presenters\AdminPresenter {


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