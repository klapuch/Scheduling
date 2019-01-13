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
final class SerialJobsTest extends Tester\TestCase {
	public function testRunningOneByOne(): void {
		ob_start();
		(new Scheduling\SerialJobs(
			new Scheduling\FakeJob(
				static function() {
					echo 'O';
				},
				'a'
			),
			new Scheduling\FakeJob(
				static function() {
					echo 'K';
				},
				'b'
			),
			new Scheduling\FakeJob(
				static function() {
					echo '!';
				},
				'c'
			)
		))->fulfill();
		Assert::same('OK!', ob_get_clean());
	}
}

(new SerialJobsTest())->run();
