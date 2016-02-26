<?php
@include_once ('../libcompactmvc.php');
LIBCOMPACTMVC_ENTRY;

/**
 * Redirect Exception
 *
 * @author Botho Hohbaum (bhohbaum@googlemail.com)
 * @package LibCompactMVC
 * @copyright	Copyright (c) Botho Hohbaum 01.01.2016
 * @license LGPL version 3
 * @link https://github.com/bhohbaum
 */
class RedirectException extends Exception {
	private $is_internal;

	public function __construct($message = null, $code = null, $internal = true) {
		DLOG();
		$this->message = $message;
		$this->code = $code;
		$this->is_internal = $internal;
	}

	public function is_internal() {
		DLOG(print_r($this->is_internal, true));
		return $this->is_internal;
	}

}