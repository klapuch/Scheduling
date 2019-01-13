<?php
declare(strict_types = 1);

namespace Klapuch\Scheduling\TestCase;

use Klapuch\Scheduling\Misc;

trait TemplateDatabase {
	/** @var \Klapuch\Storage\Connection */
	protected $connection;

	/** @var \Klapuch\Scheduling\Misc\Databases */
	private $databases;

	protected function setUp(): void {
		parent::setUp();
		$credentials = parse_ini_file(__DIR__ . '/../config/config.ini', true);
		$this->databases = new Misc\RandomDatabases($credentials['DATABASE']);
		$this->connection = $this->databases->create();
	}

	protected function tearDown(): void {
		parent::tearDown();
		$this->connection = null;
		$this->databases->drop();
	}
}
