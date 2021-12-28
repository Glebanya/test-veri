<?php

namespace VeriTest;

use VeriTest\Model\Point;

class EuclideanDistance implements CountDistanceInterface {

	public function __invoke(Point $first, Point $second): float|int
	{
		$n = max(count($first->components),count($second->components));

		for ($i = 0, $sum = 0; $i < $n; $i++) {
			$sum += (($first->components[$i] ?? 0) - ($second->components[$i] ?? 0)) ** 2;
		}

		return sqrt($sum);
	}
}