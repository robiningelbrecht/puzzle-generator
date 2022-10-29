<h1 align="center">Puzzle generator</h1>

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

<h2 id="examples">Examples</h2>

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

### Cube size

```
https://puzzle-generator.robiningelbrecht.be/cube?cube[size]=6
```

![cube size](https://puzzle-generator.robiningelbrecht.be/cube?cube[size]=6)

### Rotations

```
https://puzzle-generator.robiningelbrecht.be/cube?rotations[0][axis]=y&rotations[0][value]=120&rotations[1][axis]=x&rotations[1][value]=120
```

![rotations](https://puzzle-generator.robiningelbrecht.be/cube?rotations[0][axis]=y&rotations[0][value]=120&rotations[1][axis]=x&rotations[1][value]=120)

### View from top

```
https://puzzle-generator.robiningelbrecht.be/cube?view=top
```

![view from top](https://puzzle-generator.robiningelbrecht.be/cube?view=top)

### View as net

```
https://puzzle-generator.robiningelbrecht.be/cube?view=net
```

![view as net](https://puzzle-generator.robiningelbrecht.be/cube?view=net)

### Combination

```
https://puzzle-generator.robiningelbrecht.be/cube?size=250&backgroundColor=cccccc&cube[size]=4&cube[algorithm]=R U D2
```

![view as net](https://puzzle-generator.robiningelbrecht.be/cube?size=250&backgroundColor=cccccc&cube[size]=4&cube[algorithm]=R%20U%20D2)

## <h2 id="documentation">Documentation</h2>

<table>
    <thead>
        <tr>
            <th>Param</th>
            <th>Default</th>
            <th>Valid range</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>size</td>
            <td>128</td>
            <td>1 - 1024</td>
            <td>The width and the height of the SVG container</td>
        </tr>
        <tr>
            <td>backgroundColor</td>
            <td>transparent</td>
            <td>HTML hex color codes (ex. '#FFFFFF') or transparent</td>
            <td>The background color of the SVG container</td>
        </tr>
       <tr>
            <td>rotations</td>
            <td>
                <pre>
<code>
[ 'axis': 'y','value': 45 ], 
[ 'axis': 'x','value': -34 ]
</code>
                </pre>
            </td>
            <td>x | y | z with any valid degree</td>
            <td>
                The angle the puzzle is viewed at can be adjusted by passing in rotations. 
                They are a list of angle rotations to perform on the puzzle before rendering. 
                They can be adjusted to get the perfect view of the puzzle for your purposes.
            </td>
        </tr>
       <tr>
            <td>view</td>
            <td>3D</td>
            <td>3D | top | net</td>
            <td>
                The view in which the cube will be rendered. 
                Using <kbd>top</kbd> or <kbd>net</kbd>  will override any custom viewport rotations passed in.
            </td>
        </tr>
    </tbody>
</table>

## <h2 id="development">Development</h2>

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
