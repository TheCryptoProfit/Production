/* Do not modify this file directly. It is compiled from other files. */
/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
!function(){var e,a,l=document.getElementById("access");if(l&&(e=l.getElementsByTagName("h3")[0],a=l.getElementsByTagName("ul")[0],e))a&&a.childNodes.length?e.onclick=function(){-1===a.className.indexOf("nav-menu")&&(a.className="nav-menu"),-1!==e.className.indexOf("toggled-on")?(e.className=e.className.replace(" toggled-on",""),a.className=a.className.replace(" toggled-on","")):(e.className+=" toggled-on",a.className+=" toggled-on")}:e.style.display="none"}();