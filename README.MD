# Symfony file converter application

This is a demo application that converts files between CSV, JSON, SQL, and XLSX formats. The applied design pattern also
allows for addition of extra formats with minimal modifications. 

System Requirements
------------

* PHP 8.2 or above
* PDO-SQLite PHP extension enabled;
* [Git][2]
* [Composer][3]
* [Symfony CLI][4]
* and the [usual Symfony application requirements][5].


Installation
------------

1. Clone the repository

```bash
 git clone https://github.com/ybjozee/symfony_file_converter.git
 cd symfony_file_converter
```

2. Install dependencies

```bash
 composer install
```

3. Update `DATABASE_URL` as required - by default, SQLite is used

``` ini
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

4. Setup database

```bash
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate -n
```

5. Install Node dependencies.

```bash
npm install
```
6. Compile webpack assets

```bash
npm run dev
```

7. Run the application 

```bash
symfony serve
```

[2]: https://git-scm.com/
[3]: https://getcomposer.org/
[4]: https://symfony.com/download
[5]: https://symfony.com/doc/current/reference/requirements.html
