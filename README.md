<p align="center">
  <img src="./logo.png" alt="Logo" width="100" height="auto">
</p>

**S-PHP** is a lightweight, simple MVC (Model-View-Controller) PHP framework designed to help developers build scalable and maintainable web applications. It's perfect for those who need a quick start without the complexity of heavier frameworks.

## Features

- **Lightweight**: Minimalistic design with only the core MVC features.
- **Easy Setup**: No complex configurations; simple to get started.
- **Flexible Routing**: Handles URL-to-controller mappings effortlessly.
- **MVC Architecture**: Organizes code into Models, Views, and Controllers.
- **Extensible**: Easily extendable with additional features.

## Requirements

- PHP 7.4 or higher
- Web server (Apache, Nginx, etc.)
- Composer (optional, for dependency management)

## Installation

### 1. Clone the Repository

Clone this repository to your local machine using Git:

```bash
git clone https://github.com/PranabZz/S-PHP.git
```

### 2. Install Dependencies (Optional)

If you wish to use Composer to manage dependencies, run the following:

```bash
composer install
```
### Project Structure

```bash
S-PHP/
│
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
│
├── public/
│   └── index.php       # Entry point for the application
│
├── routes/
│   └── web.php         # Defines routes for the application
│
└── .gitignore
```

### Usage

Create a New Controller

To create a new controller, simply create a new file in the app/Controllers directory:

```php
<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // This method will be invoked when the user accesses the home route
        echo "Welcome to S-PHP!";
    }
}

```


### Define Routes

Routes are defined in routes/web.php. Here is an example route:

```php

$router = new Router();
$router->get('/home', HomeController::class, 'index', Middleware::class);  

```

### Working with Views

You can return views from a controller like this:

```php
namespace App\Controllers;

use Sphp\Core\View;

class HomeController
{
    public function index()
    {
        // Renders the view
        return View::render('home.index');
    }
}
```


## Contributions
If you would like to contribute to this project, feel free to fork the repository and create a pull request with your changes. Please make sure to follow the coding standards and write tests for any new features.