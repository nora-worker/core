<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Core\Util\Collection;

/**
 * ハッシュクラス
 */
class Hash
{
    private $_array;
    private $_flg = 0;
    const NO_CASE_SENSITIVE = 1;

    /**
     * 作成する
     */
    static public function create ($flg = 0)
    {
        $hash = new static($flg);

        for($i=1; $i<func_num_args(); $i++)
        {
            $hash->set(func_get_arg($i));
        }

        return $hash;
    }

    /**
     * コンストラクタ
     */
    public function __construct ($flg = 0)
    {
        $this->_flg = $flg;

        // 保存用
        $this->_array = [];
    }

    /**
     * データ・セット
     *
     * 第一引数に配列を与えれば
     * 複数件を一括でセットできる
     *
     * @param string $name
     * @param mixed $value
     */
    public function set ($name, $value = null)
    {
        if (is_array($name))
        {
            foreach($name as $k=>$v) $this->set($k,$v);
            return $this;
        }

        $this->_array[$this->_filterName($name)] = $value;
    }

    protected function _filterName($name)
    {
        if ($this->_flg & self::NO_CASE_SENSITIVE)
        {
            $name = strtolower($name);
        }
        return $name;
    }

    /**
     * データの存在チェック
     *
     * データが設定されていれば、
     * NullでもEmptyでもTrue
     *
     * @param string $name
     * @param mixed $value
     */
    public function has ($name)
    {
        return isset(
            $this->_array[$this->_filterName($name)]
        );
    }

    /**
     * データの取得
     *
     * 参照で返す。
     * 第二引数をセットすると値が取得できなかった時の
     * 初期値として使える
     *
     * @param string $name
     * @param mixed $default
     */
    public function &get ($name, $default = null)
    {
        if ($this->has($name))
        {
            return $this->_array[$this->_filterName($name)];
        }
        return $default;
    }

    /**
     * マージする
     *
     */
    public function merge($array)
    {
        foreach($array as $k=>$v)
        {
            $this->set($k, $v);
        }
        return $this;
    }

    public function &__get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}
