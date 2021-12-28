<?php

namespace VeriTest\Commands;

use VeriTest\Model\Student;
use VeriTest\RateCalculator;
use VeriTest\Repository\StudentRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
	name: "app:calc-attendance",
	description: 'Calculate student\'s attendance from files',
	hidden: false
)]
class CalculateAttendanceCommand extends Command {
	/**
	 * @param StudentRepository $studentRepository
	 * @param RateCalculator $basicRateCalc
	 */
	public function __construct(
		private StudentRepository $studentRepository,
		private RateCalculator $basicRateCalc
	){
		parent::__construct();
	}


	protected function configure(): void {
		$this
			->addUsage("app.phar app:calc-attendance")
		;
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {

		$outputStream = fopen('php://memory','rb+');

		try {

			fputcsv($outputStream,$this->getHeaders());
			/**@var $student Student*/
			foreach ($this->studentRepository->getAll() as $student) {
				$rate = $this->basicRateCalc->calcRate($student);
				fputcsv($outputStream,[$student->id, $rate]);
			}

			rewind($outputStream);

			$output->write(
				stream_get_contents($outputStream)
			);


			return Command::SUCCESS;
		}
		catch (\Throwable $throwable) {
			$output->write(
				$this->formatError($throwable->getMessage())
			);
			return self::FAILURE;
		}
		finally {
			fclose($outputStream);
		}
	}

	/**
	 * @param string $errorMsg
	 *
	 * @return string
	 */
	private function formatError(string $errorMsg) : string {
		return sprintf("ERROR: %s", $errorMsg);
	}

	private function getHeaders(): array {
		return ['id','payout'];
	}
}