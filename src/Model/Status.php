<?php

namespace VeriTest\Model;

enum Status {
	case ATTENDING;
	case ANNUAL_LEAVE;
	case CERTIFIED_SICK_LEAVE;
	case UNCERTIFIED_SICK_LEAVE;

	public static function fromString(string $str) : ?Status {
		return match (trim($str)) {
			'AT' => self::ATTENDING,
			'AL' => self::ANNUAL_LEAVE,
			'CSL' => self::CERTIFIED_SICK_LEAVE,
			'USL' => self::UNCERTIFIED_SICK_LEAVE,
			default => null
		};
	}
}