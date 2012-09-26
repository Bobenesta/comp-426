/* User
 *
 * Models information associated with each user.
 */

 var User = function(username, password, email, first, last, dob, sex, reviews) {
 	this.username = username;
 	this.password = password;
 	this.email = email;
 	this.first = first;
	this.last = last;
	this.dob = dob;
	this.sex = sex;
	this.reviews = reviews; // Concerning the all customer reviews (X out of 5 stars)
 }

User.all = {};
User.all['PET'] = new User('Pet', 'pet$bL', 'peter.boyle@gmail.com', 'Peter', 'Boyle', '12-26-1972', 'M', 4.5);
User.all['JACK'] = new User('Jack', '051986jr', 'jackrichard@hotmail.com', 'Jackson', 'Richardson', '05-19-1986', 'M' 4.3);
User.all['SHA'] = new User('Sha B', 'shashabb', 'shantelle.b@live.com', 'Shantelle', 'Brown', '09-23-1982', 'F' 4.8);