<?php 
defined('C5_EXECUTE') or die("Access Denied.");

class EventTimeDefinitionAttributeTypeController extends Concrete5_Controller_AttributeType_Default {
    public function form() {
        $html = Loader::helper('html');
        
        //$this->addHeaderItem($html->javascript('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js'));
        //$this->addHeaderItem($html->javascript('//ajax.googleapis.com/ajax/libs/angularjs/1.0.6/angular.min.js'));
        
        $this->addHeaderItem($html->css(       'event_time_definition_attribute_type.css', 'bootstrap_calendar'));
        $this->addHeaderItem($html->javascript('event_time_definition_attribute_type.js' , 'bootstrap_calendar'));
        
        if (is_object($this->attributeValue)) {
    	    $value = Loader::helper('text')->entities($this->getAttributeValue()->getValue());
        }
        
    	$this->set('hidden', Loader::helper('form')->hidden($this->field('value'), $value));
    }
    
    public function saveForm($data) {
		$this->saveValue($data['value']);
	}
}
