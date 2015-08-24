<?php
@include_once ('../libcompactmvc.php');
LIBCOMPACTMVC_ENTRY;

/**
 * Controller super class
 *
 * @author Botho Hohbaum (bhohbaum@googlemail.com)
 * @package LibCompactMVC
 * @copyright Copyright (c) Botho Hohbaum 24.01.2012
 * @license LGPL version 3
 * @link https://github.com/bhohbaum/libcompactmvc
 */
abstract class CMVCController extends InputSanitizer {
	private $ob;
	private static $rbrc;
	public $view;

	/**
	 *
	 * @var DbAccess db
	 */
	public $db;

	/**
	 *
	 * @var Log
	 */
	public $log;
	public $redirect;

	/**
	 *
	 */
	public function __construct() {
		// we don't DLOG here, it's spaming...
		// DLOG();
		parent::__construct();
		$this->view = new View();
		$this->log = new Log(Log::LOG_TARGET_FILE, LOG_TYPE);
		$this->log->set_log_file(LOG_FILE);
	}

	/**
	 * Has to return the name of the DBA class.
	 *
	 * @return String
	 */
	protected function dba() {
		DLOG();
		return (defined("DBA_DEFAULT_CLASS")) ? DBA_DEFAULT_CLASS : "DbAccess";
	}

	protected function retrieve_data() {
		DLOG();
	}

	protected function run_page_logic() {
		DLOG();
	}

	protected function retrieve_data_get() {
		DLOG();
	}

	protected function retrieve_data_post() {
		DLOG();
	}

	protected function retrieve_data_put() {
		DLOG();
	}

	protected function retrieve_data_delete() {
		DLOG();
	}

	protected function retrieve_data_exec() {
		DLOG();
	}

	protected function run_page_logic_get() {
		DLOG();
	}

	protected function run_page_logic_post() {
		DLOG();
	}

	protected function run_page_logic_put() {
		DLOG();
	}

	protected function run_page_logic_delete() {
		DLOG();
	}

	protected function run_page_logic_exec() {
		DLOG();
	}

	/**
	 * Exception handler
	 *
	 * @param Exception $e
	 */
	protected function exception_handler($e) {
		DLOG();
		$this->response_code($e->getCode());
		throw $e;
	}

	/**
	 *
	 */
	protected function get_raw_input() {
		return CMVCController::$request_data_raw;
	}

	/**
	 *
	 */
	protected function method() {
		if (php_sapi_name() == "cli") {
			$method = (array_key_exists("METHOD", $_ENV)) ? $_ENV['METHOD'] : "exec";
		} else {
			$method = $_SERVER['REQUEST_METHOD'];
		}
		$method = strtoupper($method);
		DLOG(__METHOD__ . " '" . $method . "'");
		return $method;
	}

	/**
	 *
	 * @param unknown_type $obj
	 */
	protected function json_response($obj) {
		DLOG(__METHOD__ . " " . UTF8::encode(json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));
		$this->view->clear();
		$this->view->add_template("out.tpl");
		$this->view->set_value("out", UTF8::encode(json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));
	}

	/**
	 *
	 * @param unknown_type $obj
	 */
	protected function binary_response($obj) {
		DLOG();
		$this->view->clear();
		$this->view->add_template("out.tpl");
		$this->view->set_value("out", $obj);
	}

	/**
	 *
	 * @param unknown_type $code
	 * @throws Exception
	 */
	protected function response_code($code) {
		DLOG(__METHOD__ . "(" . $code . ")");
		if (function_exists('http_response_code')) {
			$code = http_response_code($code);
		} else {
			if ($code !== null) {
				switch ($code) {
					case 100:
						$text = 'Continue';
						break;
					case 101:
						$text = 'Switching Protocols';
						break;
					case 200:
						$text = 'OK';
						break;
					case 201:
						$text = 'Created';
						break;
					case 202:
						$text = 'Accepted';
						break;
					case 203:
						$text = 'Non-Authoritative Information';
						break;
					case 204:
						$text = 'No Content';
						break;
					case 205:
						$text = 'Reset Content';
						break;
					case 206:
						$text = 'Partial Content';
						break;
					case 300:
						$text = 'Multiple Choices';
						break;
					case 301:
						$text = 'Moved Permanently';
						break;
					case 302:
						$text = 'Moved Temporarily';
						break;
					case 303:
						$text = 'See Other';
						break;
					case 304:
						$text = 'Not Modified';
						break;
					case 305:
						$text = 'Use Proxy';
						break;
					case 400:
						$text = 'Bad Request';
						break;
					case 401:
						$text = 'Unauthorized';
						break;
					case 402:
						$text = 'Payment Required';
						break;
					case 403:
						$text = 'Forbidden';
						break;
					case 404:
						$text = 'Not Found';
						break;
					case 405:
						$text = 'Method Not Allowed';
						break;
					case 406:
						$text = 'Not Acceptable';
						break;
					case 407:
						$text = 'Proxy Authentication Required';
						break;
					case 408:
						$text = 'Request Time-out';
						break;
					case 409:
						$text = 'Conflict';
						break;
					case 410:
						$text = 'Gone';
						break;
					case 411:
						$text = 'Length Required';
						break;
					case 412:
						$text = 'Precondition Failed';
						break;
					case 413:
						$text = 'Request Entity Too Large';
						break;
					case 414:
						$text = 'Request-URI Too Large';
						break;
					case 415:
						$text = 'Unsupported Media Type';
						break;
					case 500:
						$text = 'Internal Server Error';
						break;
					case 501:
						$text = 'Not Implemented';
						break;
					case 502:
						$text = 'Bad Gateway';
						break;
					case 503:
						$text = 'Service Unavailable';
						break;
					case 504:
						$text = 'Gateway Time-out';
						break;
					case 505:
						$text = 'HTTP Version not supported';
						break;
					default:
						throw new Exception('Unknown http status code "' . htmlentities($code) . '"', $code);
						break;
				}
				$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
				header($protocol . ' ' . $code . ' ' . $text);
				$GLOBALS['http_response_code'] = $code;
			} else {
				$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
			}
		}
		return $code;
	}

	/**
	 *
	 * @param unknown_type $observe_headers
	 * @throws RBRCException
	 */
	protected function rbrc($observe_headers = true) {
		DLOG();
		self::$rbrc = RBRC::get_instance($this->request(), $observe_headers);
		if (self::$rbrc->get()) {
			$this->view->clear();
			$this->view->add_template("out.tpl");
			$this->view->set_value("out", self::$rbrc->get());
			$this->ob = $this->view->render();
			throw new RBRCException();
		}
	}

	/**
	 *
	 */
	public function run() {
		DLOG();
		DLOG(var_export($_REQUEST, true));
		$this->redirect = "";
		$this->db = DbAccess::get_instance($this->dba());
		$this->populate_members();
		if (!isset($this->view)) {
			$this->view = new View();
		}
		switch ($this->method()) {
			case 'GET':
				$this->retrieve_data_get();
				break;
			case 'POST':
				$this->retrieve_data_post();
				break;
			case 'PUT':
				$this->retrieve_data_put();
				break;
			case 'DELETE':
				$this->retrieve_data_delete();
				break;
			case 'EXEC':
				$this->retrieve_data_exec();
				break;
		}
		$this->retrieve_data();
		switch ($this->method()) {
			case 'GET':
				$this->run_page_logic_get();
				break;
			case 'POST':
				$this->run_page_logic_post();
				break;
			case 'PUT':
				$this->run_page_logic_put();
				break;
			case 'DELETE':
				$this->run_page_logic_delete();
				break;
			case 'EXEC':
				$this->run_page_logic_exec();
				break;
		}
		$this->run_page_logic();
		// after the page logic has been executed, we don't need
		// the database any more. all information should be stored
		// in variables by now.
		unset($this->db);
		// if we have a redirect, we don't want the current template(s) to be displayed
		if ($this->redirect == "") {
			$this->ob = $this->view->render();
			if (isset(self::$rbrc)) {
				self::$rbrc->put($this->ob);
			}
		}
	}

	/**
	 * Run the exception handler method
	 *
	 * @param Exception $e
	 *        	the exception
	 */
	public function run_exception_handler($e) {
		DLOG(__METHOD__ . " Exception " . $e->getCode() . " '" . $e->getMessage() . "'");
		$this->exception_handler($e);
		$this->ob = $this->view->render();
	}

	/**
	 *
	 */
	public function get_ob() {
		DLOG();
		return $this->ob;
	}

	/**
	 *
	 */
	protected function populate_members() {
		if (REGISTER_HTTP_VARS) {
			foreach (array_keys($this->request(null)) as $key) {
				$this->{$key} = $this->request($key);
			}
		}
	}


}
