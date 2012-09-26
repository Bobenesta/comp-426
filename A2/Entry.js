var Entry= function(from, to, when, user){
	this.from= from;
	this.to= to;
	this.when= when;
	this.user= user;
};


var User= function(name, email, request){
	this.name=name;
	this.email=email;
};

var Entries=[new Entry("UNC","Duke","09/26/2012","ben"), new Entry("Charlotte", "UNC", "09/30/2012","matt"), 
new Entry("Charlotte", "Duke", "09/30/2012","jack"),new Entry("Duke","UNC","09/26/2012","ben"), new Entry("New Bern", "Raleigh", "09/30/2012","matt"), 
new Entry("Durham", "Elon", "09/30/2012","jack")];

var Users= [new User("jack","zhew@live.unc.edu"), new User("matt","matt@bluematt.me"), new User("ben","benoitbarge@gmail.com")] ;

