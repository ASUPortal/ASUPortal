<?php

class CWorkPlanSection1StudyTypes extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Виды учебной деятельности для ".$this->getNumberSection()." раздела текущего контроля";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }
    
    public function getNumberSection()
    {
    	return 1;
    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        foreach ($contextObject->getControlTypes()->getItems() as $row) {
        	if ($row->section->sectionIndex == $this->getNumberSection() and $row->control->getAlias() == "current") {
        		$dataRow = array();
	        	$dataRow[0] = "– ".$row->type->getValue();
	        	$dataRow[1] = $row->mark;
	        	$dataRow[2] = $row->amount_labors;
	        	$dataRow[3] = $row->min;
	        	$dataRow[4] = $row->max;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}