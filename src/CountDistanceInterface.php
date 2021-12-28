<?php

namespace VeriTest;

use VeriTest\Model\Point;

interface CountDistanceInterface
{
	public function __invoke(Point $first, Point $second) : float|int;
}