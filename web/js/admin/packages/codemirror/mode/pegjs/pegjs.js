(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"),require("../javascript/javascript"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror","../javascript/javascript"],a)}else{a(CodeMirror)}}})(function(a){a.defineMode("pegjs",function(c){var d=a.getMode(c,"javascript");function b(e){return e.match(/^[a-zA-Z_][a-zA-Z0-9_]*/)}return{startState:function(){return{inString:false,stringType:null,inComment:false,inCharacterClass:false,braced:0,lhs:true,localState:null}},token:function(j,g){if(j){if(!g.inString&&!g.inComment&&((j.peek()=='"')||(j.peek()=="'"))){g.stringType=j.peek();j.next();g.inString=true}}if(!g.inString&&!g.inComment&&j.match(/^\/\*/)){g.inComment=true}if(g.inString){while(g.inString&&!j.eol()){if(j.peek()===g.stringType){j.next();g.inString=false}else{if(j.peek()==="\\"){j.next();j.next()}else{j.match(/^.[^\\\"\']*/)}}}return g.lhs?"property string":"string"}else{if(g.inComment){while(g.inComment&&!j.eol()){if(j.match(/\*\//)){g.inComment=false}else{j.match(/^.[^\*]*/)}}return"comment"}else{if(g.inCharacterClass){while(g.inCharacterClass&&!j.eol()){if(!(j.match(/^[^\]\\]+/)||j.match(/^\\./))){g.inCharacterClass=false}}}else{if(j.peek()==="["){j.next();g.inCharacterClass=true;return"bracket"}else{if(j.match(/^\/\//)){j.skipToEnd();return"comment"}else{if(g.braced||j.peek()==="{"){if(g.localState===null){g.localState=d.startState()}var f=d.token(j,g.localState);var h=j.current();if(!f){for(var e=0;e<h.length;e++){if(h[e]==="{"){g.braced++}else{if(h[e]==="}"){g.braced--}}}}return f}else{if(b(j)){if(j.peek()===":"){return"variable"}return"variable-2"}else{if(["[","]","(",")"].indexOf(j.peek())!=-1){j.next();return"bracket"}else{if(!j.eatSpace()){j.next()}}}}}}}}}return null}}},"javascript")});