# Laravel starter template for shared hosting

This template is specifically for deploying laravel application on shared hosting

## Installation

```bash
composer install 

php artisan key:generate

php artisan migrate

php artisan db:seed --class=AdminAccountSeeder

php artisan dusk:chrome-driver --detect

npm install
```

## Features

### Pest for Testing

- Includes arch test
  
### Pint for CS fixing

- Includes custom config
  
### Tailwind CSS

- For design

### Test Controller

- For testing misc things

### Helper Files

- For helper functions
  
### Custom Config File

- For storing application specific settings
  
### Log viewer

- For viewing logs
  
### Admin Panel

### Oauth Login with socialite
 
### Analytics 

- Google analytics
- Microsoft clarity

### Other features

- HomeController
- App Layout
  - Top Navbar
  - Side Navbar
  - Footer
- DTOs
- Deployment script
- Laravel debugbar
- Laravel dusk for frontend testing
- Powergrid Tables
- Example user CRUD
- Legal pages
- AdminAccess Middleware