/* login(string user, string password)
 *
 * Login with the given user/password and refresh the page
 */
function login(user, password) {
	var getSeedFailureHandler = function(jqXHR, textStatus, errorThrown) {
		alert("Error connecting to backend, please try again later");
	};

	var loginFailureHandler = function(jqXHR, textStatus, errorThrown) {
		alert("Error logging in: \n" + jqXHR.responseText);
	};

	$.ajax("api/login.php",
			{
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
						var passwordHash = SHA256(SHA256(password + "gEp3XuY9r7ajWxSIG7mW04PHlL9JxqXhhVs") + jqXHR.responseText);
						$.ajax("api/login.php",
							{
								type: 'POST',
								data: {
									userName: user,
									passwordHash: passwordHash
								},
								success: function(data, textStatus, jqXHR) {
										$(location).attr('href', "/homepage.php");
									},
								error: loginFailureHandler,
								cache: false
							});
					},
				error: getSeedFailureHandler,
				cache: false
			});

}

/* logout()
 *
 * Logout and refresh the page
 */
function logout() {
		$.ajax("api/logout.php",
			{
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
						$(location).attr('href', "/homepage.php");
					},
				error: function(data, textStatus, jqXHR) {
						alert("Error connecting to backend, please try again later");
					},
				cache: false
			});
}
