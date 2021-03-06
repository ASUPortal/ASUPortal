<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>{$page_title} - Официальный портал кафедры АСУ</title>
    {foreach $js as $j}
        <script type="text/javascript" src="{$web_root}scripts/{$j}"></script>
    {/foreach}
    <link href="{$web_root}scripts/_core/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="{$web_root}scripts/_core/datepicker/css/datepicker.css" rel="stylesheet" media="screen">
    <link href="{$web_root}scripts/_core/timepicker/css/bootstrap-timepicker.css" rel="stylesheet" media="screen">
    {foreach $css as $c}
        <link href="{$web_root}css/{$c}" rel="stylesheet" type="text/css" />
    {/foreach}
    {if ((count($jsInline) > 0) || (count($jqInline) > 0))}
        <script>
            {foreach $jsInline as $i}
                {$i}
            {/foreach}

            {if (count($jqInline) > 0)}
                $(document).ready(function(){
                    {foreach $jqInline as $i}
                        {$i}
                    {/foreach}
                });
            {/if}
        </script>
    {/if}
    {foreach $jsIe as $version=>$scripts}
		<!--[if IE {$version}]>
		{foreach $scripts->getItems() as $script}
			<script src="{$web_root}scripts/{$script}"></script>
		{/foreach}
		<![endif]-->
 	{/foreach}
</head>
<body>
<div id="overlay"></div>