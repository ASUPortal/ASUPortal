<?php

class CWorkPlanApproverPost extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Должность утверждающего";
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
		if (!is_null($contextObject->approverPost)) {
			$result = $contextObject->approverPost->getValue();
		}
		return $result;
    }
}