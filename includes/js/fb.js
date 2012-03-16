window.fbAsyncInit = function() {
  FB.init({
    appId : '160063330776916', // App ID
    status: true, // check login status
    cookie: true, // enable cookies to allow the server to access the session
    xfbml: true,  // parse XFBML
    oath: true
  });
  FB.Event.subscribe('auth.login', function(response) {
    window.location.reload();
  });
  FB.Event.subscribe('auth.logout', function(response) {
    window.location.reload();
  });
 FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    // the user is logged in and has authenticated your
    // app, and response.authResponse supplies
    // the user's ID, a valid access token, a signed
    // request, and the time the access token 
    // and signed request each expire
    var uid = response.authResponse.userID;
    var accessToken = response.authResponse.accessToken;
    alert(uid)
  } else if (response.status === 'not_authorized') {
    // the user is logged in to Facebook, 
    // but has not authenticated your app
  } else {
    // the user isn't logged in to Facebook.
  }
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
