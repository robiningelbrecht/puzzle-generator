<h1 align="center">Puzzle generator</h1>

<p align="center">
	<img src="https://raw.githubusercontent.com/robiningelbrecht/puzzle-generator/master/public/logo.png" alt="Slim" width="200">
</p>

<p align="center">
<a href="https://github.com/robiningelbrecht/puzzle-generator/actions/workflows/ci.yml"><img src="https://github.com/robiningelbrecht/puzzle-generator/actions/workflows/ci.yml/badge.svg" alt="CI"></a>
<a href="https://codecov.io/gh/robiningelbrecht/puzzle-generator" ><img src="https://codecov.io/gh/robiningelbrecht/puzzle-generator/branch/master/graph/badge.svg?token=hgnlFWvWvw" alt="Codecov.io"/></a>
<a href="https://github.com/robiningelbrecht/puzzle-generator/blob/master/LICENSE"><img src="https://img.shields.io/github/license/robiningelbrecht/puzzle-generator?color=428f7e&logo=open%20source%20initiative&logoColor=white" alt="License"></a>
<a href="https://phpstan.org/"><img src="https://img.shields.io/badge/PHPStan-level%209-succes.svg?logo=php&logoColor=white&color=31C652" alt="PHPStan Enabled"></a>
<a href="https://php.net/"><img src="https://img.shields.io/packagist/php-v/robiningelbrecht/puzzle-generator/dev-master?color=%23777bb3&logo=php&logoColor=white" alt="PHP"></a>
</p>

---

This is a PHP library intended to render Rubik's cube puzzles as SVG images.
The idea is to do this by navigating to `https://puzzle-generator.robiningelbrecht.be/cube`
and provide query parameters to configure the desired cube.

It's heavily inspired by <a href="https://github.com/tdecker91/visualcube">visualcube</a>
and <a href="https://github.com/tdecker91/puzzle-gen">PuzzleGen</a>. Thanks to `tdecker91`
for providing these!

## Documentation

The full documentation is available on 
[https://puzzle-generator.robiningelbrecht.be](https://puzzle-generator.robiningelbrecht.be)

## Some examples

### Default

```
https://puzzle-generator.robiningelbrecht.be/cube
```

![default](https://puzzle-generator.robiningelbrecht.be/cube)

### Scrambled

```
https://puzzle-generator.robiningelbrecht.be/cube?cube[algorithm]=M2 E2 S2
```

![scrambled](https://puzzle-generator.robiningelbrecht.be/cube?cube[algorithm]=M2%20E2%20S2)

## Development

Feel free to fork and make changes to your needs. Consider giving it a ⭐ when you do.

Clone repository

```bash
> git clone git@github.com:robiningelbrecht/puzzle-generator.git
```

Build Docker containers

```bash
> docker-compose up --build -d
```

Install dependencies

```bash
> docker-compose run --rm php-cli composer install
```

Navigate to `http://localhost:9090`
