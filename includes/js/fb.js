window.fbAsyncInit = function() {
  FB.init({
    appId : '160063330776916', // App ID
    status: true, // check login status
    cookie: true, // enable cookies to allow the server to access the session
    xfbml: true,  // parse XFBML
    oath: true
  });
  FB.Event.subscribe('auth.login', function(response) {
    window.location = 'login.htm?FB=true';
  });
  FB.Event.subscribe('auth.logout', function(response) {
    window.location = 'logout.htm';
  });
};
// Load the SDK Asynchronously
(function(d){
  var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement('script'); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js";
  ref.parentNode.insertBefore(js, ref);
}(document));
