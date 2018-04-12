(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(d){d.defineSimpleMode=function(l,k){d.defineMode(l,function(m){return d.simpleMode(m,k)})};d.simpleMode=function(m,v){c(v,"start");var n={},u=v.meta||{},t=false;for(var l in v){if(l!=u&&v.hasOwnProperty(l)){var r=n[l]=[],s=v[l];for(var p=0;p<s.length;p++){var o=s[p];r.push(new b(o,v));if(o.indent||o.dedent){t=true}}}}var q={startState:function(){return{state:"start",pending:null,local:null,localState:null,indent:t?[]:null}},copyState:function(x){var w={state:x.state,pending:x.pending,local:x.local,localState:null,indent:x.indent&&x.indent.slice(0)};if(x.localState){w.localState=d.copyState(x.local.mode,x.localState)}if(x.stack){w.stack=x.stack.slice(0)}for(var y=x.persistentStates;y;y=y.next){w.persistentStates={mode:y.mode,spec:y.spec,state:y.state==x.localState?w.localState:d.copyState(y.mode,y.state),next:w.persistentStates}}return w},token:j(n,m),innerMode:function(w){return w.local&&{mode:w.local.mode,state:w.localState}},indent:a(n,u)};if(u){for(var k in u){if(u.hasOwnProperty(k)){q[k]=u[k]}}}return q};function c(k,l){if(!k.hasOwnProperty(l)){throw new Error("Undefined state "+l+" in simple mode")}}function f(m,l){if(!m){return/(?:)/}var k="";if(m instanceof RegExp){if(m.ignoreCase){k="i"}m=m.source}else{m=String(m)}return new RegExp((l===false?"":"^")+"(?:"+m+")",k)}function i(m){if(!m){return null}if(typeof m=="string"){return m.replace(/\./g," ")}var k=[];for(var l=0;l<m.length;l++){k.push(m[l]&&m[l].replace(/\./g," "))}return k}function b(l,k){if(l.next||l.push){c(k,l.next||l.push)}this.regex=f(l.regex);this.token=i(l.token);this.data=l}function j(k,l){return function(v,n){if(n.pending){var o=n.pending.shift();if(n.pending.length==0){n.pending=null}v.pos+=o.text.length;return o.token}if(n.local){if(n.local.end&&v.match(n.local.end)){var w=n.local.endToken||null;n.local=n.localState=null;return w}else{var w=n.local.mode.token(v,n.localState),p;if(n.local.endScan&&(p=n.local.endScan.exec(v.current()))){v.pos=v.start+p.index}return w}}var t=k[n.state];for(var r=0;r<t.length;r++){var u=t[r];var s=(!u.data.sol||v.sol())&&v.match(u.regex);if(s){if(u.data.next){n.state=u.data.next}else{if(u.data.push){(n.stack||(n.stack=[])).push(n.state);n.state=u.data.push}else{if(u.data.pop&&n.stack&&n.stack.length){n.state=n.stack.pop()}}}if(u.data.mode){e(l,n,u.data.mode,u.token)}if(u.data.indent){n.indent.push(v.indentation()+l.indentUnit)}if(u.data.dedent){n.indent.pop()}if(s.length>2){n.pending=[];for(var q=2;q<s.length;q++){if(s[q]){n.pending.push({text:s[q],token:u.token[q-1]})}}v.backUp(s[0].length-(s[1]?s[1].length:0));return u.token[0]}else{if(u.token&&u.token.join){return u.token[0]}else{return u.token}}}}v.next();return null}}function h(l,k){if(l===k){return true}if(!l||typeof l!="object"||!k||typeof k!="object"){return false}var m=0;for(var n in l){if(l.hasOwnProperty(n)){if(!k.hasOwnProperty(n)||!h(l[n],k[n])){return false}m++}}for(var n in k){if(k.hasOwnProperty(n)){m--}}return m==0}function e(l,o,k,m){var s;if(k.persistent){for(var q=o.persistentStates;q&&!s;q=q.next){if(k.spec?h(k.spec,q.spec):k.mode==q.mode){s=q}}}var r=s?s.mode:k.mode||d.getMode(l,k.spec);var n=s?s.state:d.startState(r);if(k.persistent&&!s){o.persistentStates={mode:r,spec:k.spec,state:n,next:o.persistentStates}}o.localState=n;o.local={mode:r,end:k.end&&f(k.end),endScan:k.end&&k.forceEnd!==false&&f(k.end,false),endToken:m&&m.join?m[m.length-1]:m}}function g(m,k){for(var l=0;l<k.length;l++){if(k[l]===m){return true}}}function a(k,l){return function(r,p,o){if(r.local&&r.local.mode.indent){return r.local.mode.indent(r.localState,p,o)}if(r.indent==null||r.local||l.dontIndentStates&&g(r.state,l.dontIndentStates)>-1){return d.Pass}var u=r.indent.length-1,t=k[r.state];scan:for(;;){for(var q=0;q<t.length;q++){var s=t[q];if(s.data.dedent&&s.data.dedentIfLineStart!==false){var n=s.regex.exec(p);if(n&&n[0]){u--;if(s.next||s.push){t=k[s.next||s.push]}p=p.slice(n[0].length);continue scan}}}break}return u<0?0:r.indent[u]}}});