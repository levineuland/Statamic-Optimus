![Optimus Plugin for Statamic](http://levineuland.com/github/optimus-header.jpg)

Optimus is an enhanced version of the Statamic transform tag, adding additional rendering effects to make your images as flexible as possible.

**What it does:**

* Dynamically adjust brightness and contrast
* Adds blend modes and filter effects to your images
* Add solid color or gradient overlays
* Fully compatible with existing Transform tags

**Requirements:**

* Statamic v1.6.7+
* IMagick PHP Module v3.1.0+ (Note: Only required for effects and blend modes marked with __*__)
  
---
## Basic Installation

1. Clone the repo into the `_addons` directory of your Statamic site  
2. Insert a `{{ optimus }}` tag into a page on your site
3. Add a `src` atrribute with a path to your image and use any of the modifiers below

**Warning:** Complex image processing can increase the load time for your website. This plugin leverages the same approach as the Transform plugin, so after the first time you see the image it will not attempt to regenerate it.


---
## Adjustments

These properties allow you to modify properties of the image before any effects or filters are added.


###Brightness
Increases or decreases the brightness of the image. Accepts values from -1 to 1.

`brightness=1.0`

![Brightness Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Contrast
Increases or decreases the brightness of the image. Accepts values from -1 to 1.

`contrast=1.0`

![Contrast Example](http://levineuland.com/github/lena-placeholder-long.jpg)

---
## Effects

These properties allow you to modify properties of the image before any effects or filters are added.


###Blur
Perform a gaussian blur on the image. Accepts values from 0 to 1.

`effect='blur' effect_strength='0.8'`

![Blur Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Pixelate
Pixelates the image. Accepts values from 0 to 1.

`effect='pixelate' effect_strength='0.3'`

![Contrast Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Greyscale
Desaturates the image. Adjusting the strength will increase the contrast of the effect.

`effect='greyscale' effect_strength='0.3'`

![Greyscale Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Sepia*
Adds a sepia tone to the image.

`effect='sepia' effect_strength='0.8'`

![Greyscale Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Noir*
Modifies your image to have a film-noir effect.

`effect='sepia'`

![Noir Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Nashville*
Modifies your image to have a sunny/southern effect.

`effect='nashville'`

![Nashville Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Gotham*
Modifies your image to have a dark, brooding effect.

`effect='gotham'`

![Gotham Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Lomo*
Modifies your image to have a plastic camera effect.

`effect='lomo'`

![Lomo Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Toaster*
Modifies your image to have a smooth/warm effect.

`effect='toaster'`

![Toaster Example](http://levineuland.com/github/lena-placeholder-long.jpg)


###Geocities*
Modifies your image to have a look reminiscent of the 90's.

`effect='geocities'`

![Geocities Example](http://levineuland.com/github/lena-placeholder-long.jpg)

---
## Blend Modes

These properties allow you to modify properties of the image before any effects or filters are added.


###Colorize
Perform a gaussian blur on the image. Accepts values from 0 to 1.

`effect='blur' effect_strength='0.8'`

![Blur Example](http://levineuland.com/github/lena-placeholder-long.jpg)

---
## Cover Types

These properties allow you to modify properties of the image before any effects or filters are added.


###Solid
Overlays a solid color on top of your image.

`cover_type='solid' cover_color='#000000' cover_opacity='1'`

![Blur Example](http://levineuland.com/github/lena-placeholder-long.jpg)

---

## Version History
1.0 - Official Release


## Author
Created by Levi Neuland. Have a question or suggestion? Feel free to reach out.  
Twitter: [@levineuland](http://twitter.com/levineuland/)  
Website: [levineuland.com](http://levineuland.com) 
