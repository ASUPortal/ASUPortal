<?php

class CCourseProjectLecturerWithPost extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Преподаватель с должностью для курсового проектирования";
    }

    public function getFieldDescription()
    {
        return "Используется при печати курсового проекта, принимает параметр id с Id курсового проекта";
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
        if (!is_null($contextObject->lecturer->getPost())) {
            $result = $contextObject->lecturer->getPost()->getValue()." каф. АСУ ";
        }
        $result .= $contextObject->lecturer->getNameShort();
        return $result;
    }
}