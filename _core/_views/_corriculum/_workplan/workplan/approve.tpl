{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Рабочие программы</h2>
    {CHtml::helpForCurrentPage()}
    {include file="_corriculum/_workplan/workplan/header.tpl"}
        
    <form action="workplans.php" method="post" id="MainView">
    {if $plans->getCount() == 0}
        <div class="alert">
            Нет рабочих программ для отображения
        </div>
	{else}

	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th>{CHtml::activeViewGroupSelect("id", $plans->getFirstItem(), true)}</th>
	            <th>№</th>
				<th></th>
	            <th>{CHtml::tableOrder("discipline.name", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("corriculum.title", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("person.fio", $plans->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("comment_file", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_on_portal", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan_library", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan_lecturer", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan_head_of_department", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan_nms", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan_dean", $plans->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("status_workplan_prorektor", $plans->getFirstItem())}</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $plans->getItems() as $plan}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить рабочую программу дисциплины {$plan->discipline}')) { location.href='?action=delete&id={$plan->id}'; }; return false;"></a></td>
	            <td>{CHtml::activeViewGroupSelect("id", $plan, false, true)}</td>
	            <td>{counter}</td>
	            <td><a href="?action=edit&id={$plan->getId()}" class="icon-pencil"></a></td>
	            <td>{$plan->discipline}</td>
	            <td>
	            	{if !is_null($plan->corriculumDiscipline)}
		            	{if !is_null($plan->corriculumDiscipline->cycle)}
			            	{if !is_null($plan->corriculumDiscipline->cycle->corriculum)}
			            		<a href="{$web_root}_modules/_corriculum/?action=view&id={$plan->corriculumDiscipline->cycle->corriculum->getId()}">{$plan->corriculumDiscipline->cycle->corriculum->title}</a>
			            	{/if}
		            	{/if}
	            	{/if}
	            </td>
				<td>{", "|join:$plan->authors->getItems()}</td>
				<td>
                    <span>
                        <span class="changeStatusComment" asu-id="{$plan->getId()}" asu-action="updateCommentFile" asu-color="{if is_null($plan->commentFile)}white{else}{$plan->commentFile->getAlias()}{/if}">
                            {if $plan->comment_file == 0 or is_null($plan->commentFile)}
                                Нет комментария
                            {else}
                                {$plan->commentFile->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusOnPortal" asu-id="{$plan->getId()}" asu-action="updateStatusOnPortal" asu-color="{if is_null($plan->statusOnPortal)}white{else}{$plan->statusOnPortal->getAlias()}{/if}">
                            {if $plan->status_on_portal == 0 or is_null($plan->statusOnPortal)}
                                Нет комментария
                            {else}
                                {$plan->statusOnPortal->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlanLibrary" asu-id="{$plan->getId()}" asu-action="updateStatusWorkPlanLibrary" asu-color="{if is_null($plan->statusWorkplanLibrary)}white{else}{$plan->statusWorkplanLibrary->getAlias()}{/if}">
                            {if $plan->status_workplan_library == 0 or is_null($plan->statusWorkplanLibrary)}
                                –
                            {else}
                                {$plan->statusWorkplanLibrary->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlanLecturer" asu-id="{$plan->getId()}" asu-action="updateStatusWorkPlanLecturer" asu-color="{if is_null($plan->statusWorkplanLecturer)}white{else}{$plan->statusWorkplanLecturer->getAlias()}{/if}">
                            {if $plan->status_workplan_lecturer == 0 or is_null($plan->statusWorkplanLecturer)}
                                –
                            {else}
                                {$plan->statusWorkplanLecturer->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlanHeadOfDepartment" asu-id="{$plan->getId()}" asu-action="updateStatusWorkPlanHeadOfDepartment" asu-color="{if is_null($plan->statusWorkplanHeadOfDepartment)}white{else}{$plan->statusWorkplanHeadOfDepartment->getAlias()}{/if}">
                            {if $plan->status_workplan_head_of_department == 0 or is_null($plan->statusWorkplanHeadOfDepartment)}
                                –
                            {else}
                                {$plan->statusWorkplanHeadOfDepartment->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlanNMS" asu-id="{$plan->getId()}" asu-action="updateStatusWorkPlanNMS" asu-color="{if is_null($plan->statusWorkplanNMS)}white{else}{$plan->statusWorkplanNMS->getAlias()}{/if}">
                            {if $plan->status_workplan_nms == 0 or is_null($plan->statusWorkplanNMS)}
                                –
                            {else}
                                {$plan->statusWorkplanNMS->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlanDean" asu-id="{$plan->getId()}" asu-action="updateStatusWorkPlanDean" asu-color="{if is_null($plan->statusWorkplanDean)}white{else}{$plan->statusWorkplanDean->getAlias()}{/if}">
                            {if $plan->status_workplan_dean == 0 or is_null($plan->statusWorkplanDean)}
                                –
                            {else}
                                {$plan->statusWorkplanDean->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	            <td>
                    <span>
                        <span class="changeStatusWorkPlanProrektor" asu-id="{$plan->getId()}" asu-action="updateStatusWorkPlanProrektor" asu-color="{if is_null($plan->statusWorkplanProrektor)}white{else}{$plan->statusWorkplanProrektor->getAlias()}{/if}">
                            {if $plan->status_workplan_prorektor == 0 or is_null($plan->statusWorkplanProrektor)}
                                –
                            {else}
                                {$plan->statusWorkplanProrektor->getValue()}
                            {/if}
                        </span>
                    </span>
	            </td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
{include file="_corriculum/_workplan/workplan/index.right.tpl"}
{/block}
