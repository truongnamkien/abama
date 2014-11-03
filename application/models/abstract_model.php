<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

abstract class Abstract_Model extends CI_Model {

    protected $CI;
    protected $cache = NULL;
    protected static $global_data = array();

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();

        $this->CI->load->config('cache', TRUE);
        $this->use_cache = $this->CI->config->item('use_cache', 'cache');
        $this->use_static = $this->CI->config->item('use_static', 'cache');
        $this->default_cache_time = $this->CI->config->item('default_cache_time', 'cache');

        if ($this->use_cache) {
            $this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
            $this->cache = & $this->CI->cache;
            $this->key_all = $this->database . '.all_objects';
        }

        $this->load->database();
    }

    /**
     * Tạo record
     * @param type $data
     * @return type
     */
    public function create($data) {
        unset($data[$this->type . '_id']);
        $ret = $this->check_existed($data);

        if ($ret['return_code'] == API_SUCCESS && $ret['data'] == FALSE) {
            if ($this->db->insert($this->database, $data)) {
                $id = $this->db->insert_id();
                if ($id > 0) {
                    $data[$this->type . '_id'] = $id;
                    if ($this->use_cache) {
                        $this->clear_all_cache();
                    }
                    return $this->_ret(API_SUCCESS, $data);
                }
            }
        }

        return $this->_ret(API_FAILED);
    }

    /**
     * Xóa record theo id
     * @param type $id
     */
    public function delete($id) {
        $this->delete_where(array($this->type . '_id' => $id));
    }

    /**
     * Xóa những record thỏa điều kiện
     * @param type $filter
     */
    public function delete_where($filter) {
        if ($this->use_cache) {
            $this->clear_all_cache();
        }
        $this->db->where($filter)->delete($this->database);
    }

    /**
     * Tìm record theo id
     * @param type $id
     * @return type
     */
    public function get($id) {
        $objects = $this->get_where(array($this->type . '_id' => $id));
        if ($objects['return_code'] == API_SUCCESS && !empty($objects['data'])) {
            return $this->_ret(API_SUCCESS, array_shift($objects['data']));
        }
        return $this->_ret(API_FAILED);
    }

    /**
     * Tìm những record thỏa điều kiện
     * @param type $filter
     * @param string $sort_by
     * @param string $order
     * @return type
     */
    public function get_where($filter, $sort_by = FALSE, $order = 'asc', $offset = NULL, $limit = NULL) {
        if ($sort_by == FALSE) {
            $sort_by = $this->type . '_id';
        }
        if ($order != 'asc' && $order != 'desc') {
            $order = 'asc';
        }
        if (is_string($filter) && $filter == '') {
            $filter = array();
        }
        if (empty($offset)) {
            $offset = 0;
        }

        if ($this->use_cache) {
            if (is_array($filter)) {
                $cache_key = $this->get_cache_key($filter, $sort_by, $order);
                $data = $this->get_cache($cache_key);
                if (!empty($data)) {
                    if (empty($limit)) {
                        $limit = count($data);
                    }
                    return $this->_ret(API_SUCCESS, array_slice($data, $offset, $limit));
                }
            }
        }

        $query = $this->db->from($this->database)
                ->order_by($sort_by, $order)
                ->where($filter)
                ->get();
        if (!empty($query)) {
            $data = $query->result_array();

            if ($this->use_cache) {
                if (is_array($filter)) {
                    $cache_key = $this->get_cache_key($filter, $sort_by, $order);
                    $this->save_cache($cache_key, $data);
                }
            }
            if (empty($limit)) {
                $limit = count($data);
            }
            return $this->_ret(API_SUCCESS, array_slice($data, $offset, $limit));
        }
        return $this->_ret(API_FAILED);
    }

    /**
     * Chỉ lấy những field được liệt kê
     * @param type $fields
     * @param type $filter
     */
    public function get_fields($fields, $filter, $sort_by = FALSE, $order = 'asc', $offset = NULL, $limit = NULL) {
        if ($sort_by == FALSE) {
            $sort_by = $this->type . '_id';
        }
        if ($order != 'asc' && $order != 'desc') {
            $order = 'asc';
        }
        if (is_string($filter) && $filter == '') {
            $filter = array();
        }

        $query = $this->db->select($fields)
                ->from($this->database)
                ->order_by($sort_by, $order)
                ->limit($limit, $offset)
                ->where($filter)
                ->get();

        if ($error = $this->db->_error_message()) {
            return $this->_ret(API_FAILED);
        }
        if (!empty($query)) {
            $data = $query->result_array();

            return $this->_ret(API_SUCCESS, $data);
        }
        return $this->_ret(API_FAILED);
    }

    /**
     * Update record theo id
     * @param type $id
     * @param type $update_data
     * @param type $filter
     * @return type
     */
    public function update($id, $update_data, $filter = array()) {
        if (empty($id) && empty($filter)) {
            return $this->_ret(API_FAILED);
        }
        if (!empty($id)) {
            if (empty($filter)) {
                $filter = '';
            }

            if (is_array($filter)) {
                $temp_filter = array();
                foreach ($filter as $key => $val) {
                    $temp_filter[] = $key . ' = ' . $val;
                }
                $filter = implode(' AND ', $temp_filter);
            }
            if (!empty($filter)) {
                $filter .= ' AND ';
            }
            $filter .= $this->type . '_id = ' . $id;
        }

        $data = $this->get_where($filter);
        unset($update_data[$this->type . '_id']);

        if ($data['return_code'] == API_SUCCESS && !empty($data['data'])) {
            $data = $data['data'];
            $this->db->trans_start();
            $this->db->where($filter)->update($this->database, $update_data);
            if ($error = $this->db->_error_message()) {
                return $this->_ret(API_FAILED);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->_ret(API_FAILED);
            } else {
                $this->db->trans_commit();
                if ($this->use_cache) {
                    $this->clear_all_cache();
                }
                return $this->get_where($filter);
            }
        }
        return $this->_ret(API_FAILED);
    }

    /**
     * Lấy tất cả record trong table
     * @param string $sort_by
     * @param string $order
     * @return type
     */
    public function get_all($sort_by = FALSE, $order = 'asc', $offset = NULL, $limit = NULL) {
        if ($sort_by == FALSE) {
            $sort_by = $this->type . '_id';
        }
        if ($order != 'asc' && $order != 'desc') {
            $order = 'asc';
        }
        if (empty($offset)) {
            $offset = 0;
        }
        if ($this->use_cache) {
            $cache_key = $this->get_cache_key(array(), $sort_by, $order);
            $data = $this->get_cache($cache_key);
            if (!empty($data)) {
                if (empty($limit)) {
                    $limit = count($data);
                }
                return $this->_ret(API_SUCCESS, array_slice($data, $offset, $limit));
            }
        }
        $query = $this->db
                ->order_by($sort_by, $order)
                ->get($this->database);
        if (!empty($query)) {
            $data = $query->result_array();
            if ($this->use_cache) {
                $this->save_cache($cache_key, $data);
            }
            if (empty($limit)) {
                $limit = count($data);
            }
            return $this->_ret(API_SUCCESS, array_slice($data, $offset, $limit));
        }
        return $this->_ret(API_FAILED);
    }

    /**
     * Đếm số lượng record trong table
     * @return int
     */
    public function count_all() {
        return $this->db->count_all($this->database);
    }

    /**
     * Ðếm tổ số record thỏa điều kiện
     * @param type $filter
     * @return int
     */
    public function count_where($filter) {
        if (empty($filter)) {
            return $this->count_all();
        }

        $objects = $this->get_where($filter);
        if ($objects['return_code'] == API_SUCCESS) {
            return count($objects['data']);
        }
        return 0;
    }

    /**
     * Kiểm tra xem field có tồn tại trong table không
     * @param type $field
     * @return boolean
     */
    public function check_field_existed($field) {
        if (empty($field)) {
            return FALSE;
        }

        return $this->db->field_exists($field, $this->database);
    }

    protected function check_existed($data) {
        return $this->_ret(API_SUCCESS, FALSE);
    }

    public function get_available_display_order() {
        $query = $this->db->query('SELECT max(display_order) as display_order FROM ' . $this->database);

        if (!empty($query)) {
            $row = $query->row_array();
            return $row['display_order'] + 1;
        }
        return 0;
    }

    /**
     * Dựa vào filter để generate ra key tuong ứng
     * @param type $filter
     * @return string
     */
    protected function get_cache_key($filter, $sort_by = FALSE, $sort_order = 'asc') {
        if ($this->use_cache) {
            if (empty($filter)) {
                $cache_key = 'cache.' . $this->key_all;
            } else {
                ksort($filter);
                $cache_key = 'cache.' . $this->database;
                foreach ($filter as $key => $val) {
                    $cache_key .= '.' . $key . '.' . $val;
                }
            }
            if (!empty($sort_by)) {
                $cache_key .= '.sort_' . $sort_by . '_' . $sort_order;
            }
            return $cache_key;
        }
        return FALSE;
    }

    /**
     * Save cache và luu key vào danh sách những key dã cache
     * @param type $key
     * @param type $data
     */
    protected function save_cache($key, $data) {
        if ($this->use_cache) {
            if ($this->use_static) {
                self::$global_data[$key] = $data;
            }
            $this->cache->save($key, $data, $this->default_cache_time);
        }
    }

    /**
     * Lấy data đã được cache
     * @param type $key
     * @return type
     */
    protected function get_cache($key) {
        if ($this->use_cache) {
            if ($this->use_static && isset(self::$global_data[$key]) && !empty(self::$global_data[$key])) {
                return self::$global_data[$key];
            }
            $data = $this->cache->get($key);
            if ($this->use_static) {
                self::$global_data[$key] = $data;
            }
            return $data;
        }
        return FALSE;
    }

    /**
     * Xóa 1 cache và remove khỏi danh sách key list
     * @param type $key
     * @return type
     */
    protected function clear_cache($key) {
        if ($this->use_cache) {
            if ($this->use_static && isset(self::$global_data[$key])) {
                unset(self::$global_data[$key]);
            }

            $this->cache->delete($key);
        }
    }

    /**
     * Xóa toàn bộ cache của model
     */
    protected function clear_all_cache() {
        if ($this->use_cache) {
            if ($this->use_static) {
                self::$global_data = array();
            }
            $this->cache->clean('cache.' . $this->database);
        }
    }

}
