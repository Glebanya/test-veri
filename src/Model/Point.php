<?php

namespace VeriTest\Model;

class Point
{
	public readonly array $components;

	public function __construct(int ...$components) {
		$this->components = $components;
	}

	public static function fromString(string $str) : Point {
		$str = trim($str," \"()\t\n\r\0\x0B");
		$parts = explode(",",$str);

		return new self(...array_map('intval',$parts));
	}
}