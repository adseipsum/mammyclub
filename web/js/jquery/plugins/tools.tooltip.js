(function(d){d.tools=d.tools||{version:"1.2.5"};d.tools.tooltip={conf:{effect:"toggle",fadeOutSpeed:"fast",predelay:0,delay:30,opacity:1,tip:0,position:["top","center"],offset:[0,0],relative:false,cancelDefault:true,events:{def:"mouseenter,mouseleave",input:"focus,blur",widget:"focus mouseenter,blur mouseleave",tooltip:"mouseenter,mouseleave"},layout:"<div/>",tipClass:"tooltip"},addEffect:function(e,g,f){c[e]=[g,f]}};var c={toggle:[function(e){var f=this.getConf(),g=this.getTip(),h=f.opacity;if(h<1){g.css({opacity:h})}g.show();e.call()},function(e){this.getTip().hide();e.call()}],fade:[function(e){var f=this.getConf();this.getTip().fadeTo(f.fadeInSpeed,f.opacity,e)},function(e){this.getTip().fadeOut(this.getConf().fadeOutSpeed,e)}]};function b(g,i,f){var k=f.relative?g.position().top:g.offset().top,j=f.relative?g.position().left:g.offset().left,l=f.position[0];k-=i.outerHeight()-f.offset[0];j+=g.outerWidth()+f.offset[1];if(/iPad/i.test(navigator.userAgent)){k-=d(window).scrollTop()}var e=i.outerHeight()+g.outerHeight();if(l=="center"){k+=e/2}if(l=="bottom"){k+=e}l=f.position[1];var h=i.outerWidth()+g.outerWidth();if(l=="center"){j-=h/2}if(l=="left"){j-=h}return{top:k,left:j}}function a(h,j){var r=this,g=h.add(r),o,f=0,q=0,m=h.attr("title"),i=h.attr("data-tooltip"),s=c[j.effect],n,l=h.is(":input"),e=l&&h.is(":checkbox, :radio, select, :button, :submit"),k=h.attr("type"),p=j.events[k]||j.events[l?(e?"widget":"input"):"def"];if(!s){throw'Nonexistent effect "'+j.effect+'"'}p=p.split(/,\s*/);if(p.length!=2){throw"Tooltip: bad events configuration for "+k}d(h).bind(p[0],function(t){clearTimeout(f);if(j.predelay){q=setTimeout(function(){r.show(t)},j.predelay)}else{r.show(t)}}).bind(p[1],function(t){clearTimeout(q);if(j.delay){f=setTimeout(function(){r.hide(t)},j.delay)}else{r.hide(t)}});if(m&&j.cancelDefault){h.removeAttr("title");h.data("title",m)}d.extend(r,{show:function(u){if(!o){if(i){o=d(i)}else{if(j.tip){o=d(j.tip).eq(0)}else{if(m){o=d(j.layout).addClass(j.tipClass).appendTo(document.body).hide().append(m)}else{o=h.next();if(!o.length){o=h.parent().next()}}}}if(!o.length){throw"Cannot find tooltip for "+h}}if(r.isShown()){return r}o.stop(true,true);var v=b(h,o,j);if(j.tip){o.html(h.data("title"))}u=jQuery.extend(true,{},u)||jQuery.extend(true,{},d.Event());u.type="onBeforeShow";g.trigger(u,[v]);if(u.isDefaultPrevented()){return r}v=b(h,o,j);o.css({position:"absolute",top:v.top,left:v.left});n=true;s[0].call(r,function(){u.type="onShow";n="full";g.trigger(u)});var t=j.events.tooltip.split(/,\s*/);if(!o.data("__set")){o.bind(t[0],function(){clearTimeout(f);clearTimeout(q)});if(t[1]&&!h.is("input:not(:checkbox, :radio), textarea")){o.bind(t[1],function(w){if(w.relatedTarget!=h[0]){h.trigger(p[1].split(" ")[0])}})}o.data("__set",true)}return r},hide:function(t){if(!o||!r.isShown()){return r}t=t||d.Event();t.type="onBeforeHide";g.trigger(t);if(t.isDefaultPrevented()){return}n=false;c[j.effect][1].call(r,function(){t.type="onHide";g.trigger(t)});return r},isShown:function(t){return t?n=="full":n},getConf:function(){return j},getTip:function(){return o},getTrigger:function(){return h}});d.each("onHide,onBeforeShow,onShow,onBeforeHide".split(","),function(u,t){if(d.isFunction(j[t])){d(r).bind(t,j[t])}r[t]=function(v){if(v){d(r).bind(t,v)}return r}})}d.fn.tooltip=function(e){var f=this.data("tooltip");if(f){return f}e=d.extend(true,{},d.tools.tooltip.conf,e);if(typeof e.position=="string"){e.position=e.position.split(/,?\s/)}this.each(function(){f=new a(d(this),e);d(this).data("tooltip",f)});return e.api?f:this}})(jQuery);