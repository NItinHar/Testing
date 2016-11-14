<?php 
    use \Magento\Framework\App\Bootstrap;
    include('app/bootstrap.php');
    //php cat.php
     $bootstrap      = Bootstrap::create(BP, $_SERVER);
    $objectManager  = $bootstrap->getObjectManager();

	$parentId = \Magento\Catalog\Model\Category::TREE_ROOT_ID;

	$parentCategory = $objectManager->create('Magento\Catalog\Model\Category')->load($parentId);

	$category = $objectManager->create('Magento\Catalog\Model\Category');
	//Check exist category
	$cate 	= 	$category->getCollection()
					->addAttributeToSelect('*')
					->addAttributeToFilter('apicatid','123asdasdoipi23123')
					->getFirstItem();
		   
		$data	=	array();
		$data	=	$cate->getData();
		if(!isset($data['entity_id'])) {
			echo 'yessssssssssss';
			$category->setName('ntnmod');
			$category->save();
		}else{
			echo 'Category Not exist';
		} 
//echo '<pre>'; print_r($cate->getData()); die('sssssssssssss');

    $url            = \Magento\Framework\App\ObjectManager::getInstance();
    $storeManager   = $url->get('\Magento\Store\Model\StoreManagerInterface');
    $mediaurl       = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    $state          = $objectManager->get('\Magento\Framework\App\State');
    $state->setAreaCode('frontend');
    /// Get Website ID
    $websiteId      = $storeManager->getWebsite()->getWebsiteId();
    echo 'websiteId: '.$websiteId." ";
    /// Get Store ID
    $store          = $storeManager->getStore();
    $storeId        = $store->getStoreId();
    echo 'storeId: '.$storeId." ";
    /// Get Root Category ID
    $rootNodeId    = $store->getRootCategoryId();
    echo 'rootNodeId: '.$rootNodeId." ";
    /// Get Root Category
    $rootCat       = $objectManager->get('Magento\Catalog\Model\Category');
    //$cat_info      = $rootCat->load($rootNodeId);
	//create first level category	
    $categorys     = array('nitin'); // Category Names
    foreach($categorys as $cat) {
        $name      = ucfirst($cat);
        $url       = strtolower($cat);
        $cleanurl  = trim(preg_replace('/ +/', '', preg_replace('/[^A-Za-z0-9 ]/', '', urldecode(html_entity_decode(strip_tags($url))))));
        $categoryFactory	=$objectManager->get('\Magento\Catalog\Model\CategoryFactory');		
        $categoryTmp = $categoryFactory->create();
        $categoryTmp->setName($name);
        $categoryTmp->setIsActive(true);
        $categoryTmp->setUrlKey($cleanurl);
        $categoryTmp->setData('description', 'description');
        $categoryTmp->setData('apicatid', '123asdasdoipi23123');
        $categoryTmp->setParentId($rootCat->getId());
        $categoryTmp->setStoreId($storeId);
        $categoryTmp->setPath($rootCat->getPath());
        $categoryTmp->save();
    }
	echo 'doneeee';    
?>