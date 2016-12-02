<?php
if (file_exists('../libcompactmvc.php')) include_once('../libcompactmvc.php');
LIBCOMPACTMVC_ENTRY;

/**
 * cephadapter.php
 *
 * @author      Botho Hohbaum <bhohbaum@googlemail.com>
 * @package     digimap
 * @copyright   Copyright (c) MIU08 GmbH
 * @link		http://www.miu08.de
 */
if (extension_loaded("rados")) {
class CephAdapter extends Singleton {
	private $rados;
	private $ctx;

	protected function __construct() {
		DLOG();
		parent::__construct();
		$this->rados = rados_create();
		rados_conf_read_file($this->rados, CEPH_CONF);
		rados_connect($this->rados);
		$this->ctx = rados_ioctx_create($this->rados, CEPH_POOL);
	}

	public function __destruct() {
		DLOG();
		parent::__destruct();
		rados_ioctx_destroy($this->ctx);
		rados_shutdown($this->rados);
	}

	/**
	 * @return CephAdapter
	 */
	public static function get_instance() {
		DLOG();
		return parent::get_instance();
	}

	public function put($oid, $buf) {
		DLOG($oid);
		$res = rados_write_full($this->ctx, $oid, $buf);
		return $res;
	}

	public function get($oid) {
		DLOG($oid);
		$buf = rados_read($this->ctx, $oid, CEPH_MAX_OBJ_SIZE);
		if (is_array($buf)) {
			throw new EmptyResultException($buf['errMessage'], $buf['errCode']);
		}
		return $buf;
	}

	public function remove($oid) {
		DLOG($oid);
		$res = rados_remove($this->ctx, $oid);
		return $res;
	}

	public function objects_list() {
		DLOG();
		$res = rados_objects_list($this->ctx);
		return $res;
	}

}
}
