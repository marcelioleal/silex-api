<?php

namespace Api\Core\Authenticator;

abstract class Authenticator
{
    public $app;
    public $request;
    public $data;
    public $publicKey;
    public $hash;
    
    abstract function run();
    
    public function __construct($app, $request)
    {
        $this->app = $app;
        $this->request = $request;

        $this->container    = $app['sa-container'];
        $this->data         = \Util\Json::decodeArray($request->get('json-data'), true);
        $this->publicKey    = $request->get('public-key');
        $this->hash         = $request->get('hash');
        
        $this->setOutput();
    }
    
    public function hash($key = '')
    {
        /**
         * Authentication code goes here.
         */
        $serverKey = [
            'private' => 'sample_key',
            'public' => md5('sample_key')
        ];
        
        if (!($key === md5($serverKey['sample_key']))) {
            throw new Exception('AUTH_INVALID_PUBLIC_KEY_MESSAGE', 'AUTH_INVALID_PUBLIC_KEY_CODE');
        }
        
        return hash_hmac('sha256', $this->request->get('json-data'), $keys['private_key'].$key);
    }

    protected function setOutput()
    {
        $this->app['auth.user'] = null;
    }
}