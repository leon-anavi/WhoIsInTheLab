package main

// We don't need all the fields when retrieving list of users
// so use a more sparse user structure
type SimpleUser struct {
	Id int32 `db:"user_id" json:"id"`
	FirstName string `db:"user_name1" json:"firstname"`
	LastName string `db:"user_name2" json:"lastname"`
}

// This is the full blown user stucture.
// Will be used for crud 
type User struct {
	Id int32 `db:"user_id" json:"id"`
	FirstName string `db:"user_name1" json:"firstname"`
	LastName string `db:"user_name2" json:"lastname"`
	Twitter string `db:"user_twitter" json:"twitter"`
	Facebook string `db:"user_facebook" json:"facebook"`
	Phone string `db:"user_tel" json:"phone"`
	Email string `db:"user_email" json:"email"`
	Website string `db:"user_website" json:"website"`
	FoursquareToken string `db:"user_fstoken" json:"fs_token"`
	GPlus string `db:"user_google_plus" json:"gplus"`
	FoursquareCheckin string  `db:"user_fscheckin" json:"fs_checkin"`
}

