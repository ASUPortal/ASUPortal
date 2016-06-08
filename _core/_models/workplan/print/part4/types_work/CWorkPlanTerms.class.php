<?php

class CWorkPlanTerms extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Семестры дисциплины";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        $discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
        if (!empty($contextObject->terms->getItems())) {
        	$arr = array("");
        	foreach ($contextObject->terms->getItems() as $term) {
        		$arr[] = $term->corriculum_discipline_section->title." семестр";
        	}
        	$result[] = $arr;
        } else {
        	if (!empty($discipline->sections->getItems())) {
        		$arr = array("");
        		foreach ($discipline->sections->getItems() as $section) {
        			$arr[] = $section->title." семестр";
        		}
        		$result[] = $arr;
        	}
        }
        return $result;
    }
}