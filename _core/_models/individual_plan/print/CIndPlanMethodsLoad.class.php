<?php

class CIndPlanMethodsLoad extends CAbstractPrintClassField{
    public function getFieldName()
    {
        return "Перечень научных и научно-методических работ, выполненных преподавателем";
    }

    public function getFieldDescription()
    {
        return "Используется при печати индивидуального плана, принимает параметр planId с Id плана";
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
        $studyLoad = new CArrayList();
        $load = CIndPlanManager::getLoad(CRequest::getInt("planId"));
        $studyLoad = $load->getWorksByType(5);
        foreach ($studyLoad->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = $row->getTitle();
        	$dataRow[1] = $row->paper_pages;
        	$result[] = $dataRow;
        }
        return $result;
    }
}