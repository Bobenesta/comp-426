// requires: userinfo.js

/* Address (bool isUNC, string addressLine, string cityStateLine, int raidus)
 *
 * Address as sent to the server for search requests
 */
var Address = function(isUNC, addressLine, cityStateLine, radius) {
 	this.isUNC = isUNC;
 	this.addressLine = addressLine;
 	this.cityStateLine = cityStateLine;
 	this.radius = radius;
}

/* deserialize (string serializedAddress)
 *
 * Create an address from its serialized form
 *
 * Format is: 1/0 for isUNC, NNN (length of addressLine), addressLine,
 * NNN (length of cityStateLine), cityStateLine, raidus
 */
Address.deserialize = function(serialized) {
	var result = new Address();

	if (serialized[0] == "1")
		result.isUNC = true;
	else if (serialized[0] == "0")
		result.isUNC = false;
	else
		return;

	if (!result.isUNC) {
		var addressLineLength = parseInt(serialized.substring(1, 4), 10);
		result.addressLine = serialized.substring(4, 4 + addressLineLength);
		var cityStateLengthStart = 4 + addressLineLength;

		var cityStateLineLength = parseInt(serialized.substring(cityStateLengthStart, cityStateLengthStart + 3), 10);
		result.cityStateLine = serialized.substring(cityStateLengthStart + 3, cityStateLengthStart + 3 + cityStateLineLength);
		var radiusStart = cityStateLengthStart + 3 + cityStateLineLength;
	} else
		var radiusStart = 1;

	result.radius = parseInt(serialized.substring(radiusStart), 10);

	return result;
}

/* serialize()
 *
 * Get a serialized version of the address suitable for sending to the server
 * for processing as a part of a SearchQuery.
 *
 * Format is: 1/0 for isUNC, NNN (length of addressLine), addressLine,
 * NNN (length of cityStateLine), cityStateLine, raidus
 */
Address.prototype.serialize = function() {
	if (this.addressLine.length >= 1000 || this.cityStateLine >= 1000)
		return "-1";
	if (this.isUNC)
		return "1" + this.radius;
	else
		return "0" +
			pad(this.addressLine.length, 3) + this.addressLine +
			pad(this.cityStateLine.length, 3) + this.cityStateLine +
			this.radius;
}

/* getShortName()
 *
 * gets a useful name for this address (eg UNC or City, State)
 */
Address.prototype.getShortName = function() {
	if (this.isUNC)
		return "UNC";
	else
		return this.cityStateLine;
}

/* Result(int resultId, string fromAddress, string toAddress, string date, int userId)
 *
 * Stores a result to a SearchQuery including the from/to address, the date, and the userId.
 * Note that we use strings here instead of Addresses because an Address has a lot of information
 * whereas we really only care about what we need to display (the Address.getShortName() data).
 */
var Result = function(resultId, fromAddress, toAddress, date, userId) {
	this.resultId = resultId;
	this.fromAddress = fromAddress;
	this.toAddress = toAddress;
	this.date = date;
	this.userId = userId;
}

/* deserialize (string serializedResult)
 *
 * Create a Result from its serialized form
 *
 * Format is: NNNNNNNNNN (resultId), NNNNNNNNNN (userId),
 * NNN (length of fromAddress), fromAddress, NNN (length of toAddress), toAddress,
 * NNN (length of date), date
 */
Result.deserialize = function(serialized) {
	var retVal = new Result();

	retVal.resultId = parseInt(serialized.substring(0, 10), 10);
	retVal.userId = parseInt(serialized.substring(10, 20), 10);

	var fromAddressLength = parseInt(serialized.substring(20, 23), 10);
	retVal.fromAddress = serialized.substring(23, 23 + fromAddressLength);

	var toAddressLength = parseInt(serialized.substring(23 + fromAddressLength, 26 + fromAddressLength), 10);
	var dateLengthStart = 26 + fromAddressLength + toAddressLength;
	retVal.toAddress = serialized.substring(26 + fromAddressLength, dateLengthStart);

	var dateLength = serialized.substring(dateLengthStart, dateLengthStart + 3);
	retVal.date = serialized.substring(dateLengthStart + 3, dateLengthStart + 3 + dateLength);

	return retVal;
}

/* ResultSet(Result[] results)
 *
 * Stores a set of results
 * Mostly just used just for convinience when serializing/deserializing
 */
var ResultSet = function(results) {
	this.results = results;
}

/* deserialize (string serializedResultSet)
 *
 * Create a ResultSet from its serialized form
 *
 * Format is: NNN (size of result), result, NNN (size of result), result, 
 *            NNN (size of result), result, ...
 */
ResultSet.deserialize = function(serialized) {
	var result = new ResultSet(new Array());
	var nextResultStart = 0;
	while (nextResultStart < serialized.length) {
		var nextResultLength = parseInt(serialized.substring(nextResultStart, nextResultStart + 3), 10);
		result.results.push(Result.deserialize(serialized.substring(nextResultStart + 3, nextResultStart + 3 + nextResultLength)));
		nextResultStart = nextResultStart + 3 + nextResultLength;
	}
	return result;
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

/* deserialize (string serializedSearchQuery)
 *
 * Create a SearchQuery from its serialized form
 *
 * Format is: NNNN (length of serialized startAddress), startAddress,
 * NNNN (length of serialized endAddress), endAddress, NNN (length of date),
 * date, 1/0 for isMorning
 */
SearchQuery.deserialize = function(serialized) {
	if (serialized[0] == "-")
		return null;

	var result = new SearchQuery();

	var startAddressLength = parseInt(serialized.substring(0, 4), 10);
	result.startAddress = Address.deserialize(serialized.substring(4, 4 + startAddressLength));
	var endAddressLengthStart = 4 + startAddressLength;

	var endAddressLength = parseInt(serialized.substring(endAddressLengthStart, endAddressLengthStart + 4), 10);
	result.endAddress = Address.deserialize(serialized.substring(endAddressLengthStart + 4, endAddressLengthStart + 4 + endAddressLength));
	var dateLengthStart = endAddressLengthStart + 4 + endAddressLength;

	var dateLength = parseInt(serialized.substring(dateLengthStart, dateLengthStart + 3), 10);
	result.date = serialized.substring(dateLengthStart + 3, dateLengthStart + 3 + dateLength);

	if (serialized[dateLengthStart + 3 + dateLength] == 1)
		result.isMorning = true;
	else
		result.isMorning = false;

	return result;
}

/* serialize()
 *
 * Get a serialized version of the SearchQuery suitable for sending to the server
 * for processing.
 *
 * Format is: NNNN (length of serialized startAddress), startAddress,
 * NNNN (length of serialized endAddress), endAddress, NNN (length of date),
 * date, 1/0 for isMorning
 */
SearchQuery.prototype.serialize = function() {
	var startAddressSerialized = this.startAddress.serialize();
	var endAddressSerialized = this.endAddress.serialize();
	if (this.date.length >= 1000 || startAddressSerialized.length >= 10000 ||
		endAddressSerialized.length >= 10000) {
		return "-1";
	} else if (this.isMorning) {
		return pad(startAddressSerialized.length, 4) + startAddressSerialized +
			pad(endAddressSerialized.length, 4) + endAddressSerialized +
			pad(this.date.length, 3) + this.date +
			"1";
	} else {
		return pad(startAddressSerialized.length, 4) + startAddressSerialized +
			pad(endAddressSerialized.length, 4) + endAddressSerialized +
			pad(this.date.length, 3) + this.date +
			"0";
	}
}

/* getSearchQueryFromSearchFields(DivElement containingDiv)
 *
 * Gets a SearchQuery based on all the fields in the form in containingDiv
 */
SearchQuery.getSearchQueryFromSearchFields = function(containingDiv) {
	var result = new SearchQuery();
	if (containingDiv.find("#start-type").val() == "unc")
		result.startAddress = new Address(true, "", "",
			parseInt(containingDiv.find("#start-within").val()));
	else
		result.startAddress = new Address(false,
			containingDiv.find("#start-address-textbox").val(),
			containingDiv.find("#start-citystate-textbox").val(),
			parseInt(containingDiv.find("#start-within").val()));

	if (containingDiv.find("#dest-type").val() == "unc")
		result.endAddress = new Address(true, "", "",
			parseInt(containingDiv.find("#dest-within").val()));
	else
		result.endAddress = new Address(false,
			containingDiv.find("#dest-address-textbox").val(),
			containingDiv.find("#dest-citystate-textbox").val(),
			parseInt(containingDiv.find("#dest-within").val()));

	result.date = containingDiv.find("#datebox").val();

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
		containingDiv.find("#start-citystate-textbox").val(this.startAddress.cityStateLine);
	}
	containingDiv.find("#start-within").val(this.startAddress.radius);

	if (this.endAddress.isUNC) {
		containingDiv.find("#dest-type").val("unc");
	} else {
		containingDiv.find("#dest-type").val("other");
		containingDiv.find("#dest-address-textbox").val(this.endAddress.addressLine);
		containingDiv.find("#dest-citystate-textbox").val(this.endAddress.cityStateLine);
	}
	containingDiv.find("#dest-within").val(this.endAddress.radius);

	containingDiv.find("#datebox").val(this.date);

	if (this.isMorning == true) {
		containingDiv.find("#time-selector").val("morning");
	} else {
		containingDiv.find("#time-selector").val("afternoon");
	}

	autoDisableAddressCityStateText(containingDiv.find("#start-type"), containingDiv.find("#start-address-textbox"), containingDiv.find("#start-citystate-textbox"));
	autoDisableAddressCityStateText(containingDiv.find("#dest-type"), containingDiv.find("#dest-address-textbox"), containingDiv.find("#dest-citystate-textbox"));
}

/* placeResultSetInTable(TableElement tableElement)
 *
 * gets a result set which matches this SearchQuery and places it in the given table
 * (TODO: Request results from server instead of doing anything static here)
 */
SearchQuery.prototype.placeResultSetInTable = function(tableElement) {
	// Clear the table
	tableElement.empty();
	// Re-add headers
	var headers = $("<tr><th>From</th><th>To</th><th>When</th><th>User</th></tr>");
	tableElement.append(headers);

	var resultSet = ResultSet.deserialize("05100000000010000005915003UNC009Charlotte01007/07/2012"+"04900000000010000001000003UNC004Duke01009/26/2012");

	for(var i = 0; i < resultSet.results.length; i++) {
		// TODO: This is going to have to be async
		var user = User.getUserById(resultSet.results[i].userId);
		tableElement.append($("<tr><td>" + resultSet.results[i].fromAddress + "</td><td>" +
				resultSet.results[i].toAddress + "</td><td>" + resultSet.results[i].date + "</td><td id=" +
				user.userId + "><a href='#user-box' onclick='userBoxHandler(" + user.userId + ");'>" + user.displayName + "</a></td></tr>"));
	}
}

/* getShortName()
 *
 * Gets a short human-readable name for this query
 */
SearchQuery.prototype.getShortName = function() {
	if (this.endAddress.isUNC == false)
		return "To " + this.endAddress.cityStateLine + " on " + this.date;
	else
		return "From " + this.startAddress.cityStateLine + " on " + this.date;
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

/* autoDisableAddressCityStateText(Element selector, Element addressTextBox, Element cityStateTextBox)
 *
 * Disables/enables addressTextBox and cityStateTextBox based on the unc/other value of selector
 */
autoDisableAddressCityStateText = function(selector, addressTextBox, cityStateTextBox) {
	if (selector.val() == "unc") {
		addressTextBox.val("");
		addressTextBox.attr("disabled", "disabled");
		cityStateTextBox.val("");
		cityStateTextBox.attr("disabled", "disabled");
	} else {
		addressTextBox.removeAttr("disabled");
		cityStateTextBox.removeAttr("disabled");
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
		formDiv.find("#start-citystate-textbox").val("");
		formDiv.find("#start-within").val(10);

		formDiv.find("#dest-type").val("unc");
		formDiv.find("#dest-address-textbox").val("");
		formDiv.find("#dest-citystate-textbox").val("");
		formDiv.find("#dest-within").val(10);

		formDiv.find("#datebox").val("");
		formDiv.find("#time-selector").val("morning");

		autoDisableAddressCityStateText(formDiv.find("#start-type"), formDiv.find("#start-address-textbox"), formDiv.find("#start-citystate-textbox"));
		autoDisableAddressCityStateText(formDiv.find("#dest-type"), formDiv.find("#dest-address-textbox"), formDiv.find("#dest-citystate-textbox"));
	} else {
		var query = SearchQuery.myQueries[parseInt(selectorElement.val(), 10)];
		query.placeSearchQueryInSearchFields(formDiv);
	}
}

SearchQuery.myQueries = new Array();
SearchQuery.myQueries.push(new SearchQuery(new Address(true, "", "", 10), new Address(false, "Duke", "Durham, NC", 10), "09/26/2012", true));
SearchQuery.myQueries.push(new SearchQuery(new Address(false, "N/A", "Charlotte, NC", 10), new Address(true, "", "", 10), "09/26/2012", false));
SearchQuery.myQueries.push(new SearchQuery(new Address(false, "N/A", "Hillsbourogh, NC", 10), new Address(true, "", "", 20), "09/26/2012", true));
SearchQuery.myQueries.push(new SearchQuery(new Address(false, "NCSU", "Raleigh, NC", 20), new Address(true, "", "", 10), "09/29/2012", true));

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
