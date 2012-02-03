<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011 Josef Kříž (pepakriz
 * @gmail.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\ToolsModule\Forms;

use Venne\ORM\Column;
use Nette\Utils\Html;
use Venne\Application\UI\Form;
use App\ToolsModule\Services\EntitiesGeneratorService;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DoctrineForm extends Form {


	/** @var EntitiesGeneratorService */
	protected $entitiesGenerator;

	/** @var string */
	protected $rootDir;



	public function __construct(EntitiesGeneratorService $entitiesGenerator)
	{
		$this->entitiesGenerator = $entitiesGenerator;
		parent::__construct();
	}



	public function setRoot($rootDir)
	{
		$this->rootDir = $rootDir;
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
		$this->entitiesGenerator->generateEntities($this["tables"]->value, $path, $this["namespace"]->value, $this["repository"]->value, $this["updateEntityIfExists"]->value, $this["generateStubMethods"]->value, $this["generateAnnotations"]->value);
	}

}
