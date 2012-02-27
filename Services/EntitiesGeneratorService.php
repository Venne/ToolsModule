<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\ToolsModule\Services;

use Venne;
use Nette\Object;
use Doctrine\ORM\Tools\EntityGenerator;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class EntitiesGeneratorService extends Object {


	/** @var \Doctrine\ORM\EntityManager */
	protected $em;

	/** @var \Doctrine\ORM\Tools\EntityGenerator */
	protected $eg;

	/** @var \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory */
	protected $cmf;

	/** @var \Doctrine\DBAL\Schema\AbstractSchemaManager */
	protected $sm;



	/**
	 * Constructor
	 */
	public function __construct(\Doctrine\ORM\EntityManager $entityManager, \Doctrine\DBAL\Schema\AbstractSchemaManager $schemaManager)
	{
		$this->eg = new EntityGenerator;
		$this->em = $entityManager;
		$this->sm = $schemaManager;
	}



	/**
	 * Factory for Driver
	 *
	 * @param string $namespace
	 * @return \Doctrine\ORM\Mapping\Driver\DatabaseDriver
	 */
	protected function createDriver($namespace = "")
	{
		$driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver($this->sm);
		foreach ($this->getTableNames() as $table) {
			$driver->setClassNameForTable($table, ucfirst($table) . "Entity");
		}
		$driver->setNamespace($namespace);
		return $driver;
	}



	public function getTableNames()
	{
		$ret = array();
		foreach ($this->sm->listTables() as $table) {
			$ret[] = $table->getName();
		}
		return $ret;
	}



	public function generateEntity($tableName, $path, $namespace = "", $repository = NULL, $updateEntityIfExists = true, $generateStubMethods = true, $generateAnnotations = true)
	{
		$this->generateEntities((array)$tableNames, $path, $namespace, $repository, $updateEntityIfExists, $generateStubMethods, $generateAnnotations);
	}



	public function generateEntities($tableNames, $path, $namespace = "", $repository = NULL, $updateEntityIfExists = true, $generateStubMethods = true, $generateAnnotations = true)
	{
		$namespace = ucfirst($namespace);
		if (substr($namespace, -1) != "\\") {
			$namespace .= "\\";
		}

		$driver = $this->createDriver($namespace);

		$this->em->getConfiguration()->setMetadataDriverImpl($driver);

		$this->cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
		$this->cmf->setEntityManager($this->em);


		$this->eg->setUpdateEntityIfExists($updateEntityIfExists); // only update if class already exists
		//$generator->setRegenerateEntityIfExists(true);	// this will overwrite the existing classes
		$this->eg->setGenerateStubMethods($generateStubMethods);
		$this->eg->setGenerateAnnotations($generateAnnotations);

		$metadata = array();
		foreach ($tableNames as $name) {
			$params = $this->cmf->getMetadataFor($namespace . ucfirst($name) . "Entity");
			if ($repository) {
				$params->setCustomRepositoryClass($repository);
			}

			$metadata[] = $params;
		}

		$this->eg->generate($metadata, $path);
	}

}

