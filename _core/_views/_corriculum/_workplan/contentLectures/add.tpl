{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление лекции</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentLectures/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLectures/common.right.tpl"}
{/block}