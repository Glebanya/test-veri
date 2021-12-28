<?php

namespace VeriTest\Streams;

class CsvStream {

	public const DEFAULT_SEPARATOR = ",";

	public const DEFAULT_BUFFER_LENGTH = 0;

	public const DEFAULT_ENCLOSURE = "\"";

	public const DEFAULT_ESCAPE = "\\";

	public readonly array $headers;

	private int $header_count;

	/**
	 * @param $stream
	 * @param int $length
	 * @param string $separator
	 * @param string $enclosure
	 * @param string $escape
	 */
	public function __construct(
		/** @var resource $stream */
		private $stream,
		/**@var int $length */
		public readonly int $length = self::DEFAULT_BUFFER_LENGTH,
		/**@var string $separator*/
		public readonly string $separator = self::DEFAULT_SEPARATOR,
		/**@var string $enclosure*/
		public readonly string $enclosure = self::DEFAULT_ENCLOSURE,
		/**@var string $escape*/
		public readonly string $escape = self::DEFAULT_ESCAPE
	) {

		if (!is_resource($this->stream)) {
			throw new \Error("stream is not resource.");
		}
		if ($this->length < 0 ) {
			throw new \Error("Wrong buffer size.");
		}

		if (!$headers = $this->read()) {
			throw new \Error("Can't read headers");
		}

		$this->headers = $headers;

		$this->header_count = count($this->headers);

	}

	private function read() : array|null {

		if (\feof($this->stream)) {
			return null;
		}

		if (!$data = fgetcsv($this->stream, $this->length, $this->separator, $this->enclosure, $this->escape)) {
			return null;
		}

		return $data;
	}

	/**
	 * @return object|null
	 */
	public function fetch() : ?object {

		if (!$data = $this->read()) {
			return null;
		}

		if (count($data) !== $this->header_count) {
			throw new \Error("wrong columns count");
		}

		$data = \array_combine($this->headers, $data);

		return (object) $data;
	}
}

