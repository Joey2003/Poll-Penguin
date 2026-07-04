**Test 1 - Test with Valid Credentials**
1. Insert a test record into the accounts database table with a username and password.
2. Call the function with these credentials.
3. Verify that the function returns true.

**Test 2 - Test with Invalid Password**
1. Call the function with a valid username but unknown password.
2. Verify that the function returns false.

**Test 3 - Test with Non-Existent Username**
1. Call the function with a non-existent username.
2. Verify that the function returns false.

**Test 4 - Test SQL Injection Protection**
1. Call the function with a username containing SQL injection code.
2. Verify that the function returns false and does not execute the injected code.
