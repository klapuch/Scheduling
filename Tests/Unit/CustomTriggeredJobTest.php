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
final class CustomTriggeredJobTest extends Tester\TestCase {
	public function testTriggeringOnValidCallback(): void {
		ob_start();
		(new Scheduling\CustomTriggeredJob(
			new Scheduling\FakeJob(static function () {
				echo 'a';
			}),
			static function () {
				return true;
			}
		))->fulfill();
		Assert::same('a', ob_get_clean());
	}

	public function testSkippingForFalseCallback(): void {
		ob_start();
		(new Scheduling\CustomTriggeredJob(
			new Scheduling\FakeJob(static function () {
				echo 'a';
			}),
			static function () {
				return false;
			}
		))->fulfill();
		Assert::same('', ob_get_clean());
	}
}

(new CustomTriggeredJobTest())->run();
