/* Car
 *
 * Models information associated with each car.
 */

 var Car = function(model, make, year) {
 	this.model = model;
 	this.make = make;
 	this.year = year;
 }
 
 Car.prototype.name = function () {
    return "" + this.year + " " + this.make + " " + this.model;
}

Car.all = {};
Car.all['FLE'] = new Car('Flex', 'Ford', 2009);
Car.all['CIV'] = new Car('Civic', 'Honda', 2004);
Car.all['YAR'] = new Car('Yarris', 'Toyota', 2000);