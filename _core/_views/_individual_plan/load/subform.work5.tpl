{if ($load->getWorksByType(CIndPlanPersonWorkType::LIST_SCIENTIFIC_WORKS)->getCount() == 0)}
    <div class="alert alert-block">
        Нет данных для отображения
    </div>
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("title_id", $load->getWorksByType(CIndPlanPersonWorkType::LIST_SCIENTIFIC_WORKS)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("paper_pages", $load->getWorksByType(CIndPlanPersonWorkType::LIST_SCIENTIFIC_WORKS)->getFirstItem())}</th>
        </tr>
        {counter start=0 print=false}
        {foreach $load->getWorksByType(CIndPlanPersonWorkType::LIST_SCIENTIFIC_WORKS)->getItems() as $work}
            <tr>
                <td>
                    <a href="work.php?action=edit&id={$work->getId()}&year={$year}">
                        <i class="icon-pencil"></i>
                    </a>
                </td>
                <td>
                    <a href="#" onclick="if (confirm('Действительно удалить запись?')) { location.href='work.php?action=delete&id={$work->getId()}'; }; return false;">
                        <i class="icon-trash"></i>
                    </a>
                </td>
                <td>{counter}</td>
                <td>{$work->getTitle()}</td>
                <td>{$work->paper_pages}</td>
            </tr>
        {/foreach}
    </table>
{/if}