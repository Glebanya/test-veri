<?php

namespace VeriTest\Repository;

use VeriTest\Model\Point;
use VeriTest\Model\Status;
use VeriTest\Model\Student;
use VeriTest\Streams\CsvIterator;
use VeriTest\Streams\CsvStream;

class StudentRepository {

	private CsvIterator $csvIterator;

	/**
	 * @param string $file
	 */
	public function __construct(string $file) {

		if (false === $studentDescriptor = fopen($file, 'rb')) {
			throw new \Error("can't open attendance database file");
		}

		$this->csvIterator = new CsvIterator(
			new CsvStream($studentDescriptor)
		);

	}

	/**
	 * @return \Iterator<Student>
	 */
	public function getAll() : \Iterator {

		foreach ($this->csvIterator as $student) {
			yield new Student(
				id: $student->id,
				name: $student->name,
				location: Point::fromString($student->location),
				dob: \DateTime::createFromFormat('Y-m-d',$student->dob),
				workplace: $student->workplace_id,
				status: Status::fromString($student->status)
			);
		}
	}

}