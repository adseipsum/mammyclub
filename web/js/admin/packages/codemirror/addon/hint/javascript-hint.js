(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(d){var k=d.Pos;function g(o,q){for(var p=0,r=o.length;p<r;++p){q(o[p])}}function e(o,q){if(!Array.prototype.indexOf){var p=o.length;while(p--){if(o[p]===q){return true}}return false}return o.indexOf(q)!=-1}function h(t,s,v,p){var u=t.getCursor(),r=v(t,u);if(/\b(?:string|comment)\b/.test(r.type)){return}r.state=d.innerMode(t.getMode(),r.state).state;if(!/^[\w$_]*$/.test(r.string)){r={start:u.ch,end:u.ch,string:"",state:r.state,type:r.string=="."?"property":null}}else{if(r.end>u.ch){r.end=u.ch;r.string=r.string.slice(0,u.ch-r.start)}}var o=r;while(o.type=="property"){o=v(t,k(u.line,o.start));if(o.string!="."){return}o=v(t,k(u.line,o.start));if(!q){var q=[]}q.push(o)}return{list:i(r,q,s,p),from:k(u.line,r.start),to:k(u.line,r.end)}}function a(p,o){return h(p,c,function(q,r){return q.getTokenAt(r)},o)}d.registerHelper("hint","javascript",a);function b(p,q){var o=p.getTokenAt(q);if(q.ch==o.start+1&&o.string.charAt(0)=="."){o.end=o.start;o.string=".";o.type="property"}else{if(/^\.[\w$_]*$/.test(o.string)){o.type="property";o.start++;o.string=o.string.replace(/\./,"")}}return o}function j(p,o){return h(p,m,b,o)}d.registerHelper("hint","coffeescript",j);var l=("charAt charCodeAt indexOf lastIndexOf substring substr slice trim trimLeft trimRight toUpperCase toLowerCase split concat match replace search").split(" ");var n=("length concat join splice push pop shift unshift slice reverse sort indexOf lastIndexOf every some filter forEach map reduce reduceRight ").split(" ");var f="prototype apply call bind".split(" ");var c=("break case catch continue debugger default delete do else false finally for function if in instanceof new null return switch throw true try typeof var void while with").split(" ");var m=("and break catch class continue delete do else extends false finally for if in instanceof isnt new no not null of off on or return switch then throw true try typeof until void while with yes").split(" ");function i(t,s,x,A){var z=[],q=t.string,r=A&&A.globalScope||window;function u(v){if(v.lastIndexOf(q,0)==0&&!e(z,v)){z.push(v)}}function p(B){if(typeof B=="string"){g(l,u)}else{if(B instanceof Array){g(n,u)}else{if(B instanceof Function){g(f,u)}}}for(var v in B){u(v)}}if(s&&s.length){var w=s.pop(),o;if(w.type&&w.type.indexOf("variable")===0){if(A&&A.additionalContext){o=A.additionalContext[w.string]}if(!A||A.useGlobalScope!==false){o=o||r[w.string]}}else{if(w.type=="string"){o=""}else{if(w.type=="atom"){o=1}else{if(w.type=="function"){if(r.jQuery!=null&&(w.string=="$"||w.string=="jQuery")&&(typeof r.jQuery=="function")){o=r.jQuery()}else{if(r._!=null&&(w.string=="_")&&(typeof r._=="function")){o=r._()}}}}}}while(o!=null&&s.length){o=o[s.pop().string]}if(o!=null){p(o)}}else{for(var y=t.state.localVars;y;y=y.next){u(y.name)}for(var y=t.state.globalVars;y;y=y.next){u(y.name)}if(!A||A.useGlobalScope!==false){p(r)}g(x,u)}return z}});