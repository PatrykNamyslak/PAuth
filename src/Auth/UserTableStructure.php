<?php
namespace PatrykNamyslak\Auth;

enum UserTableStructure:string{
    case UPPERCASE_WITH_UNDERSCORES = "User_ID, Username, Email, Password";
    case UPPERCASE_CAMMEL = "UserID, Username, Email, Password";

    // since in mysql case sensitivity is not an issue we can generalise.
    case LOWERCASE_AND_UPPERCASE_NO_UNDERSCORES = "userid, username, email, password";
}

?>