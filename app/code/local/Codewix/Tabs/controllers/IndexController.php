<?php
class Codewix_Tabs_IndexController extends Mage_Core_Controller_Front_Action {  

	public function indexAction() {
	
	$this->loadLayout();
	$this->renderLayout();
	
	}
	public $html ="";
	public function getAction() {
	
		$per_page=Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage');
		$page = $this->getRequest()->getParam('page');
		$last = $this->getRequest()->getParam('last');

		$tab = $this->getRequest()->getParam('tab');
		
		if($tab !=2 && $tab !=3) {
			$selection = Mage::getStoreConfig('tabs_section/tabs_group/tabs_field1');
			if($selection) {
			$category_field_val = explode("-",Mage::getStoreConfig('tabs_section/tabs_group/tabs_category_field1'));
			$catid = $category_field_val[0];
			$arr = $this->getTabContent($selection,$page,$catid);
			}
			else {
				$arr = $this->getTabContent(1,$page,1);
			}
		}
		elseif($tab == 2){
			
			$selection = Mage::getStoreConfig('tabs_section/tabs_group/tabs_field2');		
			if($selection) {
			$category_field_val = explode("-",Mage::getStoreConfig('tabs_section/tabs_group/tabs_category_field2'));
			$catid = $category_field_val[0];
			$arr = $this->getTabContent($selection,$page,$catid);
			}
			else {
				$arr = $this->getTabContent(2,$page,1);
			}
		}
		else{

			$selection = Mage::getStoreConfig('tabs_section/tabs_group/tabs_field3');	
			if($selection) {
			$category_field_val = explode("-",Mage::getStoreConfig('tabs_section/tabs_group/tabs_category_field3'));
			$catid = $category_field_val[0];
			$arr = $this->getTabContent($selection,$page,$catid);
			}
			else {
				$arr = $this->getTabContent(1,$page,1);
			}
		}        
		$productRating = new Mage_Review_Block_Helper;
		$count = 1;	
		$collection = $arr[0];
		$_count = $arr[1];
		global $html;
		$special_price ="";
		if ($_count) {
		foreach ($collection as $product) {
			if($selection == 6) {
				$special_price = '<div class="tab-special-price">'.Mage::helper('core')->currency($product->getSpecialPrice()).'</div>';
				$price =  '<strike>'.Mage::helper('core')->currency($product->getPrice()).'</strike>';
			} else {
				$price = Mage::helper('core')->currency($product->getPrice());
			}
			if($count==1): 
			$html.= '<ul class="ul-tabs-homepage cart-items-row">';
      endif;
			$html.='<li class="homepage-item box_items"><span class="box_items_text"><a href="'.$product->getProductUrl().'" title="'.$product->getName().'">'.substr($product->getName(),0,15).'</a></span><br /><a href="'.$product->getProductUrl().'" title="'.$product->getName().'"><img src="'.$product->getImageUrl().'" width="139" height="96" /></a><div class="tab-regular-price">'.$price.'</div>'.$special_price;
			
			$addtocarturl = Mage::helper('checkout/cart')->getAddUrl($product); ?>
				 
				         <?php $html.=$productRating->getSummaryHtml($product,'short','No Review Yet');?>
				         <?php //endif; 
			$html.='<p><button type="button" title="Add to Cart" class="button btn-cart" onclick="setLocation(\''.$addtocarturl.'\')"><span><span>Add to Cart</span></span></button></p>
				</li>	';		
      if($count%4==0):
			$html.='</ul><div id="homepage-clearer"></div>'; 
      $count=0; endif;
			$count++; 
		}
  if($tab) {
		$html.='<div style="clear:both"></div><div class="page-div" id="paging_button'."-".$tab.'" align="center"><ul>';
		} else {
		$html.='<div style="clear:both"></div><div class="page-div" id="paging_button" " align="center"><ul>';
		}
					//Show page links
			$totalPages = ceil($_count/Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'));
			//echo $totalPages;
			if($totalPages != 1) {
			$secondLast = $totalPages-1;
			if($page <5 ){
				$start = 1;
				$total = 5;
			}
			 
			elseif($page == $totalPages) {
				$start = $page-4;
				$total = $totalPages;
				
			}
			else {
				$start = $page-2;
				$total = $page+2;
			}
			if($total > $totalPages) $total = $totalPages;
			
			if($last == 'true' && $page != $totalPages):
				$start = $page-2;
				//$total = $page+2;
			endif;
			if($secondLast == $page && $secondLast>3) {
				$start=$page-3;
			}
			$next = $page+1;
			$pre = $page-1;
			$counter =1;	
			if($page !=1 ) $html.="<li id=\"".$pre."\"class =\"pre\"><<</li>";
			for($i=$start; $i<=$total && $total <= $totalPages; $i++)
			{
				if($counter==5) {
					$html.='<li id="'.$i.'" class="last-li">'.$i.'</li>';
				}
				else {
					$html.='<li id="'.$i.'">'.$i.'</li>';
				}
				$counter++;
		
			}
			if($page != $totalPages)$html.="<li id=\"".$next."\"class =\"next\">>></li>";
			}
			
			$html.='</ul></div>';	
	
       } 
			else {
			$html.='<div id="no_product"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/base/default/images/NoProduct.png"/></div>';	
			}
			echo $html;
	
	}
	
	public function getAllProducts($page) {
	
		$_collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
		//$count = $_collection->count();
		$collection = $_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();

		$_count = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)->count(); 
			
		$arr[0] = $collection;
		$arr[1] = $_count;
		
		return	$arr;

	
	}
	
	public function getNewProducts($page) {
	
	  $todayDate = date('m/d/y');
		$_collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
      ->addAttributeToFilter('news_to_date', array('or'=> array(
      0 => array('date' => true, 'from' => $todayDate),
      1 => array('is' => new Zend_Db_Expr('null')))
      ), 'left');
	
		$collection = $_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();
		$_count  = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
      ->addAttributeToFilter('news_to_date', array('or'=> array(
      0 => array('date' => true, 'from' => $todayDate),
      1 => array('is' => new Zend_Db_Expr('null')))
      ), 'left')->count();	
			
		$arr[0] = $collection;
		$arr[1] = $_count;
		
		return	$arr;
	
	}
	
	public function getFeaturedProducts($page) {
	
		$_collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter(array(
        array('attribute'=>'is_featured','eq'=>'1')           
			));
		//print_r($_collection);	
		//$count = $_collection->count();
		$collection = $_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();

		$_count = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('is_featured')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addFieldToFilter(array(
        array('attribute'=>'is_featured','eq'=>'1'),            
			))->count(); 
		//print_r($_count);	
		
		$arr[0] = $collection;
		$arr[1] = $_count;
		
		return	$arr;
	
	}
	
	public function getBestSellerProducts($page) {
	  
	  $visibility = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
	                  Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG);
	  $_collection = Mage::getResourceModel('reports/product_collection')
			->addAttributeToSelect('*')
			->addOrderedQty()
			->addAttributeToFilter('visibility',$visibility)
			->setOrder('ordered_qty','desc');
	
	  $collection = $_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();
		$_count = Mage::getResourceModel('reports/product_collection')
			->addAttributeToSelect('*')
			->addOrderedQty()
			->addAttributeToFilter('visibility',$visibility)
			->setOrder('ordered_qty','desc')->count(); 
		//print_r($_count);	
		$arr[0] = $collection;
		$arr[1] = $_count;
		
		return	$arr;	
	}
	
	public function getOnSaleProducts($page) {
	
		$todayDate = date('m/d/y');
		$tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
    $tomorrowDate = date('m/d/y', $tomorrow);
		$_collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
      ->addAttributeToFilter('special_to_date', array('or'=> array(
      0 => array('date' => true, 'from' => $todayDate),
      1 => array('is' => new Zend_Db_Expr('null')))
      ), 'left');
	
		$collection = $_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();
		$_count  = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
      ->addAttributeToFilter('special_to_date', array('or'=> array(
      0 => array('date' => true, 'from' => $todayDate),
      1 => array('is' => new Zend_Db_Expr('null')))
      ), 'left')->count();	
			
		$arr[0] = $collection;
		$arr[1] = $_count;
		
		return	$arr;
	
	}
	
	public function getCategoryProducts($page,$catid) {
	
		$category = Mage::getModel('catalog/category')->load($catid);
		$_collection = $category->getProductCollection()
								->addAttributeToSelect('*')
								->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
		$collection=$_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();
		$_count = $category->getProductCollection()
								->addAttributeToSelect('*')
								->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)->count();
		
		$arr[0] = $collection;
		$arr[1] = $_count;
		
		return	$arr;
	}

	public function getHotProducts($page) {

		$_collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addFieldToFilter(array(
				array('attribute'=>'is_hot','eq'=>'1')
			));
		//print_r($_collection);
		//$count = $_collection->count();
		$collection = $_collection->setCurPage($page)
			->setPageSize(Mage::getStoreConfig('tabs_section/tabs_group/tabs_perpage'))
			->load();

		$_count = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('is_hot')
			->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->addFieldToFilter(array(
				array('attribute'=>'is_hot','eq'=>'1'),
			))->count();
		//print_r($_count);

		$arr[0] = $collection;
		$arr[1] = $_count;

		return	$arr;

	}
	
	public function getTabContent($selection,$page,$catid) {
	
		switch($selection) {
			case 1:
			$arr = $this->getAllProducts($page);
			break;
			case 2:
			$arr = $this->getNewProducts($page);
			break;
			case 3:
			$arr = $this->getFeaturedProducts($page);
			break;
			case 4:
			$arr = $this->getCategoryProducts($page,$catid);
			break;
			case 5:
			$arr = $this->getBestSellerProducts($page);
			break;
			case 6:
			$arr = $this->getOnSaleProducts($page);
			break;
			case 7:
			$arr = $this->getHotProducts($page);
			break;
			default:
			echo $this->__("Invalid configuration");
			break;
		
		}
	return $arr;
	}
}
?>
