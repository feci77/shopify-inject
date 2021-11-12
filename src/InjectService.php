<?php

namespace Shopify\Inject;

use App\Models\User;

class InjectService
{
    public $shop;

    public $shopifyService;

    /** @var string content being used in this file for processing */
    public $globalContent;

    /** @var array current theme being used in shopify */
    public $currentTheme;

    public function __construct(User  $user)
    {
        $this->shopifyService = new ShopifyService($user);
    }

    public function getFileContent($themeId, $assetPath)
    {
        $this->globalContent =  $this->shopifyService->getThemeAsset($themeId, $assetPath);
    }

    public function getCurrentTheme()
    {
        $themes = $this->shopifyService->getAllThemes();
        foreach ($themes as $theme) {
            if($theme['role'] ==  'main'){
                $this->currentTheme = $theme;
            }
        }
    }

    /**
     * @param $liquidFilePath
     * @param $search
     * @param $contentToAdd
     * @param bool $after
     * @return bool
     */
    public function updateContentToAsset($liquidFilePath, $search, $contentToAdd, bool $after = true): bool
    {
        $this->getCurrentTheme();
        $this->getFileContent($this->currentTheme['id'], $liquidFilePath);
        if (!$this->isAlreadyExists($this->globalContent,$contentToAdd)) {
            try{
                $this->prepareContent($search,$contentToAdd,$after);
                $this->pushContentToShopify($liquidFilePath);
                 return true;
            }catch (\Exception $exception){
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $path
     */
    public function pushContentToShopify($path)
    {
        $this->shopifyService->updateThemeAsset($this->currentTheme['id'], $path, $this->globalContent);
    }

    /**
     * @param $search
     * @param $contentToAdd
     * @param bool $after
     */
    private function prepareContent( $search, $contentToAdd, $after = true )
    {

        if($after){
            $contentToAdd = $search.$contentToAdd;
        }else{
            $contentToAdd = $contentToAdd.$search;
        }
        $this->globalContent = str_replace($search, $contentToAdd, $this->globalContent);
    }

    /**
     * @param $fileContent
     * @param $contentToAdd
     * @return bool
     */
    public function isAlreadyExists($fileContent, $contentToAdd): bool
    {
        if(strpos($fileContent,$contentToAdd)){
            return true;
        }else{
            return false;
        }
    }
}
