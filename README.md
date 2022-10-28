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


## Examples


### Default

```
https://puzzle-generator.robiningelbrecht.be/cube
```

![mask](https://puzzle-generator.robiningelbrecht.be/cube)

## Documentation

<table>
<thead>
<tr>
<th>key</th>
<th>value range</th>
<th>default</th>
<th>description</th>
</tr>
</thead>
<tbody>
<tr>
<td>dist</td>
<td>1 to 100</td>
<td>5</td>
<td>Projection Distance</td>
</tr>
<tr>
<td>algorithm</td>
<td><code>([2-9]+)?([UuFfRrDdLlBbMESxyz])(w)?([2\'])?</code> (ex "R U R' U")</td>
<td></td>
<td>WCA cube notation.</td>
</tr>
<tr>
<td>case</td>
<td><code>([2-9]+)?([UuFfRrDdLlBbMESxyz])(w)?([2\'])?</code> (ex "R U R' U")</td>
<td></td>
<td>The system displays the cube state which is solved by applying the algorithm</td>
</tr>
<tr>
<td>backgroundColor</td>
<td>html color codes or names (ex. '#FFF' or 'white')</td>
<td></td>
<td></td>
</tr>
<tr>
<td>cubeColor</td>
<td>html color codes or names (ex. '#000' or 'black')</td>
<td>black</td>
<td>Color cube is drawn as</td>
</tr>
<tr>
<td>maskColor</td>
<td>html color codes or names (ex. '#000' or 'black')</td>
<td>#404040</td>
<td>Color masked stickers are drawn as</td>
</tr>
<tr>
<td>cubeSize</td>
<td>1 to 17</td>
<td>3</td>
<td>Values from N=(1 to 17) represent an NxNxN cube. Currently only regular cubes are modelled</td>
</tr>
<tr>
<td>cubeOpacity</td>
<td>0 to 100</td>
<td>100</td>
<td>Setting this value causes the base cube to be transparent. It means facelets at the back of the cube will also be rendered. A value of 0 gives complete transparency.</td>
</tr>
<tr>
<td>stickerOpacity</td>
<td>0 to 100</td>
<td>100</td>
<td>Setting this value causes the facelets to be rendered with transparency</td>
</tr>
<tr>
<td>colorScheme</td>
<td><code>{ [face: Face]: string }</code></td>
<td>U -&gt; yellow, R -&gt; red, F -&gt; blue, D-&gt; white, L -&gt; orange, B -&gt; green</td>
<td>Mapping from face to color. Color can be RGB hex value, or html color name. <code>Face</code> is an enum exported from the library. (ex. Face.U, Face.R etc..)</td>
</tr>
<tr>
<td>stickerColors</td>
<td>Array of colors (string value html color name or color code)</td>
<td></td>
<td>The order of the colors specified represent the faces in this order: U R F D L B Cube size determines how many definitions you need to fill the cube. A 3x3 cube will need 54 elements in the array.</td>
</tr>
<tr>
<td>facelets</td>
<td>Array of facelet (u,r,f,...)</td>
<td></td>
<td>Defines the cube state in terms of facelet positions. u stands for a 'U' facelet (and likewise with rfdlb). Defining a cube state using this method means the cube will be coloured according to the scheme defined by the sch variable. Three special characters are used to indicate the following:<br>n: This is a blank face (coloured grey)<br> o: This is an 'oriented' face (coloured silver)<br>t: This is a transparent face, and will not appear on the cube</td>
</tr>
<tr>
<td>viewportRotations</td>
<td><code>[Axis, number][]</code></td>
<td><code>[[Axis.Y, 45],[Axis.X, -34]]</code></td>
<td>Each entry in the sequence is an axis (x, y or z), followed by the number of degrees to rotate in a clockwise direction. Negative values are permitted. Any number of rotations is possible. <code>Axis</code> is an enum exported from the library containing values X, Y and Z</td>
</tr>
<tr>
<td>view</td>
<td>"plan"</td>
<td></td>
<td>The view parameter allows special views to facilitate interpretation of different case aspects. This will override any custom viewport rotations passed in.</td>
</tr>
<tr>
<td>width</td>
<td>whole numbers</td>
<td>128</td>
<td>Width the svg container will be</td>
</tr>
<tr>
<td>height</td>
<td>whole numbers</td>
<td>128</td>
<td>Height the svg container will be</td>
</tr>
<tr>
<td>mask</td>
<td><code>fl, f2l, ll, cll, ell, oll, ocll, oell, coll, ocell, wv, vh, els, cls, cmll, cross, f2l_3, f2l_2, f2l_sm, f2l_1, f2b, line</code></td>
<td></td>
<td>Sets parts of the cube to be masked from being colored. Stickers will be rendered gray, so image will focus particular stickers.</td>
</tr>
<tr>
<td>maskAlg</td>
<td><code>[UDLRFBudlrfbMESxyz'2 ]*</code></td>
<td></td>
<td>Commonly used to perform a rotation on the mask. For example, if you want the picture to highlight the cross on the right side, you can set the mask to <code>cross</code>, and the maskAlg to <code>z'</code> Mask alg will not affect underlying stiker values. The <code>algorithm</code> parameter will not effect the masking.</td>
</tr>
<tr>
<td>arrows</td>
<td><code>Arrow[]</code> OR Comma separated list in the form: <br><br> <code>&lt;a_from&gt;&lt;a_to&gt;(&lt;a_via&gt;)?(-i[0-9+])?(-s[0-9+])?(-&lt;color&gt;)?</code> <br><br> Where <code>&lt;a_x&gt;</code> is: <code>[URFDLB][0-N]+</code> <br><br> And: <code>&lt;color&gt;</code> is an html color code or color name.</td>
<td></td>
<td>Defines a list of arrows to be drawn on the cube. <br><br> You can either pass in an array of <code>Arrow</code>, or a string value supported by the original author's version. <br><br> Each arrow is defined with a minimum of two sticker identifiers to indicate where it should be drawn from and to. The optional third sticker identifier indicates which sticker it should pass through if a curved arrow is to be drawn. Arrows may be scaled so that they fall short, or past the centre of each facelet by specifying the s (scale) parameter after a dash. Where curved arrows are drawn the degree to which the arrow deviates from a straight path can be specified via the i (influence) parameter. Arrows may also optionally be assigned individual color, by using a - followed by a color code. <br><br> Example: <code>U0U2,U2U8,U8U0,R6R2R0-s8-i5-yellow</code></td>
</tr>
</tbody>
</table>

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
