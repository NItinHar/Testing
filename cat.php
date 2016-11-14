<?php 
    use \Magento\Framework\App\Bootstrap;
    include('app/bootstrap.php');
    $bootstrap      = Bootstrap::create(BP, $_SERVER);
    $objectManager  = $bootstrap->getObjectManager();
	$parentId = \Magento\Catalog\Model\Category::TREE_ROOT_ID;
	$parentCategory = $objectManager->create('Magento\Catalog\Model\Category')->load($parentId);
/* 	$category       = $objectManager->get('Magento\Catalog\Model\Category');
	$cate 	= 	$category->getCollection()
				->addAttributeToSelect('*')
				//->addAttributeToFilter('apicatid','123asdasdoipi23123')
				->getItems();
	
	//echo '<pre>'; print_r($cate->getData()); die();
	
	foreach($cate as $data){
		echo '<br/>'.$data->getData('name');
	} 
	//echo '<pre>'; print_r($cate->getData('name')); 
	die('aaaaaaaaaaaa'); */
	//echo '<pre>'; print_r($cate->getData()); die('sssssssssssss');
	$url            = \Magento\Framework\App\ObjectManager::getInstance();
    $state          = $objectManager->get('\Magento\Framework\App\State');
    $state->setAreaCode('frontend');
    $websiteId      = 1;
    $storeId        = 1;
    /// Get Root Category
    $category       = $objectManager->get('Magento\Catalog\Model\Category');
	//Check exist category
	$ch 			= 	curl_init("http://geb-testing.edstema.it/api/v3.0/categories.json?storeCode=ZWZHC");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json')
	);
	$sellerresult 	= 	curl_exec($ch);
	$apiData		=	json_decode($sellerresult);	
	$childData		=	array();
	$parentData		=	array();
	$i				=	0;
	$j				=	0;
	foreach($apiData->results->items as $items){
		if($items->name && $items->id){
			if(isset($items->parent->id)){				
				$childData[$items->parent->id][]	=	array('id' => $items->id, 'name' => $items->name);
				$i++;
			}else{
				$parentData[$j]['id']		=	$items->id;
				$parentData[$j]['name']		=	$items->name;
				$j++;
			}			
		}
	}	
    //create first level category	
    $categorys     		= 	$parentData;
	
	$categorys     		= 	array(	
								array(	
									'id'=> '111111',
									'name'=> 'nitin1'
								),array(	
									'id'=> '222',
									'name'=> 'nitin2'
								),array(	
									'id'=> '333333',
									'name'=> 'nitin3'
								)
							);
	
    foreach($categorys as $cat) {		
		//echo 'cat :<pre>'; print_r($cat); die('aaaaaaaaa');
		$cate 			= 	$category->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('apicatid',$cat['id'])->getFirstItem();		   
		//$cate 			= 	$category->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('name',strtolower($cat['name']))->getFirstItem();		   
		$dataArray		=	array();
		$dataArray		=	$cate->getData();
		if(!isset($dataArray['entity_id'])) {
			$name      = ucfirst($cat['name']);
			$url       = strtolower($cat['name']);
			$cleanurl  = trim(preg_replace('/ +/', '', preg_replace('/[^A-Za-z0-9 ]/', '', urldecode(html_entity_decode(strip_tags($url))))));
			$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');		
			$categoryTmp = $categoryFactory->create();
			$categoryTmp->setName($name);
			$categoryTmp->setIsActive(true);
			$categoryTmp->setUrlKey('aaaa'.$cleanurl);
			$categoryTmp->setData('description', ' ');
			$categoryTmp->setData('apicatid', $cat['id']);
			$categoryTmp->setParentId($category->getId());
			$categoryTmp->setStoreId($storeId);
			$categoryTmp->setPath($category->getPath());
			$categoryTmp->save();
		}else{
			echo 'Already added';
		}
		unset($dataArray);
    }
	echo 'parent data :<pre>'; print_r($parentData);
	echo 'doneeee';    
?>