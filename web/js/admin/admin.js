function alertObject(c,e){var d=(e)?e+"\n":"{";for(var a in c){var b=c[a];if(typeof b=="function"){b="[<FUNCTION>]"}d+=a+" : "+b+" , "}d+=" }";alert(d)}$.fn.chosenDestroy=function(){$(this).show().removeClass("chzn-done");$(this).next().remove();return $(this)};(function(a){a.isIe6=jQuery.browser.msie&&parseInt(jQuery.browser.version,10)<7&&parseInt(jQuery.browser.version,10)>4;a(document).ready(function(){isMobile=false;if(a("#is-mobile:visible").length==0){isMobile=true}if(a("input").length>0){if(a("form.login").length==0&&("form.query-params").length==0){a("input").keydown(function(i){if(i.keyCode=="13"){i.preventDefault();return false}})}}setTimeout(function(){a(".focus-me:eq(0)").focus()},100);a("#sort_clear").click(function(){a("a.sort-by").each(function(){var i="sort_"+a(this).attr("id");a.query.REMOVE(i)});window.location.search=a.query.toString();return false});a("a.sort-by").each(function(){var i="sort_"+a(this).attr("id");var j=a.query.GET(i);if(j){a(this).parent().removeClass("asc").removeClass("desc").addClass(j)}a(this).click(function(){var l="sort_"+a(this).attr("id");var m=a.query.GET(l);var k="asc";if(m&&m=="asc"){k="desc"}if(m&&m=="desc"){k=""}if(k==""){a.query.REMOVE(l)}else{a.query.SET(l,k)}window.location.search=a.query.toString();return false})});a("form.query-params").each(function(){a(this).find("select,input").not("[type=submit]").each(function(){var i=a.query.GET(a(this).attr("name"));if(a(this).attr("type")!="checkbox"){if(i instanceof Array){for($i=0;$i<i.length;$i++){a(this).find('option[value="'+i[$i]+'"]').attr("selected","selected")}}else{i+="";if(i&&i!=""){a(this).val(unescape(i.replace(/\+/g," ")))}}}});a(this).bind("submit",function(){a(this).find("select,input").each(function(){if(a(this).attr("name")){var i=a(this).val();if(i==""){a.query.REMOVE(a(this).attr("name"))}else{if(a(this).attr("type")=="checkbox"){if(a('input[type="hidden"][name="'+a(this).attr("name")+'"]').length==0){a.query.SET(a(this).attr("name"),a(this).val())}}else{a.query.SET(a(this).attr("name"),a(this).val())}}}});window.location.href=a(this).attr("action")+a.query.toString();return false});a(this).find("select").change(function(){a(this).parents("form:first").not(".no-autosubmit").submit()});a(this).find("input").change(function(){if(a(this).attr("name")!="q"){a(this).parents("form:first").not(".no-autosubmit").submit()}})});a("form.query-params .date-filter-links a").click(function(){var k=a(this).closest(".input-row-date");var j=k.find(".from:first");var m=k.find(".to:first");if(a(this).hasClass("today")){j.val(e(new Date()));m.val(e(new Date()));m.change()}if(a(this).hasClass("this-week")){var i=new Date();while(i.getDay()!=1){i.setDate(i.getDate()-1)}var n=new Date();while(n.getDay()!=0){n.setDate(n.getDate()+1)}j.val(e(i));m.val(e(n));m.change()}if(a(this).hasClass("this-month")){var i=new Date();i.setDate(1);var n=new Date();var l=n.getMonth();n.setDate(1);n.setMonth(l+1);n.setDate(n.getDate()-1);j.val(e(i));m.val(e(n));m.change()}if(a(this).hasClass("cancel")){j.val("");m.val("");m.change()}return false});function e(j){var i="";i+=j.getFullYear();i+="-";var l=j.getMonth()+1;if(l<10){i+="0"+l}else{i+=l}i+="-";var k=j.getDate();if(k<10){i+="0"+k}else{i+=k}return i}a("img.scroll-down").click(function(){a("html, body").animate({scrollTop:(parseInt(a("#center").height()))},"fast")});a("img.scroll-up").click(function(){a("html, body").animate({scrollTop:0},"fast")});a("#sidebar .navigation .selected").parents("ul.navigation").prev(".section-header").toggleClass("closed");a("#sidebar .navigation .section-header").each(function(){if(a(this).hasClass("closed")){a(this).removeClass("closed")}else{a(this).next().hide();a(this).addClass("closed")}});a("#sidebar .navigation .section-header").click(function(){a(this).next().slideToggle();a(this).toggleClass("closed")});function h(j,i){var k=a("<div>"+j+"</div>").text();if(i){k=k.substring(0,i-1)}return k}function g(i){i=h(i);i=i.toLowerCase();i=i.replace(/^\s+|\s+$/g,"");i=i.replace(/[_\s]+/g,"-");i=i.replace(/[^a-zа-я0-9-]+/g,"");i=i.replace(/[-]+/g,"-");i=i.replace(/^-+|-+$/g,"");return i}a("input.date").each(function(){a(this).datepicker({dateFormat:"yy-mm-dd",changeMonth:true,changeYear:true,yearRange:"-80:+10"}).attr("readonly","readonly")});a(".reload").change(function(){a(this).parents("form:first").submit()});a(".multipleselectGroup").each(function(i){a(this).multipleSelect()});a(".group .label").each(function(){a(this).html(a(this).html()+":")});a(".required").each(function(){var i=a(this).parents(".group").first().find("label[class!=exclude]:last");if(i.length>0){i.html(i.html().substring(0,i.html().length-1)+'<span class="red">*</span>:')}});a(".groupLink").click(function(){var i=a(this).attr("id").substring(1,a(this).attr("id").length);var j=a("#group_"+i);if(j.is(":hidden")){j.show();a(this).next("span:first").html("&uarr;")}else{j.hide();a(this).next("span:first").html("&darr;")}return false});a(".table :checkbox.toggle").each(function(k,j){a(j).change(function(i){a(j).parents("table:first").find(":checkbox:not(.toggle)").each(function(l,m){m.checked=j.checked;a(m).change()})})});if(a.isIe6){a("button.button").hover(function(){a(this).css({background:"#dedede"})},function(){a(this).css({background:"#EEEEEE"})})}a("*[type=submit]").click(function(){if(a(this).attr("name")){a(".button_hidden").remove();a(this).parents("form:first").append('<input class="button_hidden" type="hidden" name="'+a(this).attr("name")+'" value="1"/>')}if(window.tinyMCE){tinyMCE.triggerSave()}});a("form.validate").each(function(){a(this).validate({errorElement:"span",submitHandler:function(i){if(a.multipleSelect&&a.multipleSelect.instances.length>0){if(a.multipleSelect.preSubmitForm()){setTimeout(function(){i.submit()},10)}}else{i.submit()}}})});a.validator.addMethod("startwith",function(k,i,l){var j=new RegExp("^"+a(i).attr("startwith"));return a.trim(k).match(j)!=null});function c(k,l,m){var n=k.attr("startwith");var i=k.attr("maxlength")?parseInt(k.attr("maxlength")):null;var j=k.attr("translit_ignore");if(n){if(n.match(/[а-я]/)||j){l=g(l)}else{l=l.replace("&","and");l=g(translit(l))}}else{l=h(l,i)}if(l){if(n&&!m){k.val(n+l+"/")}else{if(!m){k.val(l)}}k.blur()}}a("[depends]").each(function(){var i=a(this);var j=null;if(i.attr("depends")){j=a("#"+i.attr("depends"));j=(j.length==1)?j:null}if(j&&(j.attr("disabled")==false||typeof j.attr("disabled")==="undefined")){var k=j.val()?true:false;j.data("alreadyWithValue",k);j.unbind("blur").bind("blur",function(){var m=a(this);var l=a('[depends="'+m.attr("id")+'"]');l.each(function(){var n=a(this).attr("readonly")||(!a.trim(a(this).val())&&a(this).hasClass("required"));c(a(this),m.val(),n?false:m.data("alreadyWithValue"))})});if(i.attr("readonly")||(!a.trim(i.val())&&i.hasClass("required"))){c(i,j.val(),false)}}});a(".charCounter").each(function(){var i=(a(this).attr("maxlength")&&a(this).attr("maxlength")!="-1")?a(this).attr("maxlength"):false;a(this).counter({count:"up",goal:i})});a("#holder").each(function(){a(this).find(".listCat").hover(function(){a(this).addClass("hovered")},function(){a(this).removeClass("hovered")})});a(".table").each(function(){a(this).find("tbody tr").hover(function(){a(this).addClass("hovered")},function(){a(this).removeClass("hovered")});a(this).find("a.deleteLink").click(function(i){a(this).parents("tr:first").addClass("selected-delete")})});a("select.comboboxUI").each(function(){a(this).combobox({autocompleteClass:"adminAutocomplete"})});function d(i){i.parents(".imageRow:first").find("a.addImageLink").hide();a.post(base_url+"/"+admin_url+"/get_resource_info",{image_id:i.val(),html:"true"},function(j){if(j&&j!="ERROR"){i.after(j);i.parents(".imageRow:first").find("a.deleteLink").click(function(k){k.preventDefault();a(this).parents(".imageRow:first").find("input.image").val("");a(this).parents(".imageRow:first").find("a.addImageLink").show();a(this).parents(".imageRow:first").find("div.imageBlock").remove();return false})}else{i.parents(".imageRow:first").find("a.addImageLink").show()}})}a("input.image").each(function(){var i=a(this);if(i.val()){d(i)}});a("#permission-table").each(function(){a("td.action-inner",this).click(function(){var i=a(this).parent("tr").find("input[type=checkbox]").filter(":enabled");if(i.length==i.filter(":checked").length){i.attr("checked",false)}else{i.attr("checked",true)}});a("td.action",this).click(function(){var i=a(this).index();var j=a(this).parents("table:first").find("tr:gt(0)").find("td:eq("+i+") input[type=checkbox]").filter(":enabled");if(j.length==j.filter(":checked").length){j.attr("checked",false)}else{j.attr("checked",true)}})});if(a("#"+entityName+"_parent_id").length>0){a("#"+entityName+"_parent_id").change(function(){a("#"+entityName+"_page_url").attr("disabled","disabled");var i=entityName;if(a("#"+i+"_name").length==0){i="ru_"+entityName}a("#"+entityName+"_name").attr("disabled","disabled");var j=a(this);j.attr("disabled","disabled");a.post(base_url+"/"+admin_url+"/"+entityName+"/get_page_url",{id:j.val()},function(k){if(!k.error){if(k.page_url){a("#"+entityName+"_page_url").attr("startwith",k.page_url);var l=a("#"+entityName+"_page_url").attr("depends");if(typeof l!=="undefined"){c(a("#"+entityName+"_page_url"),a("#"+i+"_name").val(),false)}}}else{alert("An error occurred")}a("#"+entityName+"_page_url").removeAttr("disabled");a("#"+i+"_name").removeAttr("disabled");j.removeAttr("disabled")},"json")});if(a("#"+entityName+"_parent_id").val()!="0"){a("#"+entityName+"_parent_id").change()}}a(".switchselect").each(function(){a(this).switchselect()});a("select:not([name=parent_id]):not(#per_page):not(.chosen-ignore):not(.switchselect):not([disabled=disabled]):not(.pagesSelect)").each(function(){if(!isMobile){a(this).chosen()}});function f(){a("input.select2:visible").not(".select2-offscreen").each(function(){var i=this;a(this).select2({placeholder:a(i).attr("defVal"),minimumInputLength:3,ajax:{url:a(this).attr("dataUrl"),dataType:"json",quietMillis:100,data:function(j,k){return{q:j}},results:function(k,j){return{results:k}}},initSelection:function(j,k){k({id:a(i).val(),text:a(i).attr("defVal")})}})});a("select.select2_multiple:visible").not(".select2-offscreen").each(function(){var i=this;a(this).select2({placeholder:a(i).attr("defVal"),minimumInputLength:3,ajax:{url:a(this).attr("dataUrl"),dataType:"json",quietMillis:100,data:function(l){var k=[];if(a(i).find("option:selected").length>0){a(i).find("option:selected").each(function(m){k.push(a(this).val())})}var j={q:l.term,not:k.join(",")};return a.param(j)},processResults:function(j){return{results:j}},results:function(k,j){return{results:k}}}})})}f();a(".add-field").click(function(){var j=a(this).siblings("ol").children(".sample");var i=j.clone().removeClass("sample");i.find("input,select,textarea").removeAttr("disabled");i.insertBefore(j).show();i.find("select:not(.chosen-ignore)").chosen();j.find("input,select,textarea").each(function(m,n){var k=a(n);var l=k.attr("name");if(l){l=l.replace(/\[([0-9]+)\]/,b);k.attr("name",l)}});f();return false});function b(m,l,j,k,i){return"["+(parseInt(l)+1)+"]"}a(".remove-field").live("click",function(){if(confirm("Удалить?")){a(this).closest("li").remove()}return false})})})(jQuery);