### Pure

1) install composer deps 
```
composer install --no-dev
```
2) compile project into .phar format
```
php --define phar.readonly=0 /app/build/generate```
```
3) execute
```
/app.phar app:calc-attendance
```

### Docker

1) build image "veri"
```
docker build -t veri .
```
2) run container with image "veri"
```
docker run veri
```