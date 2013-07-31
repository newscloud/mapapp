<?php
/**
 * @author Anggiajuang Patria <anggiaj@gmail.com>
 * @link http://git.io/IkHa_Q
 * 4/1/13/11:02 AM
 */

class EGeocoder extends CApplicationComponent
{
    public $httpAdapter = 'Curl';
    public $providers = array();
    private $_geocoder;

    /**
     * @throws CException
     */
    public function init()
    {
        parent::init();

        $this->_geocoder = new \Geocoder\Geocoder();

        // build http adapter
        if ($this->httpAdapter === null) {
            throw new CException(Yii::t('geocoder', "Http adapter must be set."));
        }

        if ($this->httpAdapter instanceof \Geocoder\HttpAdapter\HttpAdapterInterface) {
            $adapter = $this->httpAdapter;
        } elseif (is_array($this->httpAdapter)) {
            if (!isset($this->httpAdapter['name'])) {
                throw new CException(Yii::t('geocoder', 'Object configuration must be an array containing a "name" element.'));
            }
            $config = array();
            $config['class'] = $this->getHttpAdapterClassName($this->httpAdapter['name']);
            unset($this->httpAdapter['name']);
            $config = CMap::mergeArray($config, $this->httpAdapter);
            $adapter = self::newInstance($config);
        } else {
            $className = $this->getHttpAdapterClassName($this->httpAdapter);
            $adapter = new $className;
        }

        // build providers
        $providers = array();
        if (0 === count($this->providers)) {
            throw new CException(Yii::t('geocoder', "No provider registered."));
        } else {
            foreach ($this->providers as $provider) {
                if ($provider instanceof \Geocoder\Provider\ProviderInterface) {
                    array_push($providers, $provider);
                } elseif (is_array($provider)) {
                    if (!isset($provider['name'])) {
                        throw new CException(Yii::t('geocoder', 'Object configuration must be an array containing a "name" element.'));
                    }
                    $config = array();
                    $config['class'] = $this->getProviderClassName($provider['name']);
                    $config['adapter'] = $adapter;
                    unset($provider['name']);
                    $config = CMap::mergeArray($config, $provider);
                    array_push($providers, self::newInstance($config));
                } else {
                    $class = $this->getProviderClassName($provider);
                    array_push($providers, new $class($adapter));
                }
            }
        }

        $chainProvider = new \Geocoder\Provider\ChainProvider($providers);
        $this->_geocoder->registerProvider($chainProvider);
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        // just to make sure ;-)
        if (!$this->getIsInitialized()) {
            $this->init();
        }
        if (method_exists($this->_geocoder, $name)) {
            return call_user_func_array(array($this->_geocoder, $name), $params);
        }
        return parent::__call($name, $params);
    }

    /**
     * @param $alias
     * @return string
     */
    public function getHttpAdapterClassName($alias)
    {
        return '\Geocoder\HttpAdapter\\' . ucfirst($alias) . 'HttpAdapter';
    }

    /**
     * @param $alias
     * @return string
     */
    public function getProviderClassName($alias)
    {
        return '\Geocoder\Provider\\' . ucfirst($alias) . 'Provider';
    }

    /**
     * Inspired by Yii::createComponent()
     * @param array $config
     * @return mixed
     * @throws CException
     */
    public static function newInstance(array $config)
    {
        if (!isset($config['class'])) {
            throw new CException(Yii::t('geocoder', 'Object configuration must be an array containing a "class" element.'));
        }
        $class = $config['class'];
        unset($config['class']);
        return call_user_func_array(array(new ReflectionClass($class), 'newInstance'), $config);
    }
}