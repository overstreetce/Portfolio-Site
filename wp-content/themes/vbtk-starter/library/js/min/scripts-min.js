jQuery(document).ready(function($){jQuery(document).foundation(),$(".comment img[data-gravatar]").each(function(){$(this).attr("src",$(this).attr("data-gravatar"))}),$("#lightbox-close").click(function(){$(".lightbox-content").hide(),$(".lightbox-target").hide()})}),function(t){function e(){o.setAttribute("content",u),d=!0}function n(){o.setAttribute("content",c),d=!1}function a(a){g=a.accelerationIncludingGravity,m=Math.abs(g.x),s=Math.abs(g.y),h=Math.abs(g.z),!t.orientation&&(m>7||(h>6&&8>s||8>h&&s>6)&&m>5)?d&&n():d||e()}if(/iPhone|iPad|iPod/.test(navigator.platform)&&navigator.userAgent.indexOf("AppleWebKit")>-1){var i=t.document;if(i.querySelector){var o=i.querySelector("meta[name=viewport]"),r=o&&o.getAttribute("content"),c=r+",maximum-scale=1",u=r+",maximum-scale=10",d=!0,m,s,h,g;o&&(t.addEventListener("orientationchange",e,!1),t.addEventListener("devicemotion",a,!1))}}}(this);