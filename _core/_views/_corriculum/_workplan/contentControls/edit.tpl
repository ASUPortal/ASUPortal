{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование формы контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentControls/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentControls/common.right.tpl"}
{/block}