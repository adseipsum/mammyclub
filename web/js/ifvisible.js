(function(){var f,j,l,m,i,a,h,b,d,n,c,e,g,k;d={};l=document;c=false;e="active";h=60000;a=false;j=(function(){var q,o,t,s,p,r;q=function(){return(((1+Math.random())*65536)|0).toString(16).substring(1)};p=function(){return q()+q()+"-"+q()+"-"+q()+"-"+q()+"-"+q()+q()+q()};r={};t="__ceGUID";o=function(v,u,w){v[t]=undefined;if(!v[t]){v[t]="ifvisible.object.event.identifier"}if(!r[v[t]]){r[v[t]]={}}if(!r[v[t]][u]){r[v[t]][u]=[]}return r[v[t]][u].push(w)};s=function(B,y,w){var x,A,v,z,u;if(B[t]&&r[B[t]]&&r[B[t]][y]){z=r[B[t]][y];u=[];for(A=0,v=z.length;A<v;A++){x=z[A];u.push(x(w||{}))}return u}};return{add:o,fire:s}})();f=(function(){var o;o=false;return function(q,r,p){if(!o){if(q.addEventListener){o=function(t,u,s){return t.addEventListener(u,s,false)}}else{if(q.attachEvent){o=function(t,u,s){return t.attachEvent("on"+u,s,false)}}else{o=function(t,u,s){return t["on"+u]=s}}}}return o(q,r,p)}})();m=function(p,q){var o;if(l.createEventObject){return p.fireEvent("on"+q,o)}else{o=l.createEvent("HTMLEvents");o.initEvent(q,true,true);return !p.dispatchEvent(o)}};b=(function(){var r,o,s,q,p;q=void 0;p=3;s=l.createElement("div");r=s.getElementsByTagName("i");o=function(){return(s.innerHTML="<!--[if gt IE "+(++p)+"]><i></i><![endif]-->",r[0])};while(o()){continue}if(p>4){return p}else{return q}})();i=false;k=void 0;if(typeof l.hidden!=="undefined"){i="hidden";k="visibilitychange"}else{if(typeof l.mozHidden!=="undefined"){i="mozHidden";k="mozvisibilitychange"}else{if(typeof l.msHidden!=="undefined"){i="msHidden";k="msvisibilitychange"}else{if(typeof l.webkitHidden!=="undefined"){i="webkitHidden";k="webkitvisibilitychange"}}}}g=function(){var p,o;p=false;o=function(){clearTimeout(p);if(e!=="active"){d.wakeup()}a=+(new Date());return p=setTimeout(function(){if(e==="active"){return d.idle()}},h)};o();f(l,"mousemove",o);f(l,"keyup",o);f(window,"scroll",o);return d.focus(o)};n=function(){var o;if(c){return true}if(i===false){o="blur";if(b<9){o="focusout"}f(window,o,function(){return d.blur()});f(window,"focus",function(){return d.focus()})}else{f(l,k,function(){if(l[i]){return d.blur()}else{return d.focus()}},false)}c=true;return g()};d={setIdleDuration:function(o){return h=o*1000},getIdleDuration:function(){return h},getIdleInfo:function(){var o,p;o=+(new Date());p={};if(e==="idle"){p.isIdle=true;p.idleFor=o-a;p.timeLeft=0;p.timeLeftPer=100}else{p.isIdle=false;p.idleFor=o-a;p.timeLeft=(a+h)-o;p.timeLeftPer=(100-(p.timeLeft*100/h)).toFixed(2)}return p},focus:function(o){if(typeof o==="function"){return this.on("focus",o)}e="active";j.fire(this,"focus");j.fire(this,"wakeup");return j.fire(this,"statusChanged",{status:e})},blur:function(o){if(typeof o==="function"){return this.on("blur",o)}e="hidden";j.fire(this,"blur");j.fire(this,"idle");return j.fire(this,"statusChanged",{status:e})},idle:function(o){if(typeof o==="function"){return this.on("idle",o)}e="idle";j.fire(this,"idle");return j.fire(this,"statusChanged",{status:e})},wakeup:function(o){if(typeof o==="function"){return this.on("wakeup",o)}e="active";j.fire(this,"wakeup");return j.fire(this,"statusChanged",{status:e})},on:function(o,p){n();return j.add(this,o,p)},onEvery:function(p,q){var o;n();o=setInterval(function(){if(e==="active"){return q()}},p*1000);return{stop:function(){return clearInterval(o)},code:o,callback:q}},now:function(){n();return e==="active"}};if(typeof define==="function"&&define.amd){define(function(){return d})}else{window.ifvisible=d}}).call(this);