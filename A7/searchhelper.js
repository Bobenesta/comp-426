// requires: userinfo.js

/* Address (bool isUNC, string addressLine, string city, string state, int raidus)
 *
 * Address as sent to the server for search requests
 */
var Address = function(isUNC, addressLine, city, state, radius) {
	this.isUNC = isUNC;
	this.addressLine = addressLine;
	this.city = city;
	this.state = state;
	this.radius = radius;
}

/* getShortName()
 *
 * gets a useful name for this address (eg UNC or City, State)
 */
Address.prototype.getShortName = function() {
	if (this.isUNC)
		return "UNC";
	else
		return this.city + ", " + this.state;
}

/* Result(int resultId, string fromAddress, string toAddress, string date, int userId)
 *
 * Stores a result to a SearchQuery including the from/to address, the date, and the userId.
 * Note that we use strings here instead of Addresses because an Address has a lot of information
 * whereas we really only care about what we need to display (the Address.getShortName() data).
 */
var Result = function(resultId, fromAddress, toAddress, date, userId) {
	this.id = resultId;
	this.fromAddress = fromAddress;
	this.toAddress = toAddress;
	this.date = date;
	this.userId = userId;
}

/* ResultSet(Result[] results)
 *
 * Stores a set of results
 * Mostly just used just for convinience when serializing/deserializing
 */
var ResultSet = function(results) {
	this.results = results;
}

/* SearchQuery (Address startAddress, Address endAddress, string date, bool isMorning)
 *
 * Address as sent to the server for search requests
 */
var SearchQuery = function(startAddress, endAddress, date, isMorning) {
 	this.startAddress = startAddress;
 	this.endAddress = endAddress;
 	this.date = date;
 	this.isMorning = isMorning;
}

/* getSearchQueryFromSearchFields(DivElement containingDiv)
 *
 * Gets a SearchQuery based on all the fields in the form in containingDiv
 */
SearchQuery.getSearchQueryFromSearchFields = function(containingDiv) {
	var result = new SearchQuery();
	if (containingDiv.find("#start-type").val() == "unc")
		result.startAddress = new Address(true, "", "", "",
			parseInt(containingDiv.find("#start-within").val()));
	else
		result.startAddress = new Address(false,
			containingDiv.find("#start-address-textbox").val(),
			containingDiv.find("#start-city-textbox").val(),
			containingDiv.find("#start-state-textbox").val(),
			parseInt(containingDiv.find("#start-within").val()));

	if (containingDiv.find("#dest-type").val() == "unc")
		result.endAddress = new Address(true, "", "", "",
			parseInt(containingDiv.find("#dest-within").val()));
	else
		result.endAddress = new Address(false,
			containingDiv.find("#dest-address-textbox").val(),
			containingDiv.find("#dest-city-textbox").val(),
			containingDiv.find("#dest-state-textbox").val(),
			parseInt(containingDiv.find("#dest-within").val()));

	result.date = containingDiv.find("#datebox").val();

	var carDescField = containingDiv.find("#cardesc");
	if (carDescField != null)
		result.carDesc = carDescField.val();

	var carCapacityField = containingDiv.find("#carcapacity");
	if (carCapacityField != null)
		result.carCapacity = carCapacityField.val();

	if (containingDiv.find("#time-selector").val() == "morning")
		result.isMorning = true;
	else
		result.isMorning = false;

	return result;
}

/* placeSearchQueryInSearchFields(DivElement containingDiv)
 *
 * Fills in all the fields in the form in containingDiv based on a search query
 */
SearchQuery.prototype.placeSearchQueryInSearchFields = function(containingDiv) {
	if (this.startAddress.isUNC) {
		containingDiv.find("#start-type").val("unc");
	} else {
		containingDiv.find("#start-type").val("other");
		containingDiv.find("#start-address-textbox").val(this.startAddress.addressLine);
		containingDiv.find("#start-city-textbox").val(this.startAddress.city);
		containingDiv.find("#start-state-textbox").val(this.startAddress.state);
	}
	containingDiv.find("#start-within").val(this.startAddress.radius);

	if (this.endAddress.isUNC) {
		containingDiv.find("#dest-type").val("unc");
	} else {
		containingDiv.find("#dest-type").val("other");
		containingDiv.find("#dest-address-textbox").val(this.endAddress.addressLine);
		containingDiv.find("#dest-city-textbox").val(this.endAddress.city);
		containingDiv.find("#dest-state-textbox").val(this.endAddress.state);
	}
	containingDiv.find("#dest-within").val(this.endAddress.radius);

	containingDiv.find("#datebox").val(this.date);

	if (this.isMorning == true) {
		containingDiv.find("#time-selector").val("morning");
	} else {
		containingDiv.find("#time-selector").val("afternoon");
	}

	autoDisableAddressCityStateText(containingDiv.find("#start-type"), containingDiv.find("#start-address-textbox"), containingDiv.find("#start-city-textbox"), containingDiv.find("#start-state-textbox"));
	autoDisableAddressCityStateText(containingDiv.find("#dest-type"), containingDiv.find("#dest-address-textbox"), containingDiv.find("#dest-city-textbox"), containingDiv.find("#dest-state-textbox"));
}

/* placeResultSetInTable(TableElement tableElement, String apiURL, String infoURL)
 *
 * gets a result set which matches this SearchQuery and places it in the given table
 *
 * apiURL is the api AJAX URL, infoURL is the URL for a more info link
 */
SearchQuery.prototype.placeResultSetInTable = function(tableElement, apiURL, infoURL) {
	// Clear the table
	tableElement.empty();
	// Re-add headers
	var headers = $("<tr><th>From</th><th>To</th><th>When</th><th>User</th></tr>");
	tableElement.append(headers);

	var data_pairs = {}

	data_pairs['addressTo-isUNC'] = this.startAddress.isUNC;
	data_pairs['addressTo-addressLine'] = this.startAddress.addressLine;
	data_pairs['addressTo-city'] = this.startAddress.city;
	data_pairs['addressTo-state'] = this.startAddress.state;
	data_pairs['addressTo-radius'] = this.startAddress.radius;

	data_pairs['addressFrom-isUNC'] = this.endAddress.isUNC;
	data_pairs['addressFrom-addressLine'] = this.endAddress.addressLine;
	data_pairs['addressFrom-city'] = this.endAddress.city;
	data_pairs['addressFrom-state'] = this.endAddress.state;
	data_pairs['addressFrom-radius'] = this.endAddress.radius;

	data_pairs["date"] = this.date;
	data_pairs["isMorning"] = this.isMorning;

	$.ajax(apiURL,
			{
				type: 'GET',
				data: data_pairs,
				success: function(data, textStatus, jqXHR) {
						for(var i = 0; i < data.length; i++) {
							var user = User.getUserById(data[i].userId);
							tableElement.append($("<tr><td><a href='" + infoURL + "?id=" + data[i].id + "'>" + Address.prototype.getShortName.call(data[i].fromAddress) + "</a></td><td><a href='rideinfo.php?id=" + data[i].id + "'>" +
									Address.prototype.getShortName.call(data[i].toAddress) + "</a></td><td><a href='" + infoURL + "?id=" + data[i].id + "'>" + data[i].date + "</a></td><td id=" +
									user.userId + "><a href='#user-box' onclick='userBoxHandler(" + user.userId + ");'>" + user.displayName + "</a></td></tr>"));
						}
					},
				error: function(data, textStatus, jqXHR) {
						alert("Error updating search results, please try again later.");
					},
				cache: false
			});
}

/* createNew(String apiURL, String redirectURL)
 *
 * Creates a new object using the apiURL and then redirects to redirectURL?id=new_id
 */
SearchQuery.prototype.createNew = function(apiURL, redirectURL) {
	var data_pairs = {}

	data_pairs['addressTo-isUNC'] = this.startAddress.isUNC;
	data_pairs['addressTo-addressLine'] = this.startAddress.addressLine;
	data_pairs['addressTo-city'] = this.startAddress.city;
	data_pairs['addressTo-state'] = this.startAddress.state;
	data_pairs['addressTo-radius'] = this.startAddress.radius;

	data_pairs['addressFrom-isUNC'] = this.endAddress.isUNC;
	data_pairs['addressFrom-addressLine'] = this.endAddress.addressLine;
	data_pairs['addressFrom-city'] = this.endAddress.city;
	data_pairs['addressFrom-state'] = this.endAddress.state;
	data_pairs['addressFrom-radius'] = this.endAddress.radius;

	data_pairs["date"] = this.date;
	data_pairs["isMorning"] = this.isMorning;

	if (this.carDesc != undefined)
		data_pairs["carDesc"] = this.carDesc;
	if (this.carCapacity != undefined)
		data_pairs["carCapacity"] = this.carCapacity;

	$.ajax(apiURL,
			{
				type: 'POST',
				data: data_pairs,
				success: function(data, textStatus, jqXHR) {
						window.location.href = redirectURL + "?id=" + data.id;
					},
				error: function(data, textStatus, jqXHR) {
						alert("Error contacting server, please try again later.");
					},
				cache: false
			});
}

/* getShortName()
 *
 * Gets a short human-readable name for this query
 */
SearchQuery.prototype.getShortName = function() {
	if (this.endAddress.isUNC == false)
		return "To " + this.endAddress.city + " on " + this.date;
	else
		return "From " + this.startAddress.city + " on " + this.date;
}

/* userBoxHandler(int userId)
 *
 * shows/hides the user box and fills it with the given userId
 */
var userBoxHandler = function(userId) {
	var userBox = $("#user-box");
	var user = User.getUserById(userId);

	$("#user-box-name").html(user.averageRaiting + "/5");
	if (user.reviews.length > 0)
		$("#user-box-rating-1").html(user.reviews[0].rating + ": " + user.reviews[0].shortComment);
	else
		$("#user-box-rating-1").html("");
	if (user.reviews.length > 1)
		$("#user-box-rating-2").html(user.reviews[1].rating + ": " + user.reviews[1].shortComment);
	else
		$("#user-box-rating-2").html("");

	if (userBox.css("visibility") == 'hidden') {
		userBox.css("visibility", "visible");
	} else {
		userBox.css("visibility", "hidden");
	}
}

/* fillSelectorWithMyRequests(selector element)
 *
 * Fills the given HTML selector with data from SearchQueries.myQueries
 */
SearchQuery.fillSelectorWithMyRequests = function(selectorElement) {
	selectorElement.empty();
	selectorElement.append($('<option value="-">Filled In Above</option>'));
	for(var i = 0; i < SearchQuery.myQueries.length; i++) {
		selectorElement.append($('<option value="' + i + '">' + SearchQuery.myQueries[i].getShortName() + '</option>'));
	}
}

/* autoDisableAddressCityStateText(Element selector, Element addressTextBox, Element cityTextBox, Element stateTextBox)
 *
 * Disables/enables addressTextBox and cityStateTextBox based on the unc/other value of selector
 */
autoDisableAddressCityStateText = function(selector, addressTextBox, cityTextBox, stateTextBox) {
	if (selector.val() == "unc") {
		addressTextBox.val("");
		addressTextBox.attr("disabled", "disabled");
		cityTextBox.val("");
		cityTextBox.attr("disabled", "disabled");
		stateTextBox.val("");
		stateTextBox.attr("disabled", "disabled");
	} else {
		addressTextBox.removeAttr("disabled");
		cityTextBox.removeAttr("disabled");
		stateTextBox.removeAttr("disabled");
	}
}

/* updateForms(Element selectorElement, Element form)
 *
 * Fills the given HTML form with data from SearchQueries.myQueries (selected by selectorElement)
 */
SearchQuery.updateForms = function(selectorElement, formDiv) {
	if (selectorElement.val() == "-") {
		formDiv.find("#start-type").val("unc");
		formDiv.find("#start-address-textbox").val("");
		formDiv.find("#start-city-textbox").val("");
		formDiv.find("#start-state-textbox").val("");
		formDiv.find("#start-within").val(10);

		formDiv.find("#dest-type").val("unc");
		formDiv.find("#dest-address-textbox").val("");
		formDiv.find("#dest-city-textbox").val("");
		formDiv.find("#dest-state-textbox").val("");
		formDiv.find("#dest-within").val(10);

		formDiv.find("#datebox").val("");
		formDiv.find("#time-selector").val("morning");

		autoDisableAddressCityStateText(formDiv.find("#start-type"), formDiv.find("#start-address-textbox"), formDiv.find("#start-city-textbox"), formDiv.find("#start-state-textbox"));
		autoDisableAddressCityStateText(formDiv.find("#dest-type"), formDiv.find("#dest-address-textbox"), formDiv.find("#dest-city-textbox"), formDiv.find("#dest-state-textbox"));
	} else {
		var query = SearchQuery.myQueries[parseInt(selectorElement.val(), 10)];
		query.placeSearchQueryInSearchFields(formDiv);
	}
}

SearchQuery.myQueries = new Array();
SearchQuery.myQueries.push(new SearchQuery(new Address(true, "", "", "", 10), new Address(false, "Duke", "Durham", "NC", 10), "09/26/2012", true));
SearchQuery.myQueries.push(new SearchQuery(new Address(false, "N/A", "Charlotte", "NC", 10), new Address(true, "", "", "", 10), "09/26/2012", false));
SearchQuery.myQueries.push(new SearchQuery(new Address(false, "N/A", "Hillsbourogh", "NC", 10), new Address(true, "", "", "", 20), "09/26/2012", true));
SearchQuery.myQueries.push(new SearchQuery(new Address(false, "NCSU", "Raleigh", "NC", 20), new Address(true, "", "", "", 10), "09/29/2012", true));

/*//Basic sanity testing
var address = new Address(true, "", "", 0);
console.log(address.serialize());
address = Address.deserialize(address.serialize());
console.log(address);
var address2 = new Address(false, "address", "state", 10);
console.log(address2.serialize());
address = Address.deserialize(address2.serialize());
console.log(address);
console.log(address2);
var address3 = new Address(false, "Sitterson", "NC", 20);

var search1 = new SearchQuery(address, address3, "07/07/2012", false);
console.log(search1.serialize());
var search2 = SearchQuery.deserialize(search1.serialize());
console.log(search2);*/
