#Injects Code to Shopify Store Front

##This package is to inject the content into the shopify liquid files through the shopify admin api.

This package is based on the "osiset/laravel-shopify": "^17.1" package. 
so you have to install and configure this package first to make it working. 
Here are the details of *Osiset/laravel-shopify* package.
https://github.com/osiset/laravel-shopify. 
Please install and configure it completely and then use my package.

It will require the User which is implementing the IShopModel of Osiset. that can be any user with registered domain. 
If you've any confusion about it, Please go for the osiset docs.

```$injectService = new Shopify\Inject\InjectService(\App\Models\User::first());```



```
//shopify storefront liquid files file path.
$filePath = 'layout/theme.liquid';

//content to be searched. My package will search this string in the given file and update the file with provided content.
$mainContent = '{{ content_for_layout }}';

// provided content to be added before or after the search content
$contentToAdd = "<script> console.log('script added by inject app by editing theme.liquid file'); </script>";

//calling the function for updating the asset. if it will update it it will return true otherwise false.
if($injectService->updateContentToAsset($filePath,$mainContent,$contentToAdd)){
    return "Content Updated Successfully.";
}else{
    return "Content Already Exists";
}
```

