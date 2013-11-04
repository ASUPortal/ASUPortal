{if ($load->getWorksByType(3)->getCount() == 0)}
    <div class="alert alert-block">
        Нет данных для отображения
    </div>
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("title_id", $load->getWorksByType(3)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("plan_amount", $load->getWorksByType(3)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("plan_hours", $load->getWorksByType(3)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("plan_expiration_date", $load->getWorksByType(3)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("plan_report_type", $load->getWorksByType(3)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $load->getWorksByType(3)->getFirstItem())}</th>
        </tr>
        {foreach $load->getWorksByType(3)->getItems() as $work}
            <tr>
                <td>
                    <a href="work.php?action=edit&id={$work->getId()}">
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
                <td>{$work->plan_amount}</td>
                <td>{$work->plan_hours}</td>
                <td>{$work->plan_expiration_date}</td>
                <td>{$work->plan_report_type}</td>
                <td>{$work->comment}</td>
            </tr>
        {/foreach}
    </table>
{/if}