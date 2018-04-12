(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(b){function a(h){return new RegExp("^(("+h.join(")|(")+"))\\b")}var d=a(["and","or","not","is"]);var c=["as","assert","break","class","continue","def","del","elif","else","except","finally","for","from","global","if","import","lambda","pass","raise","return","try","while","with","yield","in"];var g=["abs","all","any","bin","bool","bytearray","callable","chr","classmethod","compile","complex","delattr","dict","dir","divmod","enumerate","eval","filter","float","format","frozenset","getattr","globals","hasattr","hash","help","hex","id","input","int","isinstance","issubclass","iter","len","list","locals","map","max","memoryview","min","next","object","oct","open","ord","pow","property","range","repr","reversed","round","set","setattr","slice","sorted","staticmethod","str","sum","super","tuple","type","vars","zip","__import__","NotImplemented","Ellipsis","__debug__"];b.registerHelper("hintWords","python",c.concat(g));function e(h){return h.scopes[h.scopes.length-1]}b.defineMode("python",function(s,B){var j="error";var w=B.singleDelimiters||/^[\(\)\[\]\{\}@,:`=;\.]/;var h=B.doubleOperators||/^([!<>]==|<>|<<|>>|\/\/|\*\*)/;var q=B.doubleDelimiters||/^(\+=|\-=|\*=|%=|\/=|&=|\|=|\^=)/;var C=B.tripleDelimiters||/^(\/\/=|>>=|<<=|\*\*=)/;var k=B.hangingIndent||s.indentUnit;var A=c,x=g;if(B.extra_keywords!=undefined){A=A.concat(B.extra_keywords)}if(B.extra_builtins!=undefined){x=x.concat(B.extra_builtins)}var i=B.version&&parseInt(B.version,10)==3;if(i){var v=B.singleOperators||/^[\+\-\*\/%&|\^~<>!@]/;var p=B.identifiers||/^[_A-Za-z\u00A1-\uFFFF][_A-Za-z0-9\u00A1-\uFFFF]*/;A=A.concat(["nonlocal","False","True","None","async","await"]);x=x.concat(["ascii","bytes","exec","print"]);var t=new RegExp("^(([rbuf]|(br))?('{3}|\"{3}|['\"]))","i")}else{var v=B.singleOperators||/^[\+\-\*\/%&|\^~<>!]/;var p=B.identifiers||/^[_A-Za-z][_A-Za-z0-9]*/;A=A.concat(["exec","print"]);x=x.concat(["apply","basestring","buffer","cmp","coerce","execfile","file","intern","long","raw_input","reduce","reload","unichr","unicode","xrange","False","True","None"]);var t=new RegExp("^(([rub]|(ur)|(br))?('{3}|\"{3}|['\"]))","i")}var r=a(A);var m=a(x);function E(J,I){if(J.sol()){I.indent=J.indentation()}if(J.sol()&&e(I).type=="py"){var F=e(I).offset;if(J.eatSpace()){var G=J.indentation();if(G>F){l(I)}else{if(G<F&&n(J,I)){I.errorToken=true}}return null}else{var H=y(J,I);if(F>0&&n(J,I)){H+=" "+j}return H}}return y(J,I)}function y(J,I){if(J.eatSpace()){return null}var H=J.peek();if(H=="#"){J.skipToEnd();return"comment"}if(J.match(/^[0-9\.]/,false)){var G=false;if(J.match(/^\d*\.\d+(e[\+\-]?\d+)?/i)){G=true}if(J.match(/^\d+\.\d*/)){G=true}if(J.match(/^\.\d+/)){G=true}if(G){J.eat(/J/i);return"number"}var F=false;if(J.match(/^0x[0-9a-f]+/i)){F=true}if(J.match(/^0b[01]+/i)){F=true}if(J.match(/^0o[0-7]+/i)){F=true}if(J.match(/^[1-9]\d*(e[\+\-]?\d+)?/)){J.eat(/J/i);F=true}if(J.match(/^0(?![\dx])/i)){F=true}if(F){J.eat(/L/i);return"number"}}if(J.match(t)){I.tokenize=z(J.current());return I.tokenize(J,I)}if(J.match(C)||J.match(q)){return"punctuation"}if(J.match(h)||J.match(v)){return"operator"}if(J.match(w)){return"punctuation"}if(I.lastToken=="."&&J.match(p)){return"property"}if(J.match(r)||J.match(d)){return"keyword"}if(J.match(m)){return"builtin"}if(J.match(/^(self|cls)\b/)){return"variable-2"}if(J.match(p)){if(I.lastToken=="def"||I.lastToken=="class"){return"def"}return"variable"}J.next();return j}function z(F){while("rub".indexOf(F.charAt(0).toLowerCase())>=0){F=F.substr(1)}var H=F.length==1;var G="string";function I(K,J){while(!K.eol()){K.eatWhile(/[^'"\\]/);if(K.eat("\\")){K.next();if(H&&K.eol()){return G}}else{if(K.match(F)){J.tokenize=E;return G}else{K.eat(/['"]/)}}}if(H){if(B.singleLineStringErrors){return j}else{J.tokenize=E}}return G}I.isString=true;return I}function l(F){while(e(F).type!="py"){F.scopes.pop()}F.scopes.push({offset:e(F).offset+s.indentUnit,type:"py",align:null})}function u(H,G,F){var I=H.match(/^([\s\[\{\(]|#.*)*$/,false)?null:H.column()+1;G.scopes.push({offset:G.indent+k,type:F,align:I})}function n(G,F){var H=G.indentation();while(e(F).offset>H){if(e(F).type!="py"){return true}F.scopes.pop()}return e(F).offset!=H}function D(J,H){if(J.sol()){H.beginningOfLine=true}var G=H.tokenize(J,H);var I=J.current();if(H.beginningOfLine&&I=="@"){return J.match(p,false)?"meta":i?"operator":j}if(/\S/.test(I)){H.beginningOfLine=false}if((G=="variable"||G=="builtin")&&H.lastToken=="meta"){G="meta"}if(I=="pass"||I=="return"){H.dedent+=1}if(I=="lambda"){H.lambda=true}if(I==":"&&!H.lambda&&e(H).type=="py"){l(H)}var F=I.length==1?"[({".indexOf(I):-1;if(F!=-1){u(J,H,"])}".slice(F,F+1))}F="])}".indexOf(I);if(F!=-1){if(e(H).type==I){H.indent=H.scopes.pop().offset-k}else{return j}}if(H.dedent>0&&J.eol()&&e(H).type=="py"){if(H.scopes.length>1){H.scopes.pop()}H.dedent-=1}return G}var o={startState:function(F){return{tokenize:E,scopes:[{offset:F||0,type:"py",align:null}],indent:F||0,lastToken:null,lambda:false,dedent:0}},token:function(I,G){var H=G.errorToken;if(H){G.errorToken=false}var F=D(I,G);if(F&&F!="comment"){G.lastToken=(F=="keyword"||F=="punctuation")?I.current():F}if(F=="punctuation"){F=null}if(I.eol()&&G.lambda){G.lambda=false}return H?F+" "+j:F},indent:function(I,F){if(I.tokenize!=E){return I.tokenize.isString?b.Pass:0}var H=e(I),G=H.type==F.charAt(0);if(H.align!=null){return H.align-(G?1:0)}else{return H.offset-(G?k:0)}},electricInput:/^\s*[\}\]\)]$/,closeBrackets:{triples:"'\""},lineComment:"#",fold:"indent"};return o});b.defineMIME("text/x-python","python");var f=function(h){return h.split(" ")};b.defineMIME("text/x-cython",{name:"python",extra_keywords:f("by cdef cimport cpdef ctypedef enum exceptextern gil include nogil property publicreadonly struct union DEF IF ELIF ELSE")})});