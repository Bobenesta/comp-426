/* pad (int number, int length)
 *
 * Pad number with leading zeros until it is length
 * stolen from http://www.electrictoolbox.com/pad-number-zeroes-javascript/
 */
function pad(number, length) {
	var str = '' + number;
	while (str.length < length)
		str = '0' + str;
	return str;
}

/* Review (float rating, int userIdFrom, string shortComment[, string fullComment])
 *
 * A review of a user including star rating, a short comment,
 * who wrote the review (in the form of a userid) and an options full comment
 */
var Review = function(rating, userIdFrom, shortComment, fullComment) {
	this.rating = rating;
	this.userIdFrom = userIdFrom;
	this.shortComment = shortComment;
	if (fullComment === undefined)
		this.fullComment = null;
	else
		this.fullComment = fullComment;
}

/* deserialize (string serializedReview)
 *
 * Create a Review from its serialized form
 *
 * Format is: NNN (rating), NNNNNNNNNN (userIdFrom),
 * NNN (length of shortComment), shortComment,
 * NNNN (length of fullComment, optional), fullComment
 */
Review.deserialize = function(serialized) {
	var result = new Review();

	result.rating = parseFloat(serialized.substring(0, 3));

	result.userIdFrom = parseInt(serialized.substring(3, 13), 10);

	var shortCommentLength = parseInt(serialized.substring(13, 16), 10);
	result.shortComment = serialized.substring(16, 16 + shortCommentLength);

	if (serialized.length > 16 + shortCommentLength) {
		var fullCommentLength = parseInt(serialized.substring(16 + shortCommentLength, 20 + shortCommentLength), 10);
		result.fullComment = serialized.substring(20 + shortCommentLength, 20 + shortCommentLength + fullCommentLength);
	} else {
		result.fullComment = null;
	}

	return result;
}

/* User (int userId, string displayName, float averageRating, Review[] reviews)
 *
 * Models information associated with each user (as can be seen by another user).
 */
var User = function(userId, displayName, averageRaiting, reviews) {
	this.userId = userId;
	this.displayName = displayName;
	this.averageRaiting = averageRaiting;
	this.reviews = reviews;
}

// Keep track of the full list of users we have cached on the client side
User.all = {};

/* getUserById(int userId)
 *
 * gets the User object by its id (TODO: possibly by requesting it from the server)
 * returns null if it doesnt exist
 */
User.getUserById = function(userId) {
	return User.all[userId];
}

/* getUserFrom()
 *
 * gets the User object which filed this review (see User.getUserById)
 */
Review.prototype.getUserFrom = function() {
	return User.getUserById(this.userIdFrom);
}

user5915Reviews = new Array();
user5915Reviews.push(new Review(5, 1000, "perfect"));
user5915Reviews.push(new Review(4, 1001, "good", "not perfect"));
User.all[5915] = new User(5915, "Matt", 4.5, user5915Reviews);

User.all[1000] = new User(1000, "Jack", 0, {});

user1001Reviews = new Array();
user1001Reviews.push(new Review(5, 5915, "fun"));
User.all[1001] = new User(1001, "Benoit", 5, user1001Reviews);

/*//Basic sanity testing
console.log(User.all[5915].reviews[0].getUserFrom());
console.log(User.all[5915].reviews[1].getUserFrom());
*/

/*//Basic sanity testing
var reviewOne = Review.deserialize("1.54796739617001a");
console.log(reviewOne);
reviewOne = Review.deserialize("1.54796739617001a0003abc");
console.log(reviewOne);*/
