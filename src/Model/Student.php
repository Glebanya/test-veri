<?php

namespace VeriTest\Model;

class Student{

	public function __construct(
		public readonly int|string $id,
		public readonly string $name,
		public readonly Point $location,
		public readonly \DateTimeInterface $dob,
		public readonly int|string $workplace,
		public readonly Status $status
	){}
}