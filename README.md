# PWA projekt Luka Marek

## Basic setup

### `pwa_baza_dump.sql`
* file containing articles to populate the page (no users are in the database)
* start XAMPP and make sure the configuration (port) for the database matches the one selected
* using the file:
    * go to `http://localhost/phpmyadmin/`
    * from `index.php` go to `Import`, select file and click `Import` at the bottom left
    * ~~if the previous didn't work (check if tables and database are loaded with data):~~
        * ~~remove `CREATE DATABASE [...]; USE[...]` lines from `pwa_baza_dump.sql` and proceed with the following:~~
            * ~~create a new database (by clicking new on the left) with `utf8_croatian_ci`~~
                * ~~check that the name (projekt) is the same as in `config/db.php` (also check the port and credentials)~~
            * ~~click on the database name in `phpmyadmin` and got to import, select file and import (no need to use other options)~~

### Page setup
* place the project files inside `C:\xampp\htdocs\{custom_name}` (Windows)
    * replace `{custom_name}` with any name you like (i.e. `projekt`, `C:\xampp\htdocs\projekt`)
* go to `http://localhost/{custom_name}/` and check if everything is showing as intended
* proceed to register a new user under `Administracija` -> `Registracija`
* log in after that
    * logging in allows adding new articles and deleting existing ones

#### misc
* `images/frankfurter_allgemeine.zip`
    * project files from LMS
