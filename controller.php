<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class BootstrapCalendarPackage extends Package
{
    protected $pkgHandle = 'bootstrap_calendar';
    protected $appVersionRequired = '5.6.0';
    protected $pkgVersion = '1.0.1';

    public function getPackageDescription()
    {
        return t("A calendar written using Bootstrap");
    }

    public function getPackageName()
    {
        return t("Bootstrap Calendar");
    }

    public function install()
    {
        try
        {
            $pkg = parent::install();
            
            /* ---------------- INSTALL BLOCK ---------------- */
            BlockType::installBlockTypeFromPackage('bootstrap_calendar', $pkg);
            
			/* ---------------- INSTALL PAGE TYPE ---------------- */
			Loader::model('collection_types');
			
			$eventPageType = CollectionType::getByHandle('calendar_event');
			
			if (!$eventPageType || !intval($eventPageType->getCollectionTypeID()))
			{
				$eventPageType = CollectionType::add(array('ctHandle' => 'calendar_event', 'ctName' => t('Calendar Event')), $pkg);
			}
            
            /* ---------------- INSTALL ATTRIBUTES ---------------- */
			$eaku = AttributeKeyCategory::getByHandle('collection');
            
            // Install Attribute Type
            $etd = AttributeType::add('event_time_definition', t('Event Time Definition'), $pkg);
            $eaku->associateAttributeKeyType($etd);
            
            // Create Attribute Set
			$eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
			$eventSet = $eaku->addSet('built_in', t('Calendar Event Attributes'), $pkg);
 
            // Define Attributes
			$dateTimeType = AttributeType::getByHandle('date_time');
            $eventTimeDefinitionType = AttributeType::getByHandle('event_time_definition');
			
			/*CollectionAttributeKey::add($dateTimeType, array(
				'akHandle' => 'event_start',
				'akName' => t('Event Start'),
				'akIsSearchable' => false
			), $pkg)->setAttributeSet($eventSet);
			
			CollectionAttributeKey::add($dateTimeType, array(
				'akHandle' => 'event_end',
				'akName' => t('Event End'),
				'akIsSearchable' => false
			), $pkg)->setAttributeSet($eventSet);*/
			
            CollectionAttributeKey::add($eventTimeDefinitionType, array(
    			'akHandle' => 'event_time_definition_data',
				'akName' => t('Event Time'),
				'akIsSearchable' => false
			), $pkg)->setAttributeSet($eventSet);
            
            // Assign attributes to Page Type
			//$eventStartAttribute = CollectionAttributeKey::getByHandle('event_start');
			//$eventEndAttribute = CollectionAttributeKey::getByHandle('event_end');
            $eventTimeDefinitionDataAttribute = CollectionAttributeKey::getByHandle('event_time_definition_data');
			
			//$eventPageType->assignCollectionAttribute($eventStartAttribute);
			//$eventPageType->assignCollectionAttribute($eventEndAttribute);
            $eventPageType->assignCollectionAttribute($eventTimeDefinitionDataAttribute);
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
    }
    
    // Update any existing installation
    public function upgrade()
    {
        parent::upgrade();
        
        $pkg = $this;
    }
    
    public function on_start()
    {
        $html = Loader::helper('html');
        $v = View::getInstance();
        
        $v->addHeaderItem($html->css('bs231.css', 'bootstrap_calendar'));
		$v->addHeaderItem($html->css('calendar.css', 'bootstrap_calendar'));
		$v->addHeaderItem($html->css('app.css', 'bootstrap_calendar'));
        
        $v->addHeaderItem($html->javascript('underscore-min.js', 'bootstrap_calendar'));
		$v->addHeaderItem($html->javascript('en-GB.js', 'bootstrap_calendar'));
		$v->addHeaderItem($html->javascript('bootstrap.js', 'bootstrap_calendar'));
        $v->addHeaderItem($html->javascript('calendar.js', 'bootstrap_calendar'));
        
        $v->addHeaderItem($html->javascript('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js'));
        $v->addHeaderItem($html->javascript('//ajax.googleapis.com/ajax/libs/angularjs/1.0.6/angular.min.js'));
    }
}
