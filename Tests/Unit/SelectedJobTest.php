<?php
declare(strict_types = 1);

namespace Klapuch\Scheduling\Unit;

use Klapuch\Scheduling;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class SelectedJobTest extends Tester\TestCase {
	/**
	 * @throws \UnexpectedValueException Job "regenerate" does not exist
	 */
	public function testThrowingOnUnknownName(): void {
		(new Scheduling\SelectedJob(
			'regenerate',
			new Scheduling\FakeJob(null, 'a'),
			new Scheduling\FakeJob(null, 'b'),
			new Scheduling\FakeJob(null, 'c')
		))->fulfill();
	}

	public function testFulfillingOnlySelected(): void {
		ob_start();
		(new Scheduling\SelectedJob(
			'regenerate',
			new Scheduling\FakeJob(
				static function() {
					echo 'XX';
				},
				'a'
			),
			new Scheduling\FakeJob(
				static function() {
					echo 'XX';
				},
				'b'
			),
			new Scheduling\FakeJob(
				static function() {
					echo 'XX';
				},
				'regenerate'
			),
			new Scheduling\FakeJob(
				static function() {
					echo 'OK';
				},
				'regenerate'
			),
			new Scheduling\FakeJob(
				static function() {
					echo 'XX';
				},
				'c'
			)
		))->fulfill();
		Assert::same('OK', ob_get_clean());
	}
}

(new SelectedJobTest())->run();
