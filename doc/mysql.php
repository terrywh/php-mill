<?php
/**
 * 
 */
namespace flame\mysql;
/**
 * 连接 MySQL 服务器
 * @param string $url 服务端地址, 形如:
 *  mysql://{user}:{pass}@{host}:{port}/{database}?opt1=val1
 *  目前可用的选项如下:
 *      * "charset" => 字符集
 * 
 * @return client 客户端对象
 */
function connect($url): client {}

/**
 * WHERE 字句语法规则:
 * 
 * @example
 *  $where = ["a"=>"1", "b"=>[2,"3","4"], "c"=>null, "d"=>["{!=}"=>5]];
 *  // " WHERE (`a`='1' && `b`  IN ('2','3','4') && `c` IS NULL && `d`!='5')"
 * @example
 *  $where = ["{OR}"=>["a"=>["{!=}"=>1], "b"=>["{><}"=>[1, 10, 3]], "c"=>["{~}"=>"aaa%"]]];
 *  // $sql == " WHERE (`a`!='1' || `b` NOT BETWEEN 1 AND 10 || `c` LIKE 'aaa%')"
 * @example
 *  $where = "`a`=1";
 *  // $sql == " WHERE `a`=1";
 * 
 * @param 特殊的符号均以 "{}" 进行包裹, 存在以下可用项:
 *  * `{NOT}` / `{!}` - 逻辑非, 对逻辑子句取反, 生成形式: `NOT (.........)`;
 *  * `{OR}` / `{||}` - 逻辑或, 对逻辑子句进行逻辑或拼接, 生成形式: `... || ... || ...`;
 *  * `{AND}` / `{||}` - 逻辑与, 对逻辑子句进行逻辑与拼接 (默认拼接方式), 生成形式: `... && ... && ...`;
 *  * `{!=}` - 不等, 生成形式: `...!='...'` / ` ... IS NOT NULL`;
 *  * `{>}` - 大于, 生成形式: `...>...`;
 *  * `{<}` - 小于, 生成形式: `...<...`;
 *  * `{>=}` - 大于等于, 生成形式: `...>=...`;
 *  * `{<=}` - 小于等于, 生成形式: `...<=...`;
 *  * `{<>}` - 区间内, 生成形式: `... BETWEEN ... AND ...`, 目标数组至少存在两个数值;
 *  * `{><}` - 区间外, 生成形式: `... NOT BETWEEN ... AND ...`, 目标数组至少存在两个数值;
 *  * `{~}` - 模糊正匹配, 生成形式: `... LIKE ...`;
 *  * `{!~}` - 模糊非匹配, 生成形式: `... NOT LIKE ...`;
 */

/**
 * ORDER 子句语法规则:
 * @example
 *  $order = "`a` ASC, `b` DESC";
 *  // $sql == " ORDER BY `a` ASC, `b` DESC");
 * @example
 *  $order = ["a"=>1, "b"=>-1, "c"=>true, "d"=>false, "e"=>"ASC", "f"=>"DESC"];
 *  // $sql == " ORDER BY `a` ASC, `b` DESC, `c` ASC, `d` DESC, `e` ASC, `f` DESC"
 * 
 * @param
 *  * 当 value 位正数或 `true` 时, 生成 `ASC`; 否则生成 `DESC`;
 *  * 当 value 为文本时, 直接拼接;
 */

/**
 * LIMIT 子句语法规则:
 * @example
 *  $limit = 10;
 *  // $sql == " LIMIT 10"
 * @example
 *  $limit = [10,10];
 *  // $sql == " LIMIT 10, 10"
 * @example
 *  $limit = "20, 300";
 *  // $sql == " LIMIT 20, 300"
 *
 * @param
 *  * 当 $limit 值位文本或数值时, 直接拼接;
 *  * 当 $limit 为数组时, 拼接前两个值;
 */


/**
 * MySQL 客户端
 */
class client {
    /**
     * 返回事务对象 (绑定在一个连接上)
     */
    function begin_tx(): ?tx {}
    /**
     * 执行制定的 SQL 查询, 并返回结果
     * @param string $sql 
     * @return result 结果对象
     */
    function query(string $sql): result {}
    /**
     * 向指定表插入一行或多行数据
     * @param string $table 表名
     * @param array $data 待插入数据, 多行关联数组插入多行数据(以首行 KEY 做字段名); 普通关联数组插入一行数据;
     */
    function insert(string $table, array $data): result {}
    /**
     * 从指定表格删除数
     * @param string $table 表明
     * @param mixed $where WHERE 子句, 请参考上文;
     * @param mixed $order ORDER 子句, 请参考上文;
     * @param mixed $limit LIMIT 子句, 请参考上文;
     */
    function delete(string $table, mixed $where, mixed $order, mixed $limit): result {}
    /**
     * 更新指定表
     * @param string $table 表名
     * @param mixed $where WHERE 子句, 请参考上文;
     * @param mixed $modify 当 $modify 为字符串时, 直接拼接 "UPDATE ... SET $modify", 否则按惯例数组进行 KEY = VAL 拼接;
     * @param mixed $order ORDER 子句, 请参考上文;
     * @param mixed $limit LIMIT 子句, 请参考上文;
     */
    function update(string $table, mixed $where, mixed $modify, mixed $order, mixed $limit): result {}
    /**
     * 从指定表筛选获取数据
     * @param string $table 表名
     * @param mixed $fields 待选取字段, 为数组时其元素表示各个字段; 文本时直接拼接在 "SELECT $fields FROM $table"; 
     *  关联数组的 KEY 表示对应函数, 仅可用于简单函数调用, 例如:
     *      "SUM" => "a"
     *  表示:
     *      SUM(`a`)
     * @param mixed $where WHERE 子句, 请参考上文;
     * @param mixed $order ORDER 子句, 请参考上文;
     * @param mixed $limit LIMIT 子句, 请参考上文;
     */
    function select(string $table, mixed $fields, array $where, mixed $order, mixed $limit): result {}
    /**
     * 从指定表筛选获取一条数据, 并立即返回该行数据
     */
    function one(string $table, mixed $where, mixed $order): array {}
    /**
     * 从指定表获取一行数据的指定字段值
     */
    function get(string $table, string $field, array $where, mixed $order): mixed {}
    
};

/**
 * 事务对象, 与 client 对象接口基本相同
 */
class tx {
    /**
     * 提交当前事务
     */
    function commit() {}
    /**
     * 回滚当前事务
     */
    function rollback() {}
    /**
     * @see client::query()
     */
    function query(string $sql): result {}
    /**
     * @see client::insert()
     */
    function insert(string $table, array $data): result {}
    /**
     * @see client::update()
     */
    function update(string $table, array $where, mixed $modify, mixed $order = null, mixed $limit = null): result {}
    /**
     * @see client::select()
     */
    function select(string $table, mixed $fields, array $where, mixed $order = null, mixed $limit = null): result {}
    /**
     * @see client::one()
     */
    function one(string $table, array $where, mixed $order = null): array {}
    /**
     * @see client::get()
     */
    function get(string $table, string $field, array $where, mixed $order = null): mixed {}
};

class result {
    /**
     * @property Integer
     */
    public $affected_rows;
    /**
     * @property Integer
     */
    public $insert_id;
    /**
     * 取出下一行
     * @return 下一行数据关联数组;
     *  若不存在下一行, 返回 NULL
     */
    function fetch_row():?array {}
    /**
     * 取出(剩余)全部行
     * @return 二维数组, 可能为空数组; 
     *  若当前对象不包含结果集(更新型查询), 返回 NULL
     */
    function fetch_all():?array {}
};