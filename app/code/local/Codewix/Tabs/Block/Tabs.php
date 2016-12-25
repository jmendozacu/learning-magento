<?php	
class Codewix_Tabs_Block_Tabs extends Mage_Core_Block_Template {

	public function getTabLabels($tab_id) {
	
		if(Mage::getStoreConfig('tabs_section/tabs_group/tabs_field'.$tab_id)) {
		$tab_field_val = Mage::getStoreConfig('tabs_section/tabs_group/tabs_field'.$tab_id);
		}
		else {
			$tab_field_val = $tab_id;
		}
		switch($tab_field_val) {
		
		case 1:
		echo $this->__("All Products");
		break;
		case 2:
		echo $this->__("New Products");
		break;
		case 3:
		echo $this->__("Featured Products");
		break;
		case 4:
		$category_field_val = explode("-",Mage::getStoreConfig('tabs_section/tabs_group/tabs_category_field'.$tab_id));
		echo $this->__($category_field_val[1]);
		break;
		case 5:
		echo $this->__("Best Seller Products");
		break;
		case 6:
		echo $this->__("On Sale Products");
		break;
		case 7:
		echo $this->__("Hot Products");
		break;
		default:
		echo $this->__("Invalid Configuration");
		break;
		}
		
	}
}

