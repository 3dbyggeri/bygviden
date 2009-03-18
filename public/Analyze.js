   var qString="height="+escape(window.screen.height);
   qString=qString+"&width="+escape(window.screen.width);
   qString=qString+"&url="+escape(document.location.href);
   qString=qString+"&colors="+escape(window.screen.colorDepth);
   qString=qString+"&referrer="+escape(document.referrer);
   qString=qString+"&name="+escape(document.title);
   qString=qString+"&appname="+escape(navigator.appName);
   qString=qString+"&appversion="+escape(navigator.appVersion);
   qString=qString+"&useragent="+escape(navigator.userAgent);
   qString=qString+"&language="+escape(navigator.language ? navigator.language : navigator.userLanguage);

   if (navigator.javaEnabled()) {qString=qString+"&java=1"} else {qString=qString+"&java=0"}

   var pos = document.cookie.indexOf("test=cookiesEnabled");
   if (pos == -1) {
      var expiration = new Date();
      expiration.setTime(expiration.getTime() + (5*60*1000));
      document.cookie = "test=cookiesEnabled; path=/; expires="+expiration.toGMTString();
      pos = document.cookie.indexOf("test=cookiesEnabled");
      if (pos == -1) {qString=qString+"&cookies=0"} else {qString=qString+"&cookies=1"}
   } else {
      qString=qString+"&cookies=1"
   }

   var dt = new Date();
   var dtlong = dt.getTime();
   qString=qString+"&time="+dtlong.toString();

   /*
   var server = document.location.href;
   var i = server.indexOf("://");
   var i2 = server.indexOf("/",i+3);
   if (i > -1 & i2 > -1) {
      server = server.substring(0,i2);
   } else {
      server = "";
   }
   */
   var server = 'http://www.danskbyggeri.dk';
   var img = new Image();
   img.src = server+'/servlet/LetsAnalyze?'+qString;
