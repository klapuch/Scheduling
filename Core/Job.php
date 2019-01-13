<?php
declare(strict_types = 1);

namespace Klapuch\Scheduling;

interface Job {
	public function fulfill(): void;

	public function name(): string;
}
