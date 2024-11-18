### Example of how to use property hooks in PHP 8.4.
This is the complete repository for a blog article that I wrote.
[https://oliverlundquist.com/2024/11/18/php84-property-hooks-and-data-objects.html](https://oliverlundquist.com/2024/11/18/php84-property-hooks-and-data-objects.html)

### How to run this app on your own machine
```
docker run -it -p 8000:8000 -v $PWD:/var/app/current -w /var/app/current php:8.4.0RC4 php artisan serve --host 0.0.0.0 --port 8000
```
