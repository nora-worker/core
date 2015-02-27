<?php
/**
 * Nora Project
 *
 * @author Hajime MATSUMOTO <hajime@nora-worker.net>
 * @copyright 2015 nora-worker.net.
 * @licence https://www.nora-worker.net/LICENCE
 * @version 1.0.0
 */
namespace Nora\Module\Environment;

use Nora\Core\Util\Collection\Hash;

/**
 * 検知クラス
 */
class Detector implements DetectorIF
{
    private $_detectors;

    protected $env;

    public function __construct ($env)
    {
        $this->env = $env;
        $this->_detectors = new Hash(Hash::NO_CASE_SENSITIVE);
    }

    /**
     * 環境検知を実行する
     *
     * @param string $name 種別
     * @return bool 結果
     */
    public function is($name)
    {
        if ($this->_detectors->has($name))
        {
            return !!call_user_func($this->_detectors->get($name));
        }
        if (method_exists($this, '_is'.$name))
        {
            return !!call_user_func([$this, '_is'.$name]);
        }

        throw new Exception\UnknownDetectorException($name);
    }

    /**
     * 環境検知ロジックを追加する
     *
     * @param string $name
     * @param mixed $value
     */
    public function addDetector($name, $value = null)
    {
        if (is_array($name))
        {
            $names = $name;
            foreach($names as $name => $value) $this->addDetector($name, $value);
            return $this;
        }

        $this->_detectors[$name] = $value;
        return $this;
    }

    /**
     * ダミー検知
     */
    public function _isAlwaysTrue( )
    {
        return true;
    }

    /**
     * リクエストメソッド
     */
    protected function _isGet( )
    {
        return strtoupper($this->env->get('request_method', 'GET')) === 'GET';
    }
    
    /**
     * リクエストメソッド
     */
    protected function _isPost( )
    {
        return strtoupper($this->env->get('request_method')) === 'POST';
    }

    /**
     * リクエストメソッド
     */
    protected function _isDelete( )
    {
        return strtoupper($this->env->get('request_method')) === 'DELETE';
    }

    /**
     * リクエストメソッド
     */
    protected function _isPut( )
    {
        return strtoupper($this->env->get('request_method')) === 'PUT';
    }

    /**
     * リクエストがAJAXか
     */
    protected function _isAjax( )
    {
        return !$this->env->isEmpty('http_x_requested_with') && strtolower($this->env->get('http_x_requested_with')) == 'xmlhttprequest';
    }

    /**
     * ローカルネットワークからのアクセスか
     */
    protected function _isLocal( )
    {
        return $this->env->isEmpty('remote_addr') || in_array($this->env->get('remote_addr'), ['127.0.0.1', '::1']);
    }

    /**
     * ユーザエージェント
     */
    protected function _isDocomo( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'DoCoMo');
    }

    /**
     * ユーザエージェント
     */
    protected function _isAu( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'UP.Browser');
    }

    /**
     * ユーザエージェント
     */
    protected function _isSoftBank( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'SoftBank');
    }

    /**
     * ユーザエージェント
     */
    protected function _isWillcom( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'WILLCOM');
    }

    /**
     * ユーザエージェント
     */
    protected function _isEmobile( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'emobile');
    }

    /**
     * ユーザエージェント
     */
    protected function _isIPhone( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'iPhone');
    }

    /**
     * ユーザエージェント
     */
    protected function _isIPad( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'iPad');
    }

    /**
     * ユーザエージェント
     */
    protected function _isAndroid( )
    {
        return false !== strpos($this->env->get('http_user_agent'), 'Android');
    }

    /**
     * ユーザエージェント
     */
    protected function _isSp( )
    {
        return $this->is('iphone') || ($this->is('android') && false !== strpos($this->env->get('http_user_agent'), 'Mobile'));
    }

    /**
     * ユーザエージェント
     */
    protected function _isTablet( )
    {
        return $this->is('ipad') || ($this->is('android') && false === strpos($this->env->get('http_user_agent'), 'Mobile'));
    }

    /**
     * ユーザエージェント
     */
    protected function _isMobile( )
    {
        return 
            ($this->is('docomo') || $this->is('au') || $this->is('softbank') || $this->is('willcom') || $this->is('emobile'))
            && !$this->is('sp') && !$this->is('tablet');
    }

    /**
     * ユーザエージェント
     */
    protected function _isPC( )
    {
        return !$this->is('mobile') && !$this->is('tablet') && !$this->is('sp');
    }


    /**
     * リファラ
     */
    protected function _isReferer_same_domain( )
    {
        $regex = sprintf('|http[s]?://%s', preg_quote($this->env->get('HTTP_HOST'),'|').'|');
        return preg_match($regex, $this->env->get('HTTP_REFERER'));
    }
}

