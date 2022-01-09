<?php

namespace VeriTest\Tests;

use VeriTest\EuclideanDistance;
use VeriTest\Model\Point;
use VeriTest\Model\Status;
use VeriTest\Model\Student;
use VeriTest\Model\Workplace;
use VeriTest\RateCalculator;
use VeriTest\Repository\WorkplaceRepository;

class CalculateRateTest extends \PHPUnit\Framework\TestCase {

	public function buildWorkplaceRepoStub() {

		$workplaceRepoStub = $this->getMockBuilder(WorkplaceRepository::class)
			->disableOriginalConstructor()
			->getMock()
		;
		$workplaceRepoStub
			->method('findById')
			->willReturnCallback(
				fn() => new Workplace(id: 1, name: 2, location: new Point(1,1))
			)
		;

		return $workplaceRepoStub;
	}

	public function studentDataProvider(): array {
		return [
			'AT STUDENT' => [
					new Student(
						id: 1,
						name: 'A',
						location: new Point(1,1),
						dob: \DateTime::createFromFormat('Y/m/d', '1990/01/01'),
						workplace: 1,
						status: Status::ATTENDING
					),
					96
			],
			'AL STUDENT' => [
				new Student(
					id: 2,
					name: 'B',
					location: new Point(1,1),
					dob: \DateTime::createFromFormat('Y/m/d', '1990/01/01'),
					workplace: 1,
					status: Status::ANNUAL_LEAVE
				),
				90.5
			],
			'CSL STUDENT' => [
				new Student(
					id: 3,
					name: 'C',
					location: new Point(1,1),
					dob: \DateTime::createFromFormat('Y/m/d', '1990/01/01'),
					workplace: 1,
					status: Status::CERTIFIED_SICK_LEAVE
				),
				90.5
			],
			'USL STUDENT' => [
				new Student(
					id: 4,
					name: 'D',
					location: new Point(1,1),
					dob: \DateTime::createFromFormat('Y/m/d', '1990/01/01'),
					workplace: 1,
					status: Status::UNCERTIFIED_SICK_LEAVE
				),
				0.0
			],
	];

	}

	/**
	 * @dataProvider studentDataProvider
	 */
	public function testCalculate(Student $student, $rate): void {

		$rateCalculator = new RateCalculator(
			$this->buildWorkplaceRepoStub(),
			new EuclideanDistance()
		);

		$this->assertEqualsWithDelta(
			$rate,
			$rateCalculator->calcRate($student),
			0.001,
			"wrong calc rate"
		);
	}
}