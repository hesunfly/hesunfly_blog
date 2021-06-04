<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Utils;

use Hyperf\DbConnection\Db;

/**
 * 通用的树型类.
 */
class Tree
{
    public $options = [];

    /**
     * 生成树型结构所需要的2维数组.
     * @var array
     */
    public $arr = [];

    /**
     * 生成树型结构所需修饰符号，可以换成图片.
     * @var array
     */
    public $icon = ['│', '├', '└'];

    public $nbsp = '&nbsp;';

    public $pidname = 'pid';

    protected static $instance;

    //默认配置
    protected $config = [];

    public function __construct($options = [])
    {
        if ($config = config('bxy.tree')) {
            $this->options = array_merge($this->config, $config);
        }
        $this->options = array_merge($this->config, $options);
    }

    /**
     * 初始化.
     * @param array $options 参数
     * @return Tree
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 初始化方法.
     * @param array 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','pid'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','pid'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','pid'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','pid'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','pid'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','pid'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','pid'=>3,'name'=>'三级栏目二')
     *      )
     * @param mixed $arr
     * @param null|mixed $pidname
     * @param null|mixed $nbsp
     */
    public function init($arr = [], $pidname = null, $nbsp = null)
    {
        $this->arr = $arr;
        if (!is_null($pidname)) {
            $this->pidname = $pidname;
        }
        if (!is_null($nbsp)) {
            $this->nbsp = $nbsp;
        }
        return $this;
    }

    /**
     * 得到子级数组.
     * @param int
     * @param mixed $myid
     * @return array
     */
    public function getChild($myid)
    {
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value[$this->pidname] == $myid) {
                $newarr[$value['id']] = $value;
            }
        }
        return $newarr;
    }

    /**
     * 读取指定节点的所有孩子节点.
     * @param int $myid 节点ID
     * @param bool $withself 是否包含自身
     * @return array
     */
    public function getChildren($myid, $withself = false)
    {
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value[$this->pidname] == $myid) {
                $newarr[] = $value;
                $newarr = array_merge($newarr, $this->getChildren($value['id']));
            } elseif ($withself && $value['id'] == $myid) {
                $newarr[] = $value;
            }
        }
        return $newarr;
    }

    /**
     * 读取指定节点的所有孩子节点ID.
     * @param int $myid 节点ID
     * @param bool $withself 是否包含自身
     * @return array
     */
    public function getChildrenIds($myid, $withself = false)
    {
        $childrenlist = $this->getChildren($myid, $withself);
        $childrenids = [];
        foreach ($childrenlist as $k => $v) {
            $childrenids[] = $v['id'];
        }
        return $childrenids;
    }

    public function getChildrenIdArr($myid, $withself = false)
    {
        $childrenlist = $this->getChildren($myid, $withself);
        $childrenids = [];
        foreach ($childrenlist as $k => $v) {
            if ($v['department_code'] == 'CS') {
                continue;
            }
            $childrenids[] = $v['id'];
        }
        return $childrenids;
    }

    /**
     * 得到当前位置父辈数组.
     * @param int
     * @param mixed $myid
     * @return array
     */
    public function getParent($myid)
    {
        $pid = 0;
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value['id'] == $myid) {
                $pid = $value[$this->pidname];
                break;
            }
        }
        if ($pid) {
            foreach ($this->arr as $value) {
                if ($value['id'] == $pid) {
                    $newarr[] = $value;
                    break;
                }
            }
        }
        return $newarr;
    }

    /**
     * 得到当前位置所有父辈数组.
     * @param int
     * @param mixed $myid
     * @param mixed $withself
     * @return array
     */
    public function getParents($myid, $withself = false)
    {
        $pid = 0;
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value['id'] == $myid) {
                if ($withself) {
                    $newarr[] = $value;
                }
                $pid = $value[$this->pidname];
                break;
            }
        }
        if ($pid) {
            $arr = $this->getParents($pid, true);
            $newarr = array_merge($arr, $newarr);
        }
        return $newarr;
    }

    /**
     * 读取指定节点所有父类节点ID.
     * @param int $myid
     * @param bool $withself
     * @return array
     */
    public function getParentsIds($myid, $withself = false)
    {
        $parentlist = $this->getParents($myid, $withself);
        $parentsids = [];
        foreach ($parentlist as $k => $v) {
            $parentsids[] = $v['id'];
        }
        return $parentsids;
    }

    /**
     * 树型结构Option.
     * @param int $myid 表示获得这个ID下的所有子级
     * @param string $itemtpl 条目模板 如："<option value=@id @selected @disabled>@spacer@name</option>"
     * @param mixed $selectedids 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param mixed $disabledids 被禁用的ID，比如在做树型下拉框的时候需要用到
     * @param string $itemprefix 每一项前缀
     * @param string $toptpl 顶级栏目的模板
     * @return string
     */
    public function getTree(
        $myid,
        $itemtpl = '<option value=@id @selected @disabled>@spacer@name</option>',
        $selectedids = '',
        $disabledids = '',
        $itemprefix = '',
        $toptpl = ''
    ) {
        $ret = '';
        $number = 1;
        $childs = $this->getChild($myid);
        if ($childs) {
            $total = count($childs);
            foreach ($childs as $value) {
                $id = $value['id'];
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                    $k = $itemprefix ? $this->nbsp : '';
                } else {
                    $j .= $this->icon[1];
                    $k = $itemprefix ? $this->icon[0] : '';
                }
                $spacer = $itemprefix ? $itemprefix . $j : '';
                $selected = $selectedids && in_array(
                    $id,
                    (is_array($selectedids) ? $selectedids : explode(',', $selectedids))
                ) ? 'selected' : '';
                $disabled = $disabledids && in_array(
                    $id,
                    (is_array($disabledids) ? $disabledids : explode(',', $disabledids))
                ) ? 'disabled' : '';
                $value = array_merge($value, ['selected' => $selected, 'disabled' => $disabled, 'spacer' => $spacer]);
                $value = array_combine(
                    array_map(
                        function ($k) {
                            return '@' . $k;
                        },
                        array_keys($value)
                    ),
                    $value
                );
                $nstr = strtr(
                    (($value["@{$this->pidname}"] == 0 || $this->getChild($id)) && $toptpl ? $toptpl : $itemtpl),
                    $value
                );
                $ret .= $nstr;
                $ret .= $this->getTree(
                    $id,
                    $itemtpl,
                    $selectedids,
                    $disabledids,
                    $itemprefix . $k . $this->nbsp,
                    $toptpl
                );
                ++$number;
            }
        }
        return $ret;
    }

    /**
     * 树型结构UL.
     * @param int $myid 表示获得这个ID下的所有子级
     * @param string $itemtpl 条目模板 如："<li value=@id @selected @disabled>@name @childlist</li>"
     * @param string $selectedids 选中的ID
     * @param string $disabledids 禁用的ID
     * @param string $wraptag 子列表包裹标签
     * @param mixed $wrapattr
     * @return string
     */
    public function getTreeUl($myid, $itemtpl, $selectedids = '', $disabledids = '', $wraptag = 'ul', $wrapattr = '')
    {
        $str = '';
        $childs = $this->getChild($myid);
        if ($childs) {
            foreach ($childs as $value) {
                $id = $value['id'];
                unset($value['child']);
                $selected = $selectedids && in_array(
                    $id,
                    (is_array($selectedids) ? $selectedids : explode(',', $selectedids))
                ) ? 'selected' : '';
                $disabled = $disabledids && in_array(
                    $id,
                    (is_array($disabledids) ? $disabledids : explode(',', $disabledids))
                ) ? 'disabled' : '';
                $value = array_merge($value, ['selected' => $selected, 'disabled' => $disabled]);
                $value = array_combine(
                    array_map(
                        function ($k) {
                            return '@' . $k;
                        },
                        array_keys($value)
                    ),
                    $value
                );
                $nstr = strtr($itemtpl, $value);
                $childdata = $this->getTreeUl($id, $itemtpl, $selectedids, $disabledids, $wraptag, $wrapattr);
                $childlist = $childdata ? "<{$wraptag} {$wrapattr}>" . $childdata . "</{$wraptag}>" : '';
                $str .= strtr($nstr, ['@childlist' => $childlist]);
            }
        }
        return $str;
    }

    /**
     * 菜单数据.
     * @param int $myid
     * @param string $itemtpl
     * @param mixed $selectedids
     * @param mixed $disabledids
     * @param string $wraptag
     * @param string $wrapattr
     * @param int $deeplevel
     * @return string
     */
    public function getTreeMenu(
        $myid,
        $itemtpl,
        $selectedids = '',
        $disabledids = '',
        $wraptag = 'ul',
        $wrapattr = '',
        $deeplevel = 0
    ) {
        $str = '';
        $childs = $this->getChild($myid);
        if ($childs) {
            foreach ($childs as $value) {
                $id = $value['id'];
                unset($value['child']);
                $selected = in_array(
                    $id,
                    (is_array($selectedids) ? $selectedids : explode(',', $selectedids))
                ) ? 'selected' : '';
                $disabled = in_array(
                    $id,
                    (is_array($disabledids) ? $disabledids : explode(',', $disabledids))
                ) ? 'disabled' : '';
                $value = array_merge($value, ['selected' => $selected, 'disabled' => $disabled]);
                $value = array_combine(
                    array_map(
                        function ($k) {
                            return '@' . $k;
                        },
                        array_keys($value)
                    ),
                    $value
                );
                $bakvalue = array_intersect_key($value, array_flip(['@url', '@caret', '@class']));
                $value = array_diff_key($value, $bakvalue);
                $nstr = strtr($itemtpl, $value);
                $value = array_merge($value, $bakvalue);
                $childdata = $this->getTreeMenu(
                    $id,
                    $itemtpl,
                    $selectedids,
                    $disabledids,
                    $wraptag,
                    $wrapattr,
                    $deeplevel + 1
                );
                $childlist = $childdata ? "<{$wraptag} {$wrapattr}>" . $childdata . "</{$wraptag}>" : '';
                $childlist = strtr($childlist, ['@class' => $childdata ? 'last' : '']);
                $value = [
                    '@childlist' => $childlist,
                    '@url' => $childdata || !isset($value['@url']) ? 'javascript:;' : url($value['@url']),
                    '@addtabs' => $childdata || !isset($value['@url']) ? '' : (stripos(
                            $value['@url'],
                            '?'
                        ) !== false ? '&' : '?') . 'ref=addtabs',
                    '@caret' => ($childdata && (!isset($value['@badge']) || !$value['@badge']) ? '<i class="fa fa-angle-left"></i>' : ''),
                    '@badge' => isset($value['@badge']) ? $value['@badge'] : '',
                    '@class' => ($selected ? ' active' : '') . ($disabled ? ' disabled' : '') . ($childdata ? ' treeview' : ''),
                ];
                $str .= strtr($nstr, $value);
            }
        }
        return $str;
    }

    /**
     * 特殊.
     * @param int $myid 要查询的ID
     * @param string $itemtpl1 第一种HTML代码方式
     * @param string $itemtpl2 第二种HTML代码方式
     * @param mixed $selectedids 默认选中
     * @param mixed $disabledids 禁用
     * @param string $itemprefix 前缀
     * @return string
     */
    public function getTreeSpecial($myid, $itemtpl1, $itemtpl2, $selectedids = 0, $disabledids = 0, $itemprefix = '')
    {
        $ret = '';
        $number = 1;
        $childs = $this->getChild($myid);
        if ($childs) {
            $total = count($childs);
            foreach ($childs as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                    $k = $itemprefix ? $this->nbsp : '';
                } else {
                    $j .= $this->icon[1];
                    $k = $itemprefix ? $this->icon[0] : '';
                }
                $spacer = $itemprefix ? $itemprefix . $j : '';
                $selected = $selectedids && in_array(
                    $id,
                    (is_array($selectedids) ? $selectedids : explode(',', $selectedids))
                ) ? 'selected' : '';
                $disabled = $disabledids && in_array(
                    $id,
                    (is_array($disabledids) ? $disabledids : explode(',', $disabledids))
                ) ? 'disabled' : '';
                $value = array_merge($value, ['selected' => $selected, 'disabled' => $disabled, 'spacer' => $spacer]);
                $value = array_combine(
                    array_map(
                        function ($k) {
                            return '@' . $k;
                        },
                        array_keys($value)
                    ),
                    $value
                );
                $nstr = strtr(!isset($value['@disabled']) || !$value['@disabled'] ? $itemtpl1 : $itemtpl2, $value);

                $ret .= $nstr;
                $ret .= $this->getTreeSpecial(
                    $id,
                    $itemtpl1,
                    $itemtpl2,
                    $selectedids,
                    $disabledids,
                    $itemprefix . $k . $this->nbsp
                );
                ++$number;
            }
        }
        return $ret;
    }

    /**
     * 获取树状数组.
     * @param string $myid 要查询的ID
     * @param string $itemprefix 前缀
     * @return array
     */
    public function getTreeArray($myid, $itemprefix = '')
    {
        $childs = $this->getChild($myid);
        $n = 0;
        $data = [];
        $number = 1;
        if ($childs) {
            $total = count($childs);
            foreach ($childs as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                    $k = $itemprefix ? $this->nbsp : '';
                } else {
                    $j .= $this->icon[1];
                    $k = $itemprefix ? $this->icon[0] : '';
                }
                $data[$n] = $value;
                $data[$n]['children'] = $this->getTreeArray($id, $itemprefix . $k . $this->nbsp);
                ++$n;
                ++$number;
            }
        }
        return $data;
    }

    public function getTreeBuildArray($myid, $house_sale_rent)
    {
        $childs = $this->getChild($myid);
        $n = 0;
        $data = [];
        if ($childs) {
            $room_no_arr = [];
            foreach ($childs as $item) {
                if ($item['level'] == 4) {
                    $room_no_arr[] = $item['id'];
                }
            }
            $room_status_temp = [];
            if ($room_no_arr) {
                $room_status_res = Db::table('t_shop')
                    ->where('del_flag', 1)
                    ->whereIn('room_no', $room_no_arr)
                    ->where('house_sale_rent', $house_sale_rent)
                    ->selectRaw('house_status,house_sale_rent,room_no,id')
                    ->get()->toArray();

                foreach ($room_status_res as $item) {
                    $room_status_temp[$item['room_no']] = $item;
                }
            }

            foreach ($childs as $id => $value) {
                $data[$n] = $value;
                if ($value['level'] == 4) {
                    $data[$n]['room_status'] = 5;
                    $data[$n]['house_sale_rent'] = $house_sale_rent;
                    $room_status = $room_status_temp[$id] ?? null;
                    if (isset($room_status['id'])) {
                        if ($room_status['house_sale_rent'] == 1) {
                            if (in_array($room_status['house_status'], [1, 2, 3])) {
                                $data[$n]['room_status'] = 1;
                            } elseif (in_array($room_status['house_status'], [4, 10])) {
                                $data[$n]['room_status'] = 2;
                            } elseif (in_array($room_status['house_status'], [5, 6, 7])) {
                                $data[$n]['room_status'] = 3;
                            }
                        }
                        if ($room_status['house_sale_rent'] == 2) {
                            if (in_array($room_status['house_status'], [1, 2, 8])) {
                                $data[$n]['room_status'] = 7;
                            } elseif (in_array($room_status['house_status'], [9, 10])) {
                                $data[$n]['room_status'] = 8;
                            } elseif (in_array($room_status['house_status'], [5, 6, 7])) {
                                $data[$n]['room_status'] = 9;
                            }
                        }
                    }
                }


                if ($value['level'] == 4 && isset($data[$n]['room_status']) && $data[$n]['room_status'] == 5) {
                    unset($data[$n]);
                } else {
                    $tree_res = $this->getTreeBuildArray($id, $house_sale_rent);
                    if (! $tree_res && ($value['level'] == 3 || $value['level'] == 2 || $value['level'] == 1)) {
                        unset($data[$n]);
                        continue;
                    }
                    $data[$n]['children'] = $tree_res;
                    ++$n;
                }
            }
        }
        return $data;
    }

    public function getTreeMenuArray($myid, $menu_arr = [], $permission_id = [])
    {
        $childs = $this->getChild($myid);
        $n = 0;
        $data = [];
        if ($childs) {
            foreach ($childs as $id => $value) {
                $value['check'] = false;
                if (in_array($value['id'], $menu_arr)) {
                    $value['check'] = true;
                }
                if ($value['permission']) {
                    foreach ($value['permission'] as &$item) {
                        $item['check'] = false;
                        if (in_array($item['id'], $permission_id)) {
                            $item['check'] = true;
                        }
                    }
                }
                $data[$n] = $value;
                $data[$n]['children'] = $this->getTreeMenuArray($id, $menu_arr, $permission_id);
                ++$n;
            }
        }
        return $data;
    }

    /**
     * 将getTreeArray的结果返回为二维数组.
     * @param array $data
     * @param mixed $field
     * @return array
     */
    public function getTreeList($data = [], $field = 'name')
    {
        $arr = [];
        foreach ($data as $k => $v) {
            $childlist = isset($v['childlist']) ? $v['childlist'] : [];
            unset($v['childlist']);
            $v[$field] = $v['spacer'] . ' ' . $v[$field];
            $v['haschild'] = $childlist ? 1 : 0;
            if ($v['id']) {
                $arr[] = $v;
            }
            if ($childlist) {
                $arr = array_merge($arr, $this->getTreeList($childlist, $field));
            }
        }
        return $arr;
    }

    public function getTreeArrayAddValue($dept_id, $data_arr)
    {
        $list = $this->getChildren($dept_id, true);
        //创建Tree
        $tree = [];
        if (is_array($list)) {
            //创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data['id']] = &$list[$key];
                $refer[$data['id']]['record'] = $data_arr[$data['id']];
            }
            foreach ($list as $key => $data) {
                //判断是否存在parent
                $id = $data['id'];
                $parantId = $data['parent_id'];
                if ($dept_id == $id) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parantId])) {
                        $parent = &$refer[$parantId];
                        $parent['children'][] = &$list[$key];
                    }
                }
            }
        }

        //如果数据存在市级的，将其去除
        $dept_code = array_unique(array_column($tree, 'department_code'));
        if (in_array('CS', $dept_code)) {
            foreach ($tree as $item) {
                if ($item['department_code'] == 'CS') {
                    $tree = $item['children'];
                    break;
                }
            }
        }

        return $tree;
    }

}