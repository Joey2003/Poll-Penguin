**Test 1 - Test Table Creation**

1. Go to PHPMyAdmin.
2. Run the SQL command in the MySQL database to create a table with the following structure:
    
    **id**: An auto-incrementing integer serving as the primary key.
    **username**: A unique and not null VARCHAR field for the user’s name.
    **password**: A not null VARCHAR field for the user’s hashed password.
    **email**: A unique and not null VARCHAR field for the user’s email address.
    **role**: An ENUM field indicating whether the user is a ‘teacher’ or a ‘student’.
    **created_at**: A TIMESTAMP field that records the account creation time, defaulting to the current timestamp.
    **confirmation_code**: A VARCHAR field to store the one-time confirmation code for email verification.
    
3. Verify that the table is created with the correct structure using the following SQL command: DESCRIBE tablename;

