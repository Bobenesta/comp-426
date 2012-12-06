

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
Request.all[2]= new Request(2000, "raleigh", "chapel hill", "2012/10/21", 2);
Request.all[3]= new Request(3000, "UNC", "NCSU", "2012/10/21", 3);
Request.all[4]= new Request(4000, "raleigh", "durham", "2012/10/21", 4);

Request.display= function(requestId){
	$.ajax("api/ride.php/" + requestId,
			{
				type: 'GET',
				success: function(data, textStatus, jqXHR) {
						$("#rideinfo-to").html(Address.prototype.getShortName.call(data.toAddress));
						$("#rideinfo-from").html(Address.prototype.getShortName.call(data.fromAddress));
						$("#rideinfo-car-capacity").html(data.carCapacity);
						$("#rideinfo-car-desc").html(data.carDesc);
						$("#rideinfo-name").html(data.user.name);
						$("#rideinfo-rating").html(data.user.rating);
					},
				error: function(data, textStatus, jqXHR) {
						alert("Error getting ride info, please try again later.");
					},
				cache: false
			});

	/*var iframe="<iframe width='425' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr="+
		Request.getRequestById(requestId).from+
		"&amp;daddr="+Request.getRequestById(requestId).to+
		"&amp;hl=en&amp;sspn=0.157579,0.338173&amp;t=h&amp;mra=ls&amp;ie=UTF8&amp;ll=35.95363,-78.990775&amp;spn=0.11038,0.10639&amp;output=embed'></iframe><br /><small><a href='https://maps.google.com/maps?f=d&amp;source=embed&amp;saddr="+Request.getRequestById(requestId).from+"&amp;daddr="+Request.getRequestById(requestId).to+"&amp;hl=en&amp;sll=35.95363,-78.990775&amp;sspn=0.157579,0.338173&amp;t=h&amp;mra=ls&amp;ie=UTF8&amp;ll=35.95363,-78.990775&amp;spn=0.11038,0.10639' style='color:#0000FF;text-align:left'>View Larger Map</a></small>";
	$("#map").html(iframe);*/
}
