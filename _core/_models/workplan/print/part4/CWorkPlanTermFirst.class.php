<?php

class CWorkPlanTermFirst extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Первый семестр в списке";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$result = "";
    	$discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
    	if (!empty($contextObject->terms->getItems())) {
    		$terms = array();
    		foreach ($contextObject->terms->getItems() as $term) {
    			$terms[] = $term->corriculum_discipline_section->title;
    		}
    		$result = $terms[0];
    	} else {
    		if (!empty($discipline->sections->getItems())) {
    			$terms = array();
    			foreach ($discipline->sections->getItems() as $section) {
    				$terms[] = $section->title;
    			}
    			$result = $terms[0];
    		}
    	}
        return $result;
    }
}