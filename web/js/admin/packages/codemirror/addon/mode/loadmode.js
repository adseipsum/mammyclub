(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"),"cjs")}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],function(b){a(b,"amd")})}else{a(CodeMirror,"plain")}}})(function(a,c){if(!a.modeURL){a.modeURL="../mode/%N/%N.js"}var e={};function d(f,h){var g=h;return function(){if(--g==0){f()}}}function b(l,f){var k=a.modes[l].dependencies;if(!k){return f()}var j=[];for(var h=0;h<k.length;++h){if(!a.modes.hasOwnProperty(k[h])){j.push(k[h])}}if(!j.length){return f()}var g=d(f,j.length);for(var h=0;h<j.length;++h){a.requireMode(j[h],g)}}a.requireMode=function(k,f){if(typeof k!="string"){k=k.name}if(a.modes.hasOwnProperty(k)){return b(k,f)}if(e.hasOwnProperty(k)){return e[k].push(f)}var h=a.modeURL.replace(/%N/g,k);if(c=="plain"){var g=document.createElement("script");g.src=h;var i=document.getElementsByTagName("script")[0];var j=e[k]=[f];a.on(g,"load",function(){b(k,function(){for(var l=0;l<j.length;++l){j[l]()}})});i.parentNode.insertBefore(g,i)}else{if(c=="cjs"){require(h);f()}else{if(c=="amd"){requirejs([h],f)}}}};a.autoLoadMode=function(f,g){if(!a.modes.hasOwnProperty(g)){a.requireMode(g,function(){f.setOption("mode",f.getOption("mode"))})}}});