Setup for XAMPP:

Drop the phonebook folder into htdocs/.
Import schema.sql in phpMyAdmin (creates phonebook_db).
Visit http://localhost/phonebook/login.php.

7 files total — db.php config, login.php (login+register in one page), dashboard.php (sidebar + list, embedded JS), contacts.php (JSON API for add/edit/delete/list), style.css. List/card toggle is done via one CSS class swap + localStorage, no extra library.
→ skipped: favorites/tags, pagination — add columns + query filters when the contact list actually gets long.