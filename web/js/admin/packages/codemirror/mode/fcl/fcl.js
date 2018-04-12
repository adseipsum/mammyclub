(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(a){a.defineMode("fcl",function(c){var g=c.indentUnit;var f={term:true,method:true,accu:true,rule:true,then:true,is:true,and:true,or:true,"if":true,"default":true};var d={var_input:true,var_output:true,fuzzify:true,defuzzify:true,function_block:true,ruleblock:true};var i={end_ruleblock:true,end_defuzzify:true,end_function_block:true,end_fuzzify:true,end_var:true};var h={"true":true,"false":true,nan:true,real:true,min:true,max:true,cog:true,cogs:true};var b=/[+\-*&^%:=<>!|\/]/;function e(q,o){var n=q.next();if(/[\d\.]/.test(n)){if(n=="."){q.match(/^[0-9]+([eE][\-+]?[0-9]+)?/)}else{if(n=="0"){q.match(/^[xX][0-9a-fA-F]+/)||q.match(/^0[0-7]+/)}else{q.match(/^[0-9]*\.?[0-9]*([eE][\-+]?[0-9]+)?/)}}return"number"}if(n=="/"||n=="("){if(q.eat("*")){o.tokenize=k;return k(q,o)}if(q.eat("/")){q.skipToEnd();return"comment"}}if(b.test(n)){q.eatWhile(b);return"operator"}q.eatWhile(/[\w\$_\xa1-\uffff]/);var p=q.current().toLowerCase();if(f.propertyIsEnumerable(p)||d.propertyIsEnumerable(p)||i.propertyIsEnumerable(p)){return"keyword"}if(h.propertyIsEnumerable(p)){return"atom"}return"variable"}function k(q,p){var n=false,o;while(o=q.next()){if((o=="/"||o==")")&&n){p.tokenize=e;break}n=(o=="*")}return"comment"}function m(r,o,n,q,p){this.indented=r;this.column=o;this.type=n;this.align=q;this.prev=p}function j(p,n,o){return p.context=new m(p.indented,n,o,null,p.context)}function l(o){if(!o.context.prev){return}var n=o.context.type;if(n=="end_block"){o.indented=o.context.indented}return o.context=o.context.prev}return{startState:function(n){return{tokenize:null,context:new m((n||0)-g,0,"top",false),indented:0,startOfLine:true}},token:function(r,p){var n=p.context;if(r.sol()){if(n.align==null){n.align=false}p.indented=r.indentation();p.startOfLine=true}if(r.eatSpace()){return null}var o=(p.tokenize||e)(r,p);if(o=="comment"){return o}if(n.align==null){n.align=true}var q=r.current().toLowerCase();if(d.propertyIsEnumerable(q)){j(p,r.column(),"end_block")}else{if(i.propertyIsEnumerable(q)){l(p)}}p.startOfLine=false;return o},indent:function(q,o){if(q.tokenize!=e&&q.tokenize!=null){return 0}var n=q.context;var p=i.propertyIsEnumerable(o);if(n.align){return n.column+(p?0:1)}else{return n.indented+(p?0:g)}},electricChars:"ryk",fold:"brace",blockCommentStart:"(*",blockCommentEnd:"*)",lineComment:"//"}});a.defineMIME("text/x-fcl","fcl")});