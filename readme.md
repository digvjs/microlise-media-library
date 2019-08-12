# Microlise Media Library Laravel/Vue App

> Laravel 5.8 API that uses the API resources with a Vue.js frontend

## Development Environment

- PHP v7.2
- Node v10.10.0
- NPM v6.4.1
- MySQL v8.0.12

## Setup Procedure

``` bash
# Install Dependencies
composer install

# Run Migrations
php artisan migrate

# Import Media Types
php artisan db:seed

# If you get an error about an encryption key
php artisan key:generate

# Install JS Dependencies
npm install

# Watch Files
npm run watch
```

## Endpoints

### List all media with links and meta
``` bash
GET api/media
```
### Get single media
``` bash
GET api/media/{id}
```

### Delete media
``` bash
DELETE api/media/{id}
```

### Add media
``` bash
POST api/media
```

### Update media
``` bash
PUT api/media
```


```

## App Info

### Author

Digvijay Suryawanshi

### Version

1.0.0
