/* Address
 *
 * Models information associated with each address.
 */

 var Address = function(place, number, street, city, zip, state) {
 	this.place = place;
 	this.number = number;
 	this.street = street;
 	this.city = city;
	this.zip = zip;
	this.state = state;
 }

Address.all = {};
Address.all['UNC'] = new Address('UNC', 45, 'Raleigh Street', 'Chapel Hill', '27514', 'NC');
Address.all['CHA'] = new Address('Bank of America Center', 15, 'John F. Kenendy Avenue', 'Charlotte', '51632', 'NC');