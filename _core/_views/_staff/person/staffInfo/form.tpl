<script>
    jQuery(document).ready(function(){
        jQuery("#page_content").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<form action="staffInfo.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $page)}
    {CHtml::activeHiddenField("user_id_insert", $page)}
    {CHtml::activeHiddenField("pg_cat", $page)}
    
    <div class="control-group">
        {CHtml::activeLabel("title", $page)}
        <div class="controls">
            {CHtml::activeTextField("title", $page)}
            {CHtml::error("title", $page)}
        </div>
    </div>
    
    <p>
        {CHtml::activeTextBox("page_content", $page, "page_content")}
        {CHtml::error("page_content", $page)}
    </p>
    
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>