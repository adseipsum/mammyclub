(function(a){if(typeof exports=="object"&&typeof module=="object"){a(require("../../lib/codemirror"))}else{if(typeof define=="function"&&define.amd){define(["../../lib/codemirror"],a)}else{a(CodeMirror)}}})(function(a){var b={};var f=/[^\s\u00a0]/;var d=a.Pos;function e(h){var g=h.search(f);return g==-1?0:g}a.commands.toggleComment=function(g){g.toggleComment()};a.defineExtension("toggleComment",function(j){if(!j){j=b}var g=this;var l=Infinity,h=this.listSelections(),m=null;for(var k=h.length-1;k>=0;k--){var o=h[k].from(),n=h[k].to();if(o.line>=l){continue}if(n.line>=l){n=d(l,0)}l=o.line;if(m==null){if(g.uncomment(o,n,j)){m="un"}else{g.lineComment(o,n,j);m="line"}}else{if(m=="un"){g.uncomment(o,n,j)}else{g.lineComment(o,n,j)}}}});function c(g,i,h){return/\bstring\b/.test(g.getTokenTypeAt(d(i.line,0)))&&!/^[\'\"`]/.test(h)}a.defineExtension("lineComment",function(l,m,p){if(!p){p=b}var n=this,i=n.getModeAt(l);var k=n.getLine(l.line);if(k==null||c(n,l,k)){return}var o=p.lineComment||i.lineComment;if(!o){if(p.blockCommentStart||i.blockCommentStart){p.fullLines=true;n.blockComment(l,m,p)}return}var h=Math.min(m.ch!=0||m.line==l.line?m.line+1:m.line,n.lastLine()+1);var g=p.padding==null?" ":p.padding;var j=p.commentBlankLines||l.line==m.line;n.operation(function(){if(p.indent){var t=null;for(var s=l.line;s<h;++s){var q=n.getLine(s);var r=q.slice(0,e(q));if(t==null||t.length>r.length){t=r}}for(var s=l.line;s<h;++s){var q=n.getLine(s),u=t.length;if(!j&&!f.test(q)){continue}if(q.slice(0,u)!=t){u=e(q)}n.replaceRange(t+o+g,d(s,0),d(s,u))}}else{for(var s=l.line;s<h;++s){if(j||f.test(n.getLine(s))){n.replaceRange(o+g,d(s,0))}}}})});a.defineExtension("blockComment",function(k,l,o){if(!o){o=b}var n=this,i=n.getModeAt(k);var j=o.blockCommentStart||i.blockCommentStart;var m=o.blockCommentEnd||i.blockCommentEnd;if(!j||!m){if((o.lineComment||i.lineComment)&&o.fullLines!=false){n.lineComment(k,l,o)}return}var h=Math.min(l.line,n.lastLine());if(h!=k.line&&l.ch==0&&f.test(n.getLine(h))){--h}var g=o.padding==null?" ":o.padding;if(k.line>h){return}n.operation(function(){if(o.fullLines!=false){var r=f.test(n.getLine(h));n.replaceRange(g+m,d(h));n.replaceRange(j+g,d(k.line,0));var p=o.blockCommentLead||i.blockCommentLead;if(p!=null){for(var q=k.line+1;q<=h;++q){if(q!=h||r){n.replaceRange(p+g,d(q,0))}}}}else{n.replaceRange(m,l);n.replaceRange(j,k)}})});a.defineExtension("uncomment",function(A,k,l){if(!l){l=b}var x=this,u=x.getModeAt(A);var m=Math.min(k.ch!=0||k.line==A.line?k.line:k.line-1,x.lastLine()),n=Math.min(A.line,m);var w=l.lineComment||u.lineComment,j=[];var D=l.padding==null?" ":l.padding,p;lineComment:{if(!w){break lineComment}for(var B=n;B<=m;++B){var q=x.getLine(B);var r=q.indexOf(w);if(r>-1&&!/comment/.test(x.getTokenTypeAt(d(B,r+1)))){r=-1}if(r==-1&&(B!=m||B==n)&&f.test(q)){break lineComment}if(r>-1&&f.test(q.slice(0,r))){break lineComment}j.push(q)}x.operation(function(){for(var H=n;H<=m;++H){var F=j[H-n];var I=F.indexOf(w),G=I+w.length;if(I<0){continue}if(F.slice(G,G+D.length)==D){G+=D.length}p=true;x.replaceRange("",d(H,I),d(H,G))}});if(p){return true}}var s=l.blockCommentStart||u.blockCommentStart;var g=l.blockCommentEnd||u.blockCommentEnd;if(!s||!g){return false}var C=l.blockCommentLead||u.blockCommentLead;var z=x.getLine(n),o=m==n?z:x.getLine(m);var t=z.indexOf(s),v=o.lastIndexOf(g);if(v==-1&&n!=m){o=x.getLine(--m);v=o.lastIndexOf(g)}if(t==-1||v==-1||!/comment/.test(x.getTokenTypeAt(d(n,t+1)))||!/comment/.test(x.getTokenTypeAt(d(m,v+1)))){return false}var y=z.lastIndexOf(s,A.ch);var E=y==-1?-1:z.slice(0,A.ch).indexOf(g,y+s.length);if(y!=-1&&E!=-1&&E+g.length!=A.ch){return false}E=o.indexOf(g,k.ch);var h=o.slice(k.ch).lastIndexOf(s,E-k.ch);y=(E==-1||h==-1)?-1:k.ch+h;if(E!=-1&&y!=-1&&y!=k.ch){return false}x.operation(function(){x.replaceRange("",d(m,v-(D&&o.slice(v-D.length,v)==D?D.length:0)),d(m,v+g.length));var I=t+s.length;if(D&&z.slice(I,I+D.length)==D){I+=D.length}x.replaceRange("",d(n,t),d(n,I));if(C){for(var H=n+1;H<=m;++H){var G=x.getLine(H),J=G.indexOf(C);if(J==-1||f.test(G.slice(0,J))){continue}var F=J+C.length;if(D&&G.slice(F,F+D.length)==D){F+=D.length}x.replaceRange("",d(H,J),d(H,F))}}});return true})});