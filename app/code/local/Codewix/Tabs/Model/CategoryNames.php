<?php
class Codewix_Tabs_Model_CategoryNames
{
	public function toOptionArray()
    {
		$collection = Mage::getModel('catalog/category')->getCollection()
		        ->addAttributeToSelect('*')
		        ->addIsActiveFilter();
		        
		foreach($collection as $category) {
			if($category->getId() != 3 && $category->getId() != 1)
			$categories[] = array('value'=>$category->getId()."-".$category->getName(),'label'=>$category->getName());   
		}  
		
		return $categories;
    }
}
?>
