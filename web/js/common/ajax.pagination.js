(function(a){a.extend(a.fn,{ajaxlist:function(c){var b=a.data(this[0],"ajaxlist");if(b){return b}b=new a.ajaxlist(c,this[0]);a.data(this[0],"ajaxlist",b);return b}});a.ajaxlist=function(c,b){this.settings=a.extend({},a.ajaxlist.defaults,c);this.container=a(b);this.start=false;this.init()};a.extend(a.ajaxlist,{defaults:{url:"",hover:false},prototype:{init:function(){$this=this;$this.container.show();a.address.init(function(b){}).change(function(b){$this.container.mask("&nbsp;");a.post($this.settings.url+b.value,function(c){$this.container.unmask();$this.container.html(c);$this.container.find("ul.paginator a").address(function(){var d=a(this).attr("href");return d.substring($this.settings.url.length)});if($this.settings.hover&&a.initHover){a.initHover($this.container)}if(this.start&&a.scrollTo){a.scrollTo(a("body")[0],300,{axis:"y"})}if(a.initTableToggle){a.initTableToggle($this.container)}if(a.initButtonEffect){a.initButtonEffect($this.container)}if($this.settings.callback){$this.settings.callback()}this.start=true})})}}})})(jQuery);