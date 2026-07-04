**Test 1 - Test Successful Account Creation**
1. Call the function with valid parameters.
2. Verify that the function returns a confirmation code.
3. Check the database to ensure the new account is created with the correct details and the confirmation code.

**Test 2 - Test Duplicate Username or Email**
1. Insert a record with a known username and email.
2. Call the function with the same username or email.
3. Verify that the function returns false and does not create a duplicate account.

**Test 3 - Test Invalid Role**
1. Call the function with an invalid role.
2. Verify that the function returns false and does not insert the record.

**Test 4 - Test Password Hashing**
1. Call the function with valid parameters and verify that the password is hashed in the database.
