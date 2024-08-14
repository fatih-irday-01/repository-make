

![Laravel Reposiyory](./laravel_repository.jpg)

## Installation

1. Run composer command to install the package

```shell
composer require fatihirday/repository-make
```

2. Publish the config and migration files.

```shell
php artisan vendor:publish --provider="Fatihirday\RepositoryMake\Providers\RepositoryServiceProvider"
```

* Response

```
* config/repository.php
```

## Configuration

1. Config repository File

```php
return [
    'folder' => 'Services',
];
```

| Config                                | Des                           |
|---------------------------------------|-------------------------------|
| `folder`                         | Interface and Repository path |


## Make Repository and Interface
```bash
php artisan make:repository DemoRepository
```








