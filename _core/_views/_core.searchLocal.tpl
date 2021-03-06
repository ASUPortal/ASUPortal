<script>
    jQuery(document).ready(function(){
        var searchResults = new Object();
        jQuery("#search").typeahead({
            source: function (query, process) {
                return jQuery.ajax({
                    url: "#",
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {
                        "query": query,
                        "action": "search"
                    },
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#search").css({
                            "background-image": 'url({$web_root}images/ajax-loader.gif)',
                            "background-repeat": "no-repeat",
                            "background-position": "95% center"
                        });
                    },
                    success: function(data){
                        var lookup = new Array();
                        searchResults = new Object();
                        for (var i = 0; i < data.length; i++) {
                        	var searchObj = new Object();
                            searchObj.field = data[i].field;
                            searchObj.className = data[i].class;
                            searchObj.value = data[i].value;
                            if (!(data[i].label in searchResults)) {
                            	searchResults[data[i].label] = searchObj;	
                            	lookup.push(data[i].label);
                            } else {
                            	var index = 1;
                            	while ((data[i].label + " (" + index + ")") in searchResults) {
                            		index++;
                            	}
                            	searchResults[data[i].label + " (" + index + ")"] = searchObj;
                            	lookup.push(data[i].label + " (" + index + ")");
                            }
                        }
                        process(lookup);
                        jQuery("#search").css("background-image", "none");
                    }
                });
            },
            updater: function(item){
            	var value = searchResults[item].value;
                var key = searchResults[item].field;
                var className = searchResults[item].className;
                /**
                 * Делаем фильтр по указанному полю
                 */
                var url = window.location.origin + window.location.pathname;
                var params = new Array();
                if (window.location.search != "") {
                    var qw = window.location.search;
                    qw = qw.substr(1);
                    var parts = qw.split("&");
                    for (var i = 0; i < parts.length; i++) {
                        var param = parts[i].split("=");
                        if (param[0] !== "filter" && param[0] !== "filterClass" && param[0] !== "filterLabel") {
                            params[params.length] = param[0] + "=" + param[1];
                        }
                    }
                }
                params[params.length] = "filter=" + key + ":" + value;
                params[params.length] = "filterClass=" + className;
                params[params.length] = "filterLabel=" + item;
                /**
                 * Собираем строку запроса обратно
                 */
                url = url + "?" + params.join("&");
                /**
                 * Переадресация
                 */
                window.location.href = url;
            },
            minLength: 1,
            items: 30
        });
        jQuery(".icon-trash.main_search_reset").on("click", function(){
            /**
             * Сбрасываем фильтр
             */
            var url = window.location.origin + window.location.pathname;
            var params = new Array();
            if (window.location.search != "") {
                var qw = window.location.search;
                qw = qw.substr(1);
                var parts = qw.split("&");
                for (var i = 0; i < parts.length; i++) {
                    var param = parts[i].split("=");
                    if (param[0] !== "filter" && param[0] !== "filterClass" && param[0] !== "filterLabel") {
                        params[params.length] = param[0] + "=" + param[1];
                    }
                }
            }
            /**
             * Собираем строку запроса обратно
             */
            url = url + "?" + params.join("&");
            /**
             * Переадресация
             */
            window.location.href = url;
        });
    });
</script>

<table border="0" width="100%" class="tableBlank">
	<tr>
		{block name="localSearchContent"}{/block}
	</tr>
    <tr>
    	<td valign="top">
            <table cellspacing="2">
                {foreach $__search as $label=>$value}
                    <tr>
                        <td>{$value}</td>
                        <td><i class="icon-trash main_search_reset" style="cursor: pointer; "></i></td>
                    </tr>
                {/foreach}
            </table>
        </td>
        <td valign="top" width="300px">
            <p>
                <input type="text" id="search" style="width: 95%; " placeholder="Поиск">
            </p>
        </td>
    </tr>
</table>