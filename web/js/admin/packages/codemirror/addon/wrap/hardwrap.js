(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(a){var d=a.Pos;function c(l,j,n){var i=n.paragraphStart||l.getHelper(j,"paragraphStart");for(var f=j.line,h=l.firstLine();f>h;--f){var o=l.getLine(f);if(i&&i.test(o)){break}if(!/\S/.test(o)){++f;break}}var k=n.paragraphEnd||l.getHelper(j,"paragraphEnd");for(var g=j.line+1,m=l.lastLine();g<=m;++g){var o=l.getLine(g);if(k&&k.test(o)){++g;break}if(!/\S/.test(o)){break}}return{from:f,to:g}}function e(l,h,i,g){for(var f=h;f>0;--f){if(i.test(l.slice(f-1,f+1))){break}}for(var k=true;;k=false){var j=f;if(g){while(l.charAt(j-1)==" "){--j}}if(j==0&&k){f=h}else{return{from:j,to:f}}}}function b(n,r,h,k){r=n.clipPos(r);h=n.clipPos(h);var j=k.column||80;var w=k.wrapOn||/\s\S|-[^\.\d]/;var v=k.killTrailingSpace!==false;var y=[],p="",s=r.line;var f=n.getRange(r,h,false);if(!f.length){return null}var m=f[0].match(/^[ \t]*/)[0];for(var t=0;t<f.length;++t){var o=f[t],l=p.length,g=0;if(p&&o&&!w.test(p.charAt(p.length-1)+o.charAt(0))){p+=" ";g=1}var q="";if(t){q=o.match(/^\s*/)[0];o=o.slice(q.length)}p+=o;if(t){var u=p.length>j&&m==q&&e(p,j,w,v);if(!u||u.from!=l||u.to!=l+g){y.push({text:[g?" ":""],from:d(s,l),to:d(s+1,q.length)})}else{p=m+o;++s}}while(p.length>j){var x=e(p,j,w,v);y.push({text:["",m],from:d(s,x.from),to:d(s,x.to)});p=m+p.slice(x.to);++s}}if(y.length){n.operation(function(){for(var z=0;z<y.length;++z){var A=y[z];if(A.text||a.cmpPos(A.from,A.to)){n.replaceRange(A.text,A.from,A.to)}}})}return y.length?{from:y[0].from,to:a.changeEnd(y[y.length-1])}:null}a.defineExtension("wrapParagraph",function(h,g){g=g||{};if(!h){h=this.getCursor()}var f=c(this,h,g);return b(this,d(f.from,0),d(f.to-1),g)});a.commands.wrapLines=function(f){f.operation(function(){var j=f.listSelections(),h=f.lastLine()+1;for(var l=j.length-1;l>=0;l--){var k=j[l],m;if(k.empty()){var g=c(f,k.head,{});m={from:d(g.from,0),to:d(g.to-1)}}else{m={from:k.from(),to:k.to()}}if(m.to.line>=h){continue}h=m.from.line;b(f,m.from,m.to,{})}})};a.defineExtension("wrapRange",function(h,g,f){return b(this,h,g,f||{})});a.defineExtension("wrapParagraphsInRange",function(m,l,j){j=j||{};var g=this,i=[];for(var h=m.line;h<=l.line;){var f=c(g,d(h,0),j);i.push(f);h=f.to}var k=false;if(i.length){g.operation(function(){for(var n=i.length-1;n>=0;--n){k=k||b(g,d(i[n].from,0),d(i[n].to-1),j)}})}return k})});