//是否存在指定函数 
function isExitsFunction(funcName) {
    try {
        if (typeof(eval(funcName)) == "function") {
            return true;
        }
    } catch(e) {}
    return false;
}
//是否存在指定变量 
function isExitsVariable(variableName) {
    try {
        if (typeof(variableName) == "undefined") {
            //alert("value is undefined"); 
            return false;
        } else {
            //alert("value is true"); 
            return true;
        }
    } catch(e) {}
    return false;
}

function dialog_close(d){
    if (!d){
        d = '#dialog';
    }
    var dialog = $(d);
    if(dialog.length > 0){
        $(dialog).dialog('close');
    }
}

function dialog_flush_data(d,text){
    if (!d){
        d = '#dialog';
    }
    var dialog = $(d);
    if (dialog.length == 0){
        location.reload();
    }else{
        var page = $(".pagination");
        if (page.length != 0){
            page.jqPaginator('option');
            dialog.dialog("close");
        }else{
            location.reload();
        }
    }
}

function dialog_child_close(url){
    if (window.parent != window && isExitsFunction("parent.dialog_close")){
        parent.dialog_close();
    }else if(url != ''){
        location.href = url;
    }else{
        history.go(-1);
    }
}

function dialog_child_flush_data(url){
    if (window.parent != window && isExitsFunction("parent.dialog_flush_data")){
        parent.dialog_flush_data();
    }else if(window.parent != window){
        parent.location.reload();
    }else{
        location.href = url;
    }
}

function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=")
  if (c_start!=-1)
    { 
    c_start=c_start + c_name.length+1 ;
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length
    return unescape(document.cookie.substring(c_start,c_end))
    }
  }
return "";
}

function setCookie(c_name,value,expiredays)
{
  var exdate=new Date();
  exdate.setDate(exdate.getDate()+expiredays);
  document.cookie=c_name+ "=" +escape(value)+
  ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

function checkCookie()
{
username=getCookie('username');
if (username!=null && username!="")
  {alert('Welcome again '+username+'!');}
else 
  {
  username=prompt('Please enter your name:',"");
  if (username!=null && username!="")
    {
    setCookie('username',username,365);
    }
  }
}

// 获取浏览器窗口的可视区域的宽度
function getViewPortWidth() {
    return document.documentElement.clientWidth || document.body.clientWidth;
}
 
// 获取浏览器窗口的可视区域的高度
function getViewPortHeight() {
    return document.documentElement.clientHeight || document.body.clientHeight;
}
 
// 获取浏览器窗口水平滚动条的位置
function getScrollLeft() {
    return document.documentElement.scrollLeft || document.body.scrollLeft;
}
 
// 获取浏览器窗口垂直滚动条的位置
function getScrollTop() {
    return document.documentElement.scrollTop || document.body.scrollTop;
}