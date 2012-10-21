

var Request = function(userId, from, to, when, requestId) {
	this.userId = userId;
	this.from = from;
	this.to = to;
	this.when = when;
	this.requestId= requestId;
}

Request.getRequestById = function(requestId) {
	return Request.all[requestId];
}

Request.all={};
Request.all[1]= new Request(1000, "UNC", "Duke", "2012/10/21", 1);
Request.all[2]= new Request(2000, "NCSU", "Duke", "2012/10/21", 2);
Request.all[3]= new Request(3000, "UNC", "NCSU", "2012/10/21", 3);
Request.all[4]= new Request(4000, "UNC", "UVA", "2012/10/21", 4);

Request.display= function(requestId){
$("#rideinfo-to").html(Request.getRequestById(requestId).from);
$("#rideinfo-from").html(Request.getRequestById(requestId).to);
$("#rideinfo-name").html(Request.getRequestById(requestId).when);
}
