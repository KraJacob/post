window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
        app_id      : "{{env('FACEBOOK_CLIENT_ID')}}", // FB App ID
        cookie     : true,  // enable cookies to allow the server to access the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v5.6' // use graph api version 5.6
    });


};

// Load the JavaScript SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Facebook login with JavaScript SDK
function fbLogin()
{
    FB.login(function (response) {
        if (response.authResponse)
        {
            // Get  the user profile data
            FB.api('/me', {locale: 'en_US', fields:
                        'id,first_name,last_name,email,link,gender,locale,picture'},
                function (userData)
                {
                    $.post('process.php',
                        {oauth_provider:'facebook',userData: JSON.stringify(userData),action:'1'},
                        function(data)
                        {

                            if(data=='1')
                            {
                                console.log("login")
                                //window.location= "dashboard.php";
                            }else
                            {
                                alert('something goes wrong');
                            }
                        });
                });
        }else
        {
            //do something
        }
    }, {scope: 'email,publish_actions,manage_pages,publish_pages'});
}