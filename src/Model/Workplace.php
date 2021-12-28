<?php

namespace VeriTest\Model;

class Workplace
{
	public function __construct(
		public readonly int|string $id,
		public readonly string $name,
		public readonly Point $location
	) {}

}