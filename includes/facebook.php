<!--<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml">  --> 
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '160063330776916', // App ID
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        oath       : true,
        xfbml      : true  // parse XFBML
      });
      FB.Event.subscribe('auth.login', function(response) {
        FB.api('/me', function(response) {
          var id = response.id;
          //alert("Welcome " + response.name + "! We know allll about you now");
          console.log(id);
          FB.logout(function() {
            console.log('you are logged out');
          });
        });
      });
    };
    // Load the SDK Asynchronously
    (function(d){
      var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
      js = d.createElement('script'); js.id = id; js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      d.getElementsByTagName('head')[0].appendChild(js);
    }(document));
    var onFacebookLogin = function() {
      console.log("you are loged int");
    }
  </script>
