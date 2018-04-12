(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(a){a.defineMode("julia",function(c,E){var e="error";function r(J,I){if(typeof I==="undefined"){I="\\b"}return new RegExp("^(("+J.join(")|(")+"))"+I)}var v="\\\\[0-7]{1,3}";var j="\\\\x[A-Fa-f0-9]{1,2}";var g="\\\\[abfnrtv0%?'\"\\\\]";var q="([^\\u0027\\u005C\\uD800-\\uDFFF]|[\\uD800-\\uDFFF][\\uDC00-\\uDFFF])";var z=E.operators||/^\.?[|&^\\%*+\-<>!=\/]=?|\?|~|:|\$|\.[<>]|<<=?|>>>?=?|\.[<>=]=|->?|\/\/|\bin\b(?!\()|[\u2208\u2209](?!\()/;var l=E.delimiters||/^[;,()[\]{}]/;var m=E.identifiers||/^[_A-Za-z\u00A1-\uFFFF][\w\u00A1-\uFFFF]*!*/;var n=[v,j,g,q];var f=["begin","function","type","immutable","let","macro","for","while","quote","if","else","elseif","try","finally","catch","do"];var B=["end","else","elseif","catch","finally"];var x=["if","else","elseif","while","for","begin","let","end","do","try","catch","finally","return","break","continue","global","local","const","export","import","importall","using","function","macro","module","baremodule","type","immutable","quote","typealias","abstract","bitstype"];var d=["true","false","nothing","NaN","Inf"];var p=/^(`|"{3}|([brv]?"))/;var u=r(n,"'");var o=r(x);var i=r(d);var s=r(f);var h=r(B);var G=/^@[_A-Za-z][\w]*/;var A=/^:[_A-Za-z\u00A1-\uFFFF][\w\u00A1-\uFFFF]*!*/;var b=/^::[^,;"{()=$\s]+({[^}]*}+)*/;function D(J){var I=C(J);if(I=="["){return true}return false}function C(I){if(I.scopes.length==0){return null}return I.scopes[I.scopes.length-1]}function H(O,K){if(O.match(/^#=/,false)){K.tokenize=t;return K.tokenize(O,K)}var P=K.leavingExpr;if(O.sol()){P=false}K.leavingExpr=false;if(P){if(O.match(/^'+/)){return"operator"}}if(O.match(/^\.{2,3}/)){return"operator"}if(O.eatSpace()){return null}var I=O.peek();if(I==="#"){O.skipToEnd();return"comment"}if(I==="["){K.scopes.push("[")}if(I==="("){K.scopes.push("(")}var Q=C(K);if(Q=="["&&I==="]"){K.scopes.pop();K.leavingExpr=true}if(Q=="("&&I===")"){K.scopes.pop();K.leavingExpr=true}var L;if(!D(K)&&(L=O.match(s,false))){K.scopes.push(L)}if(!D(K)&&O.match(h,false)){K.scopes.pop()}if(D(K)){if(K.lastToken=="end"&&O.match(/^:/)){return"operator"}if(O.match(/^end/)){return"number"}}if(O.match(/^=>/)){return"operator"}if(O.match(/^[0-9\.]/,false)){var J=RegExp(/^im\b/);var M=false;if(O.match(/^\d*\.(?!\.)\d*([Eef][\+\-]?\d+)?/i)){M=true}if(O.match(/^\d+\.(?!\.)\d*/)){M=true}if(O.match(/^\.\d+/)){M=true}if(O.match(/^0x\.[0-9a-f]+p[\+\-]?\d+/i)){M=true}if(O.match(/^0x[0-9a-f]+/i)){M=true}if(O.match(/^0b[01]+/i)){M=true}if(O.match(/^0o[0-7]+/i)){M=true}if(O.match(/^[1-9]\d*(e[\+\-]?\d+)?/)){M=true}if(O.match(/^0(?![\dx])/i)){M=true}if(M){O.match(J);K.leavingExpr=true;return"number"}}if(O.match(/^<:/)){return"operator"}if(O.match(b)){return"builtin"}if(!P&&O.match(A)||O.match(/:\./)){return"builtin"}if(O.match(/^{[^}]*}(?=\()/)){return"builtin"}if(O.match(z)){return"operator"}if(O.match(/^'/)){K.tokenize=w;return K.tokenize(O,K)}if(O.match(p)){K.tokenize=y(O.current());return K.tokenize(O,K)}if(O.match(G)){return"meta"}if(O.match(l)){return null}if(O.match(o)){return"keyword"}if(O.match(i)){return"builtin"}var N=K.isDefinition||K.lastToken=="function"||K.lastToken=="macro"||K.lastToken=="type"||K.lastToken=="immutable";if(O.match(m)){if(N){if(O.peek()==="."){K.isDefinition=true;return"variable"}K.isDefinition=false;return"def"}if(O.match(/^({[^}]*})*\(/,false)){return F(O,K)}K.leavingExpr=true;return"variable"}O.next();return e}function F(L,K){var J=L.match(/^(\(\s*)/);if(J){if(K.firstParenPos<0){K.firstParenPos=K.scopes.length}K.scopes.push("(");K.charsAdvanced+=J[1].length}if(C(K)=="("&&L.match(/^\)/)){K.scopes.pop();K.charsAdvanced+=1;if(K.scopes.length<=K.firstParenPos){var I=L.match(/^\s*?=(?!=)/,false);L.backUp(K.charsAdvanced);K.firstParenPos=-1;K.charsAdvanced=0;if(I){return"def"}return"builtin"}}if(L.match(/^$/g,false)){L.backUp(K.charsAdvanced);while(K.scopes.length>K.firstParenPos){K.scopes.pop()}K.firstParenPos=-1;K.charsAdvanced=0;return"builtin"}K.charsAdvanced+=L.match(/^([^()]*)/)[1].length;return F(L,K)}function t(J,I){if(J.match(/^#=/)){I.weakScopes++}if(!J.match(/.*?(?=(#=|=#))/)){J.skipToEnd()}if(J.match(/^=#/)){I.weakScopes--;if(I.weakScopes==0){I.tokenize=H}}return"comment"}function w(M,L){var J=false,I;if(M.match(u)){J=true}else{if(I=M.match(/\\u([a-f0-9]{1,4})(?=')/i)){var K=parseInt(I[1],16);if(K<=55295||K>=57344){J=true;M.next()}}else{if(I=M.match(/\\U([A-Fa-f0-9]{5,8})(?=')/)){var K=parseInt(I[1],16);if(K<=1114111){J=true;M.next()}}}}if(J){L.leavingExpr=true;L.tokenize=H;return"string"}if(!M.match(/^[^']+(?=')/)){M.skipToEnd()}if(M.match(/^'/)){L.tokenize=H}return e}function y(I){while("bruv".indexOf(I.charAt(0).toLowerCase())>=0){I=I.substr(1)}var J="string";function K(M,L){while(!M.eol()){M.eatWhile(/[^"\\]/);if(M.eat("\\")){M.next()}else{if(M.match(I)){L.tokenize=H;L.leavingExpr=true;return J}else{M.eat(/["]/)}}}return J}K.isString=true;return K}var k={startState:function(){return{tokenize:H,scopes:[],weakScopes:0,lastToken:null,leavingExpr:false,isDefinition:false,charsAdvanced:0,firstParenPos:-1}},token:function(L,J){var I=J.tokenize(L,J);var K=L.current();if(K&&I){J.lastToken=K}if(K==="."){I=L.match(m,false)||L.match(G,false)||L.match(/\(/,false)?"operator":e}return I},indent:function(J,I){var K=0;if(I=="]"||I==")"||I=="end"||I=="else"||I=="elseif"||I=="catch"||I=="finally"){K=-1}return(J.scopes.length+K)*c.indentUnit},electricInput:/(end|else(if)?|catch|finally)$/,lineComment:"#",fold:"indent"};return k});a.defineMIME("text/x-julia","julia")});