var ImageDialog={preInit:function(){var a;tinyMCEPopup.requireLangPack();if(a=tinyMCEPopup.getParam("external_image_list_url")){document.write('<script language="javascript" type="text/javascript" src="'+tinyMCEPopup.editor.documentBaseURI.toAbsolute(a)+'"><\/script>')}},init:function(b){var d=document.forms[0],a=d.elements,b=tinyMCEPopup.editor,e=b.dom,g=b.selection.getNode(),c=tinyMCEPopup.getParam("external_image_list","tinyMCEImageList");tinyMCEPopup.resizeToInnerSize();this.fillClassList("class_list");this.fillFileList("src_list",c);this.fillFileList("over_list",c);this.fillFileList("out_list",c);TinyMCE_EditableSelects.init();if(g.nodeName=="IMG"){a.src.value=e.getAttrib(g,"src");a.width.value=e.getAttrib(g,"width");a.height.value=e.getAttrib(g,"height");a.alt.value=e.getAttrib(g,"alt");a.title.value=e.getAttrib(g,"title");a.vspace.value=this.getAttrib(g,"vspace");a.hspace.value=this.getAttrib(g,"hspace");a.border.value=this.getAttrib(g,"border");selectByValue(d,"align",this.getAttrib(g,"align"));selectByValue(d,"class_list",e.getAttrib(g,"class"),true,true);a.style.value=e.getAttrib(g,"style");a.id.value=e.getAttrib(g,"id");a.dir.value=e.getAttrib(g,"dir");a.lang.value=e.getAttrib(g,"lang");a.usemap.value=e.getAttrib(g,"usemap");a.longdesc.value=e.getAttrib(g,"longdesc");a.insert.value=b.getLang("update");if(/^\s*this.src\s*=\s*\'([^\']+)\';?\s*$/.test(e.getAttrib(g,"onmouseover"))){a.onmouseoversrc.value=e.getAttrib(g,"onmouseover").replace(/^\s*this.src\s*=\s*\'([^\']+)\';?\s*$/,"$1")}if(/^\s*this.src\s*=\s*\'([^\']+)\';?\s*$/.test(e.getAttrib(g,"onmouseout"))){a.onmouseoutsrc.value=e.getAttrib(g,"onmouseout").replace(/^\s*this.src\s*=\s*\'([^\']+)\';?\s*$/,"$1")}if(b.settings.inline_styles){if(e.getAttrib(g,"align")){this.updateStyle("align")}if(e.getAttrib(g,"hspace")){this.updateStyle("hspace")}if(e.getAttrib(g,"border")){this.updateStyle("border")}if(e.getAttrib(g,"vspace")){this.updateStyle("vspace")}}a.customtitle.value=$(e.getNext(g,".customtitle")).text()}document.getElementById("srcbrowsercontainer").innerHTML=getBrowserHTML("srcbrowser","src","image","theme_advanced_image");if(isVisible("srcbrowser")){document.getElementById("src").style.width="260px"}document.getElementById("onmouseoversrccontainer").innerHTML=getBrowserHTML("overbrowser","onmouseoversrc","image","theme_advanced_image");if(isVisible("overbrowser")){document.getElementById("onmouseoversrc").style.width="260px"}document.getElementById("onmouseoutsrccontainer").innerHTML=getBrowserHTML("outbrowser","onmouseoutsrc","image","theme_advanced_image");if(isVisible("outbrowser")){document.getElementById("onmouseoutsrc").style.width="260px"}if(b.getParam("advimage_constrain_proportions",true)){d.constrain.checked=true}if(a.onmouseoversrc.value||a.onmouseoutsrc.value){this.setSwapImage(true)}else{this.setSwapImage(false)}this.changeAppearance();this.showPreviewImage(a.src.value,1)},insert:function(c,e){var a=tinyMCEPopup.editor,b=this,d=document.forms[0];if(d.src.value===""){if(a.selection.getNode().nodeName=="IMG"){a.dom.remove(a.selection.getNode());a.execCommand("mceRepaint")}tinyMCEPopup.close();return}if(tinyMCEPopup.getParam("accessibility_warnings",1)){if(!d.alt.value){tinyMCEPopup.confirm(tinyMCEPopup.getLang("advimage_dlg.missing_alt"),function(f){if(f){b.insertAndClose()}});return}}b.insertAndClose()},insertAndClose:function(){var d=tinyMCEPopup.editor,h=document.forms[0],b=h.elements,c,e={},g;tinyMCEPopup.restoreSelection();if(tinymce.isWebKit){d.getWin().focus()}if(!d.settings.inline_styles){e={vspace:b.vspace.value,hspace:b.hspace.value,border:b.border.value,align:getSelectValue(h,"align")}}else{e={vspace:"",hspace:"",border:"",align:""}}tinymce.extend(e,{src:b.src.value.replace(/ /g,"%20"),width:b.width.value,height:b.height.value,alt:b.alt.value,title:b.title.value,"class":getSelectValue(h,"class_list"),style:b.style.value,id:b.id.value,dir:b.dir.value,lang:b.lang.value,usemap:b.usemap.value,longdesc:b.longdesc.value});e.onmouseover=e.onmouseout="";if(h.onmousemovecheck.checked){if(b.onmouseoversrc.value){e.onmouseover="this.src='"+b.onmouseoversrc.value+"';"}if(b.onmouseoutsrc.value){e.onmouseout="this.src='"+b.onmouseoutsrc.value+"';"}}g=d.selection.getNode();if(g&&g.nodeName=="IMG"){d.dom.setAttribs(g,e);$(g).next("br").remove();$(g).next("span.customtitle").remove();if(b.customtitle.value.length>0){var a=d.dom.getOuterHTML(g)+'<br /><span class="customtitle">'+b.customtitle.value+"</span>";d.dom.setOuterHTML(g,a)}}else{if(b.customtitle.value.length>0){d.execCommand("mceInsertContent",false,'<img id="__mce_tmp" /><br /><span class="customtitle">'+b.customtitle.value+"</span><p></p>",{skip_undo:1})}else{d.execCommand("mceInsertContent",false,'<img id="__mce_tmp" />',{skip_undo:1})}d.dom.setAttribs("__mce_tmp",e);d.dom.setAttrib("__mce_tmp","id","");d.undoManager.add()}tinyMCEPopup.editor.execCommand("mceRepaint");tinyMCEPopup.editor.focus();tinyMCEPopup.close()},getAttrib:function(d,a){var c=tinyMCEPopup.editor,g=c.dom,b,f;if(c.settings.inline_styles){switch(a){case"align":if(b=g.getStyle(d,"float")){return b}if(b=g.getStyle(d,"vertical-align")){return b}break;case"hspace":b=g.getStyle(d,"margin-left");f=g.getStyle(d,"margin-right");if(b&&b==f){return parseInt(b.replace(/[^0-9]/g,""))}break;case"vspace":b=g.getStyle(d,"margin-top");f=g.getStyle(d,"margin-bottom");if(b&&b==f){return parseInt(b.replace(/[^0-9]/g,""))}break;case"border":b=0;tinymce.each(["top","right","bottom","left"],function(e){e=g.getStyle(d,"border-"+e+"-width");if(!e||(e!=b&&b!==0)){b=0;return false}if(e){b=e}});if(b){return parseInt(b.replace(/[^0-9]/g,""))}break}}if(b=g.getAttrib(d,a)){return b}return""},setSwapImage:function(a){var b=document.forms[0];b.onmousemovecheck.checked=a;setBrowserDisabled("overbrowser",!a);setBrowserDisabled("outbrowser",!a);if(b.over_list){b.over_list.disabled=!a}if(b.out_list){b.out_list.disabled=!a}b.onmouseoversrc.disabled=!a;b.onmouseoutsrc.disabled=!a},fillClassList:function(e){var d=tinyMCEPopup.dom,a=d.get(e),c,b;if(c=tinyMCEPopup.getParam("theme_advanced_styles")){b=[];tinymce.each(c.split(";"),function(f){var g=f.split("=");b.push({title:g[0],"class":g[1]})})}else{b=tinyMCEPopup.editor.dom.getClasses()}if(b.length>0){a.options.length=0;a.options[a.options.length]=new Option(tinyMCEPopup.getLang("not_set"),"");tinymce.each(b,function(f){a.options[a.options.length]=new Option(f.title||f["class"],f["class"])})}else{d.remove(d.getParent(e,"tr"))}},fillFileList:function(f,c){var e=tinyMCEPopup.dom,a=e.get(f),d,b;c=typeof(c)==="function"?c():window[c];a.options.length=0;if(c&&c.length>0){a.options[a.options.length]=new Option("","");tinymce.each(c,function(g){a.options[a.options.length]=new Option(g[0],g[1])})}else{e.remove(e.getParent(f,"tr"))}},resetImageData:function(){var a=document.forms[0];a.elements.width.value=a.elements.height.value=""},updateImageData:function(a,b){var c=document.forms[0];if(!b){c.elements.width.value=a.width;c.elements.height.value=a.height}this.preloadImg=a},changeAppearance:function(){var b=tinyMCEPopup.editor,c=document.forms[0],a=document.getElementById("alignSampleImg");if(a){if(b.getParam("inline_styles")){b.dom.setAttrib(a,"style",c.style.value)}else{a.align=c.align.value;a.border=c.border.value;a.hspace=c.hspace.value;a.vspace=c.vspace.value}}},changeHeight:function(){var b=document.forms[0],c,a=this;if(!b.constrain.checked||!a.preloadImg){return}if(b.width.value==""||b.height.value==""){return}c=(parseInt(b.width.value)/parseInt(a.preloadImg.width))*a.preloadImg.height;b.height.value=c.toFixed(0)},changeWidth:function(){var b=document.forms[0],c,a=this;if(!b.constrain.checked||!a.preloadImg){return}if(b.width.value==""||b.height.value==""){return}c=(parseInt(b.height.value)/parseInt(a.preloadImg.height))*a.preloadImg.width;b.width.value=c.toFixed(0)},updateStyle:function(e){var d=tinyMCEPopup.dom,i,k,c,j,a=tinymce.isIE,h=document.forms[0],g=d.create("img",{style:d.get("style").value});if(tinyMCEPopup.editor.settings.inline_styles){if(e=="align"){d.setStyle(g,"float","");d.setStyle(g,"vertical-align","");j=getSelectValue(h,"align");if(j){if(j=="left"||j=="right"){d.setStyle(g,"float",j)}else{g.style.verticalAlign=j}}}if(e=="border"){i=g.style.border?g.style.border.split(" "):[];k=d.getStyle(g,"border-style");c=d.getStyle(g,"border-color");d.setStyle(g,"border","");j=h.border.value;if(j||j=="0"){if(j=="0"){g.style.border=a?"0":"0 none none"}else{if(i.length==3&&i[a?2:1]){k=i[a?2:1]}else{if(!k||k=="none"){k="solid"}}if(i.length==3&&i[a?0:2]){c=i[a?0:2]}else{if(!c||c=="none"){c="black"}}g.style.border=j+"px "+k+" "+c}}}if(e=="hspace"){d.setStyle(g,"marginLeft","");d.setStyle(g,"marginRight","");j=h.hspace.value;if(j){g.style.marginLeft=j+"px";g.style.marginRight=j+"px"}}if(e=="vspace"){d.setStyle(g,"marginTop","");d.setStyle(g,"marginBottom","");j=h.vspace.value;if(j){g.style.marginTop=j+"px";g.style.marginBottom=j+"px"}}d.get("style").value=d.serializeStyle(d.parseStyle(g.style.cssText),"img")}},changeMouseMove:function(){},showPreviewImage:function(b,a){if(!b){tinyMCEPopup.dom.setHTML("prev","");return}if(!a&&tinyMCEPopup.getParam("advimage_update_dimensions_onchange",true)){this.resetImageData()}b=tinyMCEPopup.editor.documentBaseURI.toAbsolute(b);if(!a){tinyMCEPopup.dom.setHTML("prev",'<img id="previewImg" src="'+b+'" border="0" onload="ImageDialog.updateImageData(this);" onerror="ImageDialog.resetImageData();" />')}else{tinyMCEPopup.dom.setHTML("prev",'<img id="previewImg" src="'+b+'" border="0" onload="ImageDialog.updateImageData(this, 1);" />')}}};ImageDialog.preInit();tinyMCEPopup.onInit.add(ImageDialog.init,ImageDialog);