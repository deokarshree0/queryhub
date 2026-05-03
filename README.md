# QueryHub

## Run steps
1. Copy the project folder into XAMPP `htdocs`.
2. Start **Apache** and **MySQL**.
3. Open `phpMyAdmin` and import `queryhub_schema.sql`.
4. Make sure the database name is `queryhub`.
5. Open `http://localhost/discuss/` in your browser.

## Notes
- Database connection is in `common/db.php`.
- Theme toggle is in the top navbar.
- Old plaintext passwords can still log in, but new signups are hashed.
