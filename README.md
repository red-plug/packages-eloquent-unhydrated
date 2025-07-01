# Eloquent Unhydrated

Eloquent Unhydrated allows you to retrieve database info without the pain of hydrating every model, the idea originally came from [Laravel Crud Wizard Free](https://medium.com/@marius_18835/eloquent-is-faster-when-used-without-hydration-25e80c5eb135)

## Installation

Use the package manager composer to install `eloquent-unhydrated`.

```bash
composer require red-plug/eloquent-unhydrated
```

## Usage

```php
use App\Models\User;

User::getUnhydrated();
User::cursorPaginateUnhydrated();
User::paginateUnhydrated();
User::paginateUsingCursorUnhydrated();
User::simplePaginateUnhydrated();
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)