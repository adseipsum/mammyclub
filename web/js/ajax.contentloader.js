(function(a){a.extend(a.fn,{contentloader:function(b){var c=a.data(this[0],"contentloader");if(c){return c}c=new a.contentloader(b,this[0]);a.data(this[0],"contentloader",c);return c}});a.contentloader=function(c,b){this.settings=a.extend({},a.contentloader.defaults,c);this.container=a(b);this.start=true;this.page=2;this.init()};a.extend(a.contentloader,{defaults:{url:""},prototype:{init:function(){$this=this;var b=$this.container.parent();var g=$this.container.prev();var f;var h=0;a(window).unbind("scroll").scroll(function(){c()});function c(){if(a(window).scrollTop()>=a(document).height()-(a(window).height()+a("#footer").height()+h)){if(!a("#chart").is(":visible")){d()}}}function e(i){if(i>=$this.page){$this.start=false;a("html").animate({scrollTop:(parseInt(a(document).height()))},"fast",function(){$this.start=true;d(function(){e(i)})})}else{var j=a(document).height()-a(window).height()-5;a("html").animate({scrollTop:j},"fast")}}function d(j){if($this.start){$this.start=false;$this.container.show();var i="?";if($this.settings.url.indexOf("?")>0){i="&"}a(window).resize();a.get($this.settings.url+i+"p="+$this.page,function(k){$this.container.hide();if(k){a("#js-list li").removeClass("last");var l=jQuery.parseJSON(k);a(l.product_list_html).appendTo(b);$this.page++;a(window).resize();if(a(".js-broadcast-block").length>0){sidebarBlockScrolling(a(".js-broadcast-block"))}$this.start=true;if(l.continue_requests==false){$this.start=false}if(j){j()}}else{$this.start=false}})}}}}})})(jQuery);