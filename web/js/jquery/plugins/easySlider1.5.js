(function(a){a.fn.easySlider=function(b){var c={prevId:"prevBtn",prevText:"Previous",nextId:"nextBtn",nextText:"Next",controlsShow:true,controlsBefore:"",controlsAfter:"",controlsFade:true,firstId:"firstBtn",firstText:"First",firstShow:false,lastId:"lastBtn",lastText:"Last",lastShow:false,vertical:false,speed:800,auto:false,pause:2000,continuous:false};var b=a.extend(c,b);this.each(function(){var e=a(this);var m=a("li",e).length;var k=a("li",e).width();var f=a("li",e).height();e.width(k);e.height(f);e.css("overflow","hidden");var i=m-1;var l=0;a("ul",e).css("width",m*k);if(!b.vertical){a("li",e).css("float","left")}if(b.controlsShow){var g=b.controlsBefore;if(b.firstShow){g+='<span id="'+b.firstId+'"><a href="javascript:void(0);">'+b.firstText+"</a></span>"}g+=' <span id="'+b.prevId+'"><a href="javascript:void(0);">'+b.prevText+"</a></span>";g+=' <span id="'+b.nextId+'"><a href="javascript:void(0);">'+b.nextText+"</a></span>";if(b.lastShow){g+=' <span id="'+b.lastId+'"><a href="javascript:void(0);">'+b.lastText+"</a></span>"}g+=b.controlsAfter;a(e).after(g)}a("a","#"+b.nextId).click(function(){d("next",true)});a("a","#"+b.prevId).click(function(){d("prev",true)});a("a","#"+b.firstId).click(function(){d("first",true)});a("a","#"+b.lastId).click(function(){d("last",true)});function d(h,n){var o=l;switch(h){case"next":l=(o>=i)?(b.continuous?0:i):l+1;break;case"prev":l=(l<=0)?(b.continuous?i:0):l-1;break;case"first":l=0;break;case"last":l=i;break;default:break}var r=Math.abs(o-l);var q=r*b.speed;if(!b.vertical){p=(l*k*-1);a("ul",e).animate({marginLeft:p},q)}else{p=(l*f*-1);a("ul",e).animate({marginTop:p},q)}if(!b.continuous&&b.controlsFade){if(l==i){a("a","#"+b.nextId).addClass("hidden");a("a","#"+b.lastId).addClass("hidden")}else{a("a","#"+b.nextId).removeClass("hidden");a("a","#"+b.lastId).removeClass("hidden")}if(l==0){a("a","#"+b.prevId).addClass("hidden");a("a","#"+b.firstId).addClass("hidden")}else{a("a","#"+b.prevId).removeClass("hidden");a("a","#"+b.firstId).removeClass("hidden")}}if(n){clearTimeout(j)}if(b.auto&&h=="next"&&!n){j=setTimeout(function(){d("next",false)},r*b.speed+b.pause)}}var j;if(b.auto){j=setTimeout(function(){d("next",false)},b.pause)}if(!b.continuous&&b.controlsFade){a("a","#"+b.prevId).addClass("hidden");a("a","#"+b.firstId).addClass("hidden");if(m==1){a("a","#"+b.nextId).addClass("hidden");a("a","#"+b.lastId).addClass("hidden")}}})}})(jQuery);