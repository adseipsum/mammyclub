(function(a){a.extend(a.fn,{selectChain:function(b){return new a.selectChain(b,this)}});a.selectChain=function(b,c){this.settings=a.extend({},a.selectChain.defaults,b);this.selects=c;this.init()};a.extend(a.selectChain,{defaults:{remoteUrl:"",loadingImgPath:base_url+"web/images/loading.gif",data:null},prototype:{init:function(){var d=this;d.loading=false;if(this.settings.loadingImgPath){d.loading=a('<img class="loading" src="'+this.settings.loadingImgPath+'"/>')}var c=d.selects;if(c.length>1){for(var b=0;b<c.length;b++){if(b==0){c[b].defText=a(c[b]).find("option:first").html()}else{c[b].defText=c[b].title;c[b].title=""}}c.filter(":not(:first)").attr("disabled",true);d.flushSelects(c);c.filter(":not(:last)").change(function(){d.changeHandler(this)});if(d.settings.data){d.updateSelectChain(0)}}},updateSelectChain:function(g){var f=this,d=f.selects,e=f.settings.data[g];for(var h in e){if(e[h]){var b=d.filter("[name="+h+"]");b.val(e[h]);var c=f.selects.index(b);if(c<f.selects.length-1){f.changeHandler(b,function(){if(f.settings.data[g+1]){f.updateSelectChain(g+1)}})}}}},flushSelects:function(d){var c,b=0;while(c=d[b]){b++;var f=c.defText;var e=a(d[b]);if(e){e.find("option:first").html(f)}}},generateOptions:function(e){var c="";for(var b in e){var d=e[b];c+='<option value="'+d.id+'">'+d.name+"</option>"}return c},changeHandler:function(g,i){var f=this;var h=f.selects.filter(":gt("+f.selects.index(g)+")");h.attr("disabled",true).val("");var c=a(g);var e=c.val();if(e){f.flushSelects(h);c.attr("disabled",true);var b=c.attr("name");var d={};d[b]=e;a.post(f.settings.remoteUrl,d,function(j){c.attr("disabled",false);a(h[0]).find("option[value!=]").remove();if(j&&j.length>0){a(h[0]).append(f.generateOptions(j)).attr("disabled",false).find("option:first").html(h[0].defText);if(i){i()}}},"json")}else{f.flushSelects([g].concat(h.toArray()))}},setData:function(b){this.settings.data=b;this.updateSelectChain(0)}}})})(jQuery);