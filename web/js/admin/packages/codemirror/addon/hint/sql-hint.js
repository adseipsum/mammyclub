(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"),require("../../mode/sql/sql"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror","../../mode/sql/sql"],a)}else{a(CodeMirror)}}})(function(p){var h;var s;var i;var j={QUERY_DIV:";",ALIAS_KEYWORD:"AS"};var o=p.Pos;function l(w){return Object.prototype.toString.call(w)=="[object Array]"}function d(w){var x=w.doc.modeOption;if(x==="sql"){x="text/x-sql"}return p.resolveMode(x).keywords}function a(w){return typeof w=="string"?w:w.text}function b(w,x){if(l(x)){x={columns:x}}if(!x.text){x.text=w}return x}function q(x){var w={};if(l(x)){for(var z=x.length-1;z>=0;z--){var A=x[z];w[a(A).toUpperCase()]=b(a(A),A)}}else{if(x){for(var y in x){w[y.toUpperCase()]=b(y,x[y])}}}return w}function c(w){return h[w.toUpperCase()]}function g(x){var w={};for(var y in x){if(x.hasOwnProperty(y)){w[y]=x[y]}}return w}function e(x,z){var w=x.length;var y=a(z).substr(0,w);return x.toUpperCase()===y.toUpperCase()}function f(w,z,A,y){if(l(A)){for(var x=0;x<A.length;x++){if(e(z,A[x])){w.push(y(A[x]))}}}else{for(var B in A){if(A.hasOwnProperty(B)){var C=A[B];if(!C||C===true){C=B}else{C=C.displayText?{text:C.text,displayText:C.displayText}:C.text}if(e(z,C)){w.push(y(C))}}}}}function k(w){if(w.charAt(0)=="."){w=w.substr(1)}return w.replace(/`/g,"")}function r(x){var w=a(x).split(".");for(var y=0;y<w.length;y++){w[y]="`"+w[y]+"`"}var z=w.join(".");if(typeof x=="string"){return z}x=g(x);x.text=z;return x}function u(G,z,I,E){var F=false;var A=[];var w=z.start;var J=true;while(J){J=(z.string.charAt(0)==".");F=F||(z.string.charAt(0)=="`");w=z.start;A.unshift(k(z.string));z=E.getTokenAt(o(G.line,z.start));if(z.string=="."){J=true;z=E.getTokenAt(o(G.line,z.start))}}var D=A.join(".");f(I,D,h,function(K){return F?r(K):K});f(I,D,s,function(K){return F?r(K):K});D=A.pop();var H=A.join(".");var C=false;var B=H;if(!c(H)){var x=H;H=m(H,E);if(H!==x){C=true}}var y=c(H);if(y&&y.columns){y=y.columns}if(y){f(I,D,y,function(K){var L=H;if(C==true){L=B}if(typeof K=="string"){K=L+"."+K}else{K=g(K);K.text=L+"."+K.text}return F?r(K):K})}return w}function v(x,z){if(!x){return}var w=/[,;]/g;var A=x.split(" ");for(var y=0;y<A.length;y++){z(A[y]?A[y].replace(w,""):"")}}function t(w){return w.line+w.ch/Math.pow(10,6)}function n(w){return o(Math.floor(w),+w.toString().split(".").pop())}function m(B,C){var K=C.doc;var x=K.getValue();var w=B.toUpperCase();var D="";var L="";var A=[];var J={start:o(0,0),end:o(C.lastLine(),C.getLineHandle(C.lastLine()).length)};var z=x.indexOf(j.QUERY_DIV);while(z!=-1){A.push(K.posFromIndex(z));z=x.indexOf(j.QUERY_DIV,z+1)}A.unshift(o(0,0));A.push(o(C.lastLine(),C.getLineHandle(C.lastLine()).text.length));var H=0;var E=t(C.getCursor());for(var y=0;y<A.length;y++){var I=t(A[y]);if(E>H&&E<=I){J={start:n(H),end:n(I)};break}H=I}var F=K.getRange(J.start,J.end,false);for(var y=0;y<F.length;y++){var G=F[y];v(G,function(N){var M=N.toUpperCase();if(M===w&&c(D)){L=D}if(M!==j.ALIAS_KEYWORD){D=N}});if(L){break}}return L}p.registerHelper("hint","sql",function(A,E){h=q(E&&E.tables);var x=E&&E.defaultTable;var B=E&&E.disableKeywords;s=x&&c(x);i=i||d(A);if(x&&!s){s=m(x,A)}s=s||[];if(s.columns){s=s.columns}var C=A.getCursor();var F=[];var y=A.getTokenAt(C),w,z,D;if(y.end>C.ch){y.end=C.ch;y.string=y.string.slice(0,C.ch-y.start)}if(y.string.match(/^[.`\w@]\w*$/)){D=y.string;w=y.start;z=y.end}else{w=z=C.ch;D=""}if(D.charAt(0)=="."||D.charAt(0)=="`"){w=u(C,y,F,A)}else{f(F,D,h,function(G){return G});f(F,D,s,function(G){return G});if(!B){f(F,D,i,function(G){return G.toUpperCase()})}}return{list:F,from:o(C.line,w),to:o(C.line,z)}})});