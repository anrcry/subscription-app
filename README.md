## The Question

Create a simple subscription platform (only RESTful APIs with MySQL) in which users can subscribe to a website *(there can be multiple websites in the system)*. Whenever a new post is published on a particular website, all it's subscribers shall receive an email with the post title and description in it. **(No authentication of any type is required)**.

## Things to do
- [x] Install laravel
- [x] Prerequisites
- [x] Configuring a database
- [ ] 
- [ ] 
- [ ] 

## Prerequisites
- Assumed you have composer installed from https://getcomposer.org
- Assumed you have node installed from https://nodejs.org **(optional)**
- Assumed you are using a Visual Studios Code as your IDE. **(optional)**
- Assumed you have `PHP>=7.*` installed in your system with the path of the executable in your `$PATH` variable.

## Installing laravel

```sh
$ composer global require laravel/installer
$ laravel new your-app-name
$ cd your-app-name
$ composer update
```

**If you use VS Code as your IDE, please continue with this command**

```sh
$ code .
```

> :bulb: Please open an integrated terminal for your `laravel artisan` commands.

## Configuring a database

By default, laravel & this project assumes you are using **MySQL**.

- Driver: MySQL
- Username: *any_username*
- Password: *any_password*
- Database: *some_name*

> :alert: Please update the necessary credentials in the `.env` found in the project's root. If you do not find one, use the `.env.local` file to create one.

## Project Documentation

### Database Migrations

We have considered the following tables in the database:

1. A table for multiple websites is _websites_.
2. A table for posts on a website is _posts_.
3. A table for list of subscriptions is _mailing\_list_.


### API Routes

The following tables define routes to the different API's.

> Please append `/api` to the routes

#### Websites Routes

| Method |   Route         |       Description               |
|:------:|:----------------|:--------------------------------|
|  GET   | `/websites`     | Get all websites                |
|  POST  | `/websites`     | Add a new website               |
|  GET   | `/websites/{id}`| Get details of a website        |
|  POST  | `/websites/{id}`| Update all details of a website |
|  PUT   | `/websites/{id}`| Specific  details of a website  |

---

#### Posts Routes

| Method |      Route                  |                        Description                      |
|:------:|:----------------------------|:--------------------------------------------------------|
|  GET   | `/websites/{id}/posts`      | Gets details of all posts of a website.                 |
|  POST  | `/websites/{id}/posts`      | Add a new post to the website.                          |
|  GET   | `/websites/{id}/posts/{pid}`| Get details of a particular post of a website.          |
|  POST  | `/websites/{id}/posts/{pid}`| Update all details of particular post of a website.     |
|  PUT   | `/websites/{id}/posts/{pid}`| Update specific details of particular post of a website.|

---

#### Mailing List (Subscription Route)

| Method |      Route                 |                        Description                        |
|:------:|:---------------------------|:----------------------------------------------------------|
| POST   | `/websites/{id}/subscribe` | Add a new subscriber to the website.                      | 
| PUT    | `/websites/{id}/subscribe` | Update name by email in query like `?email=me@example.com`|
| DELETE | `/websites/{id}/subscriber`| Remove subscription from the specific website by email in query like `?email=me@example.com`|
| GET    | `/websites/{id}/subscriber`| Show the details of subscriber of a website by email in query like `?email=me@example.com`|


## Links

### Documentation:

The following links may be helpful:

1. Laravel Docs - https://laravel.com/docs \[At the time of writing the version of the docs was 9.x\]
2. PHP Docs - https://www.php.net/manual/
3. Composer - https://getcomposer.org

### API Implementation

If you want to run the api, you can use the button below:

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/13488408-eed1b06e-68a2-4c82-b8e9-6cb8edef2e86?action=collection%2Ffork&collection-url=entityId%3D13488408-eed1b06e-68a2-4c82-b8e9-6cb8edef2e86%26entityType%3Dcollection%26workspaceId%3Db92b2c7f-201e-44d0-bf3a-bffc2a4e510e)

[You can also use the `JSON` link](https://www.getpostman.com/collections/f2926699d6f9ba10c57b) to deploy on Postman.