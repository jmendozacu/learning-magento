<?php
class Codewix_Tabs_Model_Options
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('tabs')->__('All Products')),
            array('value'=>2, 'label'=>Mage::helper('tabs')->__('New Products')),
      		  array('value'=>3, 'label'=>Mage::helper('tabs')->__('Featured Product')),            
      		  array('value'=>4, 'label'=>Mage::helper('tabs')->__('Category')) ,
      		  array('value'=>5, 'label'=>Mage::helper('tabs')->__('Best Sellers Products')),
      		  array('value'=>6, 'label'=>Mage::helper('tabs')->__('On Sale Products')),
              array('value'=>6, 'label'=>Mage::helper('tabs')->__('Hot Products'))
        );
    }

}
?>
