<?php

namespace VeriTest;

use VeriTest\Model\Status;
use VeriTest\Model\Student;
use VeriTest\Repository\WorkplaceRepository;

class RateCalculator
{
	private const MEAL_ALLOWANCE = 5.5;

	public function __construct(
		private WorkplaceRepository $workplaceRepository,
		private CountDistanceInterface $distanceCounter
	){}

	private function calcBasicRate(Student $student) : float|int {

		$yearsOld = $student->dob->diff(new \DateTime())->y;

		return match (true) {
			$yearsOld < 18 => 72.50,
			$yearsOld <= 24 => 81.00,
			$yearsOld === 25 => 85.90,
			default => 90.50
		};
	}

	private function calcMealAllowance(Student $student) : float|int {

		return static::MEAL_ALLOWANCE;
	}

	private function calcTravelAllowance(Student $student) : float|int {

		if (!$workplace = $this->workplaceRepository->findById($student->workplace)){
			throw new \Error("can't find workplace");
		}

		$distanceCounter = $this->distanceCounter;
		$distance = $distanceCounter($student->location,$workplace->location);
		if ($distance < 5) {
			return 0;
		}

		$distance = floor($distance) * 2;

		return $distance * 1.09;
	}

	public function calcRate(Student $student) : float|int {

		if ($student->status === Status::UNCERTIFIED_SICK_LEAVE) {
			return 0.0;
		}

		$rate = $this->calcBasicRate($student);

		if ($student->status !== Status::ATTENDING) {
			return $rate;
		}

		$rate += $this->calcMealAllowance($student);
		$rate += $this->calcTravelAllowance($student);

		return $rate;

	}
}