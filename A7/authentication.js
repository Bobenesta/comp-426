/* login(string user, string password)
 *
 * Login with the given user/password and refresh the page
 */
function login(user, password) {
	var failureHandler = function(jqXHR, textStatus, errorThrown) {
		alert("Error connecting to backend, please try again later");
	};

	$.ajax("api/login.php",
			{
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
						var passwordHash = binb2hex(SHA256(SHA256(password + "gEp3XuY9r7ajWxSIG7mW04PHlL9JxqXhhVs") + jqXHR.responseText));
						$.ajax("api/login.php",
							{
								type: 'POST',
								data: {
									userName: user,
									passwordHash: passwordHash
								},
								success: function(data, textStatus, jqXHR) {
										alert(jqXHR.responseText);//TODO
									},
								error: failureHandler,
								cache: false
							}
					},
				error: failureHandler,
				cache: false
			}

}

/* logout()
 *
 * Logout and refresh the page
 */
function logout() {
	
}
