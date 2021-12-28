<?php

namespace VeriTest\Streams;

class CsvIterator implements \IteratorAggregate
{

	public function __construct(public readonly CsvStream $csvStream) {
	}

	/**
	 * @return \Generator<object>
	 */
	public function getIterator() : \Generator
	{
		while ($object = $this->csvStream->fetch()) {
			yield $object;
		}
	}
}