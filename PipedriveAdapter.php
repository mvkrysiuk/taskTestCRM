<?php

class PipedriveAdapter
{
    protected $apiPath = 'https://api.pipedrive.com/v1/';
    private $token = '';

    public function __construct($token = '')
    {
        if (!empty($token)) {
            $this->setToken($token);
        }
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this -> token = $token;
    }

    public function getEntityRequest($entityName, $params = [], $start = 0, $limit = 50)
    {
        if ($entityData =
            file_get_contents($this->apiPath . $entityName  . '?' . trim(implode('&', $params),
                    '&') . "&start=$start&limit=$limit&api_token=" . $this->token, true)) {
            return $entityData = json_decode($entityData, true)['data'];
        }
        return [];
    }
}