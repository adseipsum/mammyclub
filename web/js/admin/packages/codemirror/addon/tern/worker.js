var server;this.onmessage=function(b){var a=b.data;switch(a.type){case"init":return startServer(a.defs,a.plugins,a.scripts);case"add":return server.addFile(a.name,a.text);case"del":return server.delFile(a.name);case"req":return server.request(a.body,function(e,c){postMessage({id:a.id,body:c,err:e&&String(e)})});case"getFile":var d=pending[a.id];delete pending[a.id];return d(a.err,a.text);default:throw new Error("Unknown message type: "+a.type)}};var nextId=0,pending={};function getFile(a,b){postMessage({type:"getFile",name:a,id:++nextId});pending[nextId]=b}function startServer(b,c,a){if(a){importScripts.apply(null,a)}server=new tern.Server({getFile:getFile,async:true,defs:b,plugins:c})}this.console={log:function(a){postMessage({type:"debug",message:a})}};