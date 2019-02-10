<?php
declare(strict_types = 1);

namespace Klapuch\Scheduling;

final class CustomTriggeredJob implements Job {
	/** @var \Klapuch\Scheduling\Job */
	private $origin;

	/** @var callable */
	private $trigger;

	public function __construct(Job $origin, callable $trigger) {
		$this->origin = $origin;
		$this->trigger = $trigger;
	}

	public function fulfill(): void {
		if (call_user_func($this->trigger) === true)
			$this->origin->fulfill();
	}

	public function name(): string {
		return $this->origin->name();
	}
}
