<?php

namespace VeriTest\Repository;

use VeriTest\Model\Point;
use VeriTest\Model\Workplace;
use VeriTest\Streams\CsvIterator;
use VeriTest\Streams\CsvStream;

class WorkplaceRepository {

	/**
	 * @var array<string|int,Workplace>
	 */
	private array $workspaces = [];

	/**
	 * @param string $file
	 */
	public function __construct(string $file) {

		if (false === $workspaceDescriptor = fopen($file, 'rb')) {
			throw new \Error("can't open file workspaces database file");
		}

		$csvIterator = new CsvIterator(
			new CsvStream($workspaceDescriptor)
		);

		foreach ($csvIterator as $workspace) {
			$this->workspaces[$workspace->id] = new Workplace(
				id: $workspace->id,
				name: $workspace->name,
				location: Point::fromString($workspace->location)
			);
		}
	}

	/**
	 * @param string|int $id
	 *
	 * @return Workplace|null
	 */
	public function findById(string|int $id) : ?Workplace {
		return $this->workspaces[$id] ?? null;
	}


}