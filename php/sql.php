<?php

/**
 * User: wujie
 * Date: 16/8/1
 * @desc 数据查询
 */
class ZSelectSql
{
    public $database;

    public $db;
    public $commend;

    public $table;
    public $select;
    public $where;

    public $order;
    public $limit;
    public $group;
    public $having;

    public $join;
    public $join_string_array;
    public $join_array;
    public $error = false;

    public $sql;
    public $old_sql;

    public $select_page  = 1;
    public $select_limit = 20;

    public static $db_array  = ["db1", "db2"];
    public static $not_db_table
                             = [
            "db1"     => ['table1'],
            "db1" => [],
        ];

        //需要转化为大写的表
    public static $table_big = ["AuthItemChild", "AuthItem", "AuthAssignment"];
    public static $not_params
                             = [
            "db1" => [
                "table1"              => [""],
            ]
        ];

    public function __construct($db, $sql)
    {
        $this->database = $db;
        $this->old_sql  = $sql;
        $this->sql      = str_replace('`', "", strtolower($sql));
        $this->db       = Yii::app()->$db;
        $this->commend  = $this->db->createCommand();
    }

    public function strNpos($str, $find, $n)
    {
        $pos_val = 0;
        for ($i = 1; $i <= $n; $i++) {
            $pos     = strpos($str, $find);
            $str     = substr($str, $pos + 1);
            $pos_val = $pos + $pos_val + 1;
        }
        return $pos_val - 1;
    }

    public function getChildrenVal($sql)
    {
        $children = false;
        preg_match('/\(\s*\b(select)\b/i', $sql, $children_select);
        if ($children_select) {
            $select_pos   = strpos($sql, $children_select[0]);
            $children_all = substr($sql, $select_pos);
            $count        = substr_count($children_all, "(");
            if ($count == 1) {
                $end      = $this->strNpos($children_all, ")", 1);
                $children = substr($children_all, 0, $end + 1);
            } else {
                for ($i = 2; $i <= $count; $i++) {
                    $pos = $this->strNpos($children_all, "(", $i);
                    $r   = substr($children_all, 0, $pos + 1);
                    if (substr_count($r, ")") == ($i - 1)) {
                        $end      = $this->strNpos($children_all, ")", $i - 1);
                        $children = substr($children_all, 0, $end + 1);
                        break;
                    }
                }
                if ($children == false) {
                    $end      = $this->strNpos($children_all, ")", $count + 1);
                    $children = substr($children_all, 0, $end);
                }
            }
        }
        return $children;
    }


    //sql 拆解
    public function explainSql()
    {
        $sql      = $this->sql;
        $children = $this->getChildrenVal($sql);
        if ($children) {
            $children_data = $this->getChildrenData(trim(trim($children, ")"), "("));
            $sql           = str_replace($children, "(" . $children_data . ")", $sql);
        }
        $sql_from = explode("|", preg_replace('/\bfrom\b/', "|", $sql));
        if (count($sql_from) < 2) {
            $this->error = "查询错误";
        } else {
            //CString::echof($sql_from);exit;
            $sql_from[1]             = $this->explainLimit($sql_from[1]);
            $this->select            = str_replace("select", "", $sql_from[0]);
            $sql_from_where_array    = explode("|", preg_replace('/\bwhere\b/', "|", $sql_from[1]));
            $from                    = explode("|", preg_replace(['/\b(left|right)\b/', '/\b(join)\b/'], ["", "|"], $sql_from_where_array[0]));
            $this->where             = isset($sql_from_where_array[1]) ? $sql_from_where_array[1] : false;
            $this->table             = $this->formChange(array_shift($from));
            $this->join_string_array = $from;
        }
    }

    //大写表替换
    public function formChange($table)
    {
        $tab = self::$table_big;
        foreach ($tab as $v) {
            $table = preg_replace('/\b(' . $v . ')\b/i', $v, $table);
        }
        return $table;
    }

    /**
     * @desc 拆分limit  和 order by
     */
    public function explainLimit($sql)
    {
        $order_match = '/\b(order)\b\s+\b(by)\b\s+\w+\s*(?<da>(desc|asc)?)(\s*,\s*\w+)*\s*\k<da>?/';
        preg_match($order_match, $sql, $order);
        if (count($order)) {
            $this->order = preg_replace('/\b(order)\b\s+\b(by)\b/', "", $order[0]);
            $sql         = preg_replace($order_match, "", $sql);
        }
        preg_match('/\b(limit)\b\s+(\d+)/', $sql, $limit);
        if (count($limit)) {
            $this->limit = preg_replace('/\b(limit)\b/', "", $limit[0]);
            $sql         = preg_replace('/\b(limit)\b\s+(\d+)/', "", $sql);
        }
        $group_match = '/\b(group)\b\s+\b(by)\b\s+(\w+)(\s*,\s*\w+)*/';
        preg_match($group_match, $sql, $group);
        if (count($group)) {
            $this->group = preg_replace('/\b(group)\b\s+\b(by)\b/', "", $group[0]);
            $sql         = preg_replace($group_match, "", $sql);
        }
        preg_match('/\b(having)\b.*/', $sql, $having);
        if ($having) {
            $this->having = str_replace("having", "", $having[0]);
            $sql          = str_replace($having[0], "", $sql);
        }
        return $sql;
    }

    public function joinToArray()
    {
        if ($this->join_string_array) {
            foreach ($this->join_string_array as $val) {
                if (trim($val)) {
                    $this->join_array[] = explode("on", $val);
                }
            }
        }
    }

    //数据库检测
    public function checkDb()
    {
        if (in_array($this->database, self::$db_array) == false) {
            $this->error = $this->database . "数据库没有权限";
        }
    }

    //from 检测
    public function checkTable()
    {
        $not_table = self::$not_db_table[$this->database];
        $table     = $this->getTable($this->table);
        if (in_array($table, $not_table)) {
            $this->error = $this->table . "表无权限查询";
        }
        if ($this->join_array) {
            foreach ($this->join_array as $val) {
                $table = $this->getTable(trim($val[0]));
                $table = $this->getTable($table);
                if (in_array($table, $not_table)) {
                    $this->error = $this->table . "表无权限查询";
                }
            }
        }
    }

    //数据校验
    public function checkAuth()
    {
        $this->checkDb();
        $this->checkTable();
    }

    //获取表名
    public function getTable($table)
    {
        $table = str_replace('``', "", $table);
        preg_match("/\b\S+\b/", $table, $select_table);
        return $select_table[0];
    }

    public function getNotParams($table)
    {
        return isset(self::$not_params[$this->database][$table]) ? self::$not_params[$this->database][$table] : [];
    }

    //字段过滤
    public function checkParams($data)
    {
        //table
        $table = $this->getTable($this->table);
        if ($table) {
            $now_params = $this->getNotParams($table);
            //关联表
            if ($this->join_array) {
                foreach ($this->join_array as $val) {
                    $table  = $this->getTable(trim($val[0]));
                    $params = $this->getNotParams($table);
                    if ($params) {
                        $now_params = array_merge($now_params, $params);
                    }
                }
            }

            foreach ($data as $i => $detail) {
                if (isset($detail['id']) == false) {
                    $data[$i]['id'] = ($this->select_page - 1) * $this->select_limit + 1 + $i;
                    //$data[$i]['id'] = $i;
                }
                foreach ($detail as $key => $val) {
                    if ($now_params) {
                        foreach ($now_params as $not_val) {
                            if ($key == $not_val) {
                                unset($data[$i][$key]);
                            }
                        }
                    }
                }
            }
        }
        $file_array = [];
        if ($this->select_page >= 2) {
            $file_array = array_fill(0, ($this->select_page - 1) * $this->select_limit, ["id" => 1]);
        }
        return array_merge($file_array, $data);
    }

    /**
     * @desc 获取子查询数据
     */
    public function getChildrenData($sql)
    {
        $return = "";
        try {
            $offset = 0;
            while (true) {
                $base = new ZSelectSql($this->database, $sql);
                $base->explainSql();
                //$base = clone $base_new;
                if ($base->table)
                    $base->commend->from("{$base->table}");
                if ($base->where)
                    $base->commend->Where($base->where);
                if ($base->join_array) {
                    foreach ($base->join_array as $val) {
                        $base->commend->join(trim($val[0]), trim($val[1]));
                    }
                }
                if ($base->group)
                    $base->commend->group = $base->group;
                if ($base->select)
                    $base->commend->select = $base->select;
                if ($base->having)
                    $base->commend->having = $base->having;
                $base->commend->offset = $offset;
                $base->commend->limit  = 1000;
                //CString::echof($base->commend->getText());
                $data = $base->commend->queryAll();
                if (empty($data) || (isset($data[0]) && empty($data[0])))
                    break;
                $offset = $offset + 1000;
                //CString::echof($data);
                foreach ($data as $children_val) {
                    $children_val = array_values($children_val);
                    $return       = $return . $children_val[0] . ",";
                }
            }
        } catch (Exception $e) {
            //CString::echof($e->getMessage());exit;
        }
        return trim($return, ",");
    }

    //数据查询
    public function search()
    {
        $count = 0;
        $data  = [];
        $this->explainSql();
        if ($this->error == false)
            $this->checkAuth();
        if ($this->error == false)
            $this->joinToArray();
        if ($this->error == false) {
            try {
                if ($this->table)
                    $this->commend->from("{$this->table}");
                if ($this->where)
                    $this->commend->andWhere($this->where);
                if ($this->join_array) {
                    foreach ($this->join_array as $val) {
                        $this->commend->join(trim($val[0]), trim($val[1]));
                    }
                }
                if ($this->order)
                    $this->commend->order = $this->order;
                if ($this->group)
                    $this->commend->group = $this->group;
                if ($this->select)
                    $this->commend->select = $this->select;
                if ($this->having)
                    $this->commend->having = $this->having;
                //查询总条数
                $count = $this->getCount();
                //若是总数大于分页数
                if ($count >= $this->select_limit) {
                    $offset = 0;
                    if (isset($_GET['page'])) {
                        $this->select_page = $_GET['page'];
                        $offset            = $_GET['page'] - 1;
                    }
                    if (($offset + 1) * $this->select_limit > $count) {
                        $this->commend->limit = abs($offset * $this->select_limit - $count);
                    } else {
                        $this->commend->limit = $this->select_limit;
                    }
                    $this->commend->offset = $offset * $this->select_limit;
                } else {
                    if (isset($_GET['page'])) {
                        $this->commend->offset = ($_GET['page'] - 1) * $this->select_limit;
                    }
                    if ($this->limit)
                        $this->commend->limit = $this->limit;
                }
                //CString::echof($this->commend->_query);exit;
                $data = $this->commend->queryAll();
                //CString::echof($data);exit;
                if ($data)
                    $data = $this->checkParams($data);
            } catch (Exception $e) {
                try {
                    $table_sql = $this->getNotTab($this->sql);
                    if (empty($old_sql)) {
                        $data               = $this->db->createCommand($this->old_sql)->queryAll();
                        $data               = $this->checkParams($data);
                        $this->select_limit = 1000;
                    } else {
                        $this->error = $table_sql[0] . "无查询权限";
                    }

                } catch (Exception $e) {
                    $this->error = $e->getMessage();
                }
            }
        }
        return new ArrayDataProvider($data, array(
            'id'         => 'id',
            "self_count" => $count,
            'pagination' => array(
                'pageSize' => $this->select_limit,
                'pageVar'  => 'page'
            ),
        ));
    }

    public function getNotTab($sql)
    {
        $r = "";
        foreach (self::$not_db_table as $val) {
            foreach ($val as $v) {
                $r = $r . "|" . $v;
            }
        }
        $r = trim($r, "|");
        preg_match('/\b(' . $r . ')\b/', $sql, $oll);
        return $oll;
    }

    //获取总条数
    public function getCount()
    {
        $commend = clone $this->commend;
        $old     = $commend->select;
        preg_match('/\b(sum|count)\(\b/', $old, $oll);
        if (empty($oll)) {
            $commend->select = "count(*) as count_num";
            $r               = $commend->queryScalar();
        } else {
            $r = count($commend->queryAll());
        }
        if ($this->limit)
            $r = min($this->limit, $r);
        return $r;
    }


 
}