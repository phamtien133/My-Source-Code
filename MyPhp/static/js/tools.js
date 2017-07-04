var result = null;
var requests = [];
function getParam(sParam)
{
    return oParams[sParam];
}
function formatParam(aParams)
{
    if(aParams instanceof Array)
    {
        var sParams = '';
        for(var sValue in aParams){
            if(sValue != '' && sValue != 'undefined'){
                sParams +=  '&' + sValue + '=' + encodeURIComponent(aParams[sValue]);
            }
        }
        return sParams;
    }
    return aParams;
}
function IsNumeric(input)
{
    return (input - 0) == input && (''+input).replace(/^\s+|\s+$/g, "").length > 0;
}
function trim(str, charlist) { 
    var whitespace, l = 0, i = 0;
    str += '';
    
    if (!charlist) {
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } else {
        charlist += '';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    }
    
    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }
    
    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }
    
    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}
function ltrim ( str, charlist ) { 
    charlist = !charlist ? ' \s\xA0' : (charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('^[' + charlist + ']+', 'g');
    return (str+'').replace(re, '');
}
function rtrim ( str, charlist ) { 
    charlist = !charlist ? ' \s\xA0' : (charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('[' + charlist + ']+$', 'g');
    return (str+'').replace(re, '');
}
function empty(mixed_var) {
    var key;
    
    if (mixed_var === ""
        || mixed_var === 0
        || mixed_var === "0"
        || mixed_var === null
        || mixed_var === false
        || mixed_var === undefined
        || trim(mixed_var) == ""
    ){
        return true;
    }
    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            if (typeof mixed_var[key] !== 'function' ) {
              return false;
            }
        }
        return true;
    }
    return false;
}
function substr(sString, iStart, iLength) 
{ 
    if(iStart < 0) 
    {
        iStart += sString.length;
    }
 
    if(iLength == undefined) 
    {
        iLength = sString.length;
    } 
    else if(iLength < 0)
    {
        iLength += sString.length;
    } 
    else 
    {
        iLength += iStart;
    }
 
    if(iLength < iStart) 
    {
        iLength = iStart;
    }
 
    return sString.substring(iStart, iLength);
}
function isset() 
{
    var a=arguments; var l=a.length; var i=0;
    
    if (l==0) { 
            throw new Error('Empty isset'); 
    }
    
    while (i!=l) {
        if (typeof(a[i])=='undefined' || a[i]===null) { 
            return false; 
        } else { 
            i++; 
        }
    }
    return true;
}