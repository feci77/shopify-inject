<?php

namespace Shopify\Inject;

use App\Models\User;

class ShopifyService
{
    /** @var string $baseUrl */
    public $baseUrl;

    /** @var array $headers */
    public $headers;

    /** @var User */
    public $shop;

    public function __construct($user, $token = null)
    {
        $this->shop = $user;

        $this->headers = [
            'X-Shopify-Access-Token' => $token
        ];
        $this->baseUrl = '/admin/';
    }

    /**
     * Get All Themes of the store.
     * @return array|mixed
     */
    public function getAllThemes()
    {
        return $this->call($this->baseUrl.'themes.json')['themes'];
    }

    /**
     * To get the specific file for the given path
     *
     * @param $_themeId
     * @param $_assetPath
     * @return mixed
     */
    public function getThemeAsset($_themeId, $_assetPath)
    {
        $response = $this->call($this->baseUrl.'/themes/'.$_themeId.'/assets.json',[
            'asset[key]' => $_assetPath
        ]);
        if(isset($response['asset'])){
            return $response['asset']['value'];
        }
        return null;
    }

    /**
     * @param $_themeId
     * @param $_assetPath
     * @param $_content
     * @return array|\GuzzleHttp\Promise\Promise|\stdClass
     */
    public function updateThemeAsset($_themeId, $_assetPath, $_content)
    {
        return $this->call($this->baseUrl.'/themes/'.$_themeId.'/assets.json',[
            'asset' => [
                'key'=> $_assetPath,
                'value' => $_content
            ]
        ],'PUT');
    }

    /**
     * //currently this would work only for one user, for more then one users we can add the login to app, and get
     * logged-in user here and its credentials for further process.
     * @param $url
     * @param array $body
     * @param string $method
     * @return array|\GuzzleHttp\Promise\Promise|\stdClass
     */
    public function call($url, array $body = [] , string $method = "GET")
    {
        $result =  $this->shop->api()->rest($method, $url, $body);
        if ($result['status'] >= 200 &&  $result['status'] < 300 ) {
            return $result['body']->container;
        }else{
            return [];
        }
    }
}
