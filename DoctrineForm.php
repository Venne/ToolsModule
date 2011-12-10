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

use Venne\ORM\Column;
use Nette\Utils\Html;

/**
 * @author Josef Kříž
 */
class DoctrineForm extends \Venne\Forms\EditForm {


	/** @var EntitiesGeneratorService */
	protected $entitiesGenerator;

	/** @var string */
	protected $rootDir;



	public function __construct($entitiesGenerator, $rootDir)
	{
		$this->entitiesGenerator = $entitiesGenerator;
		$this->rootDir = $rootDir;
		parent::__construct();
	}



	public function startup()
	{
		parent::startup();

		$this->addGroup("Tables");
		$this->addMultiSelect("tables", "Tables")->setItems($this->entitiesGenerator->getTableNames(), false);


		$this->addGroup("Settings");
		$this->addText("path", "Path for Entities");
		$this->addText("namespace", "Namespace");
		$this->addText("repository", "Repository class")->setDefaultValue("\Venne\Doctrine\ORM\BaseRepository");
		$this->addCheckbox("updateEntityIfExists", "Update entity if exists")->setDefaultValue(true);
		$this->addCheckbox("generateStubMethods", "Generate stub methods")->setDefaultValue(true);
		$this->addCheckbox("generateAnnotations", "Generate annotations")->setDefaultValue(true);
		
		$this->addText("aaa", "Text");
	}



	public function save()
	{
		$val = $this["path"]->value;
		$path = $this->rootDir . (substr($val, 0, 1) == "/" ? $val : "/" . $val);
		$this->entitiesGenerator->generateEntities(
				$this["tables"]->value, $path,
				$this["namespace"]->value,
				$this["repository"]->value,
				$this["updateEntityIfExists"]->value,
				$this["generateStubMethods"]->value,
				$this["generateAnnotations"]->value
			);
	}

}
