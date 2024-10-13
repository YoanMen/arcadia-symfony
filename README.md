
# Arcadia Zoo Application

deployed application => https://arcadia.menardyoan.com/

This is a fictional project, presented at my final exam.

## Context

The client contact my agence to make a website to get visitors informed about Arcadia zoo. He want to be use this application to make reports  about animals and habitas by employees, to get follow. 


## Tech Stack

**Front:** Twigg, TailwindCSS

**Back:** Symfony


## Features

- pages for animals, habitats and services
- visitors can send advice and email
- employees can write report for animal food, approved visitors advices and manage services.
- veterinarys can write animal report, habitat commentary and consult reports food
- administrator can manage application (habitats, services, animals, schedules), he can consult reports by veterinarys and famous animals on the website.


## Run Locally

Clone the project

```bash
  git clone https://github.com/YoanMen/arcadia-symfony.git
```

Go to the project directory and reopen in Container


Install dependencies

```bash
  composer install
  php bin/console tailwind:build
```

Need to create MongoDB database, connect with : 

    mongodb://root:rootpassword@localhost:27017/

Create "arcadia" database for MongoDB.

Create relational ddb with Symfony

```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migration:migrate
```

initialize needed ressources

```bash
  php bin/console doctrine:fixture:load
```

Start the server

```bash
  symfony serve
```
