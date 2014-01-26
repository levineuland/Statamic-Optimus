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
###Test Image

To demonstrate the capabilities of this plugin I've used a cropped version of the [Lenna](http://en.wikipedia.org/wiki/Lenna) standard test image. Below is the unmodified original for comparison.

![Original Image](http://levineuland.com/github/optimus-examples-lena/lena-original.jpg)

---
## Adjustments

These properties allow you to modify properties of the image before any effects or filters are added.


###Brightness
Increases or decreases the brightness of the image. Accepts values from -1 to 1.

`brightness=0.5`

![Brightness Example](http://levineuland.com/github/optimus-examples-lena/lena-brightness.jpg)


###Contrast
Increases or decreases the brightness of the image. Accepts values from -1 to 1.

`contrast=0.8`

![Contrast Example](http://levineuland.com/github/optimus-examples-lena/lena-contrast.jpg)

---
## Effects

These properties allow you to modify properties of the image before any effects or filters are added.


###Blur
Perform a gaussian blur on the image. Accepts values from 0 to 1.

`effect='blur' effect_strength='0.8'`

![Blur Example](http://levineuland.com/github/optimus-examples-lena/lena-blur.jpg)


###Pixelate
Pixelates the image. Accepts values from 0 to 1.

`effect='pixelate' effect_strength='0.3'`

![Contrast Example](http://levineuland.com/github/optimus-examples-lena/lena-pixelate.jpg)


###Greyscale
Desaturates the image. Adjusting the strength will increase the contrast of the effect.

`effect='greyscale' effect_strength='0.3'`

![Greyscale Example](http://levineuland.com/github/optimus-examples-lena/lena-greyscale.jpg)


###Sepia*
Adds a sepia tone to the image.

`effect='sepia' effect_strength='0.8'`

![Greyscale Example](http://levineuland.com/github/optimus-examples-lena/lena-sepia.jpg)


###Noir*
Modifies your image to have a film-noir effect.

`effect='noir'`

![Noir Example](http://levineuland.com/github/optimus-examples-lena/lena-noir.jpg)


###Nashville*
Modifies your image to have a sunny/southern effect.

`effect='nashville'`

![Nashville Example](http://levineuland.com/github/optimus-examples-lena/lena-nashville.jpg)


###Gotham*
Modifies your image to have a dark, brooding effect.

`effect='gotham'`

![Gotham Example](http://levineuland.com/github/optimus-examples-lena/lena-gotham.jpg)


###Lomo*
Modifies your image to have a plastic camera effect.

`effect='lomo'`

![Lomo Example](http://levineuland.com/github/optimus-examples-lena/lena-lomo.jpg)


###Toaster*
Modifies your image to have a smooth/warm effect.

`effect='toaster'`

![Toaster Example](http://levineuland.com/github/optimus-examples-lena/lena-toaster.jpg)


###Geocities*
Modifies your image to have a look reminiscent of the 90's.

`effect='geocities'`

![Geocities Example](http://levineuland.com/github/optimus-examples-lena/lena-geocities.jpg)

---
## Blend Modes

Apply a color and a blend mode to your image. Use the `blend_opacity` to modify the transparency of the color before the blend mode is applied. These methods are similar to effects found in Photoshop, GIMP, Pixelmator, and Acorn.


###Colorize
Applies a colorize blend mode to your image.

`blend_mode='colorize' blend_color='#FF0000' blend_opacity='1'`

![Colorize Example](http://levineuland.com/github/optimus-examples-lena/lena-colorize.jpg)


###Darken*
Applies a darken blend mode to your image

`blend_mode='darken' blend_color='#009CFF' blend_opacity='1'`

![Darken Example](http://levineuland.com/github/optimus-examples-lena/lena-darken.jpg)


###Multiply*
Applies a multiply blend mode to your image.

`blend_mode='multiply' blend_color='#FF0000' blend_opacity='1'`

![Multiply Example](http://levineuland.com/github/optimus-examples-lena/lena-multiply2.jpg)


###Color Burn*
Applies a color burn blend mode to your image.

`blend_mode='color_burn' blend_color='#00FFB4' blend_opacity='1'`

![Color Burn Example](http://levineuland.com/github/optimus-examples-lena/lena-colorburn.jpg)


###Screen*
Applies a screen blend mode to your image

`blend_mode='screen' blend_color='#FF0000' blend_opacity='1'`

![Screen Example](http://levineuland.com/github/optimus-examples-lena/lena-screen.jpg)


###Color Dodge*
Applies a color dodge blend mode to your image.

`blend_mode='color_dodge' blend_color='#6C6B06' blend_opacity='1'`

![Color Dodge Example](http://levineuland.com/github/optimus-examples-lena/lena-colordodge.jpg)


###Overlay*
Applies a overlay mode to your image

`blend_mode='overlay' blend_color='#FF0000' blend_opacity='1'`

![Overlay Example](http://levineuland.com/github/optimus-examples-lena/lena-overlay.jpg)


###Hard Light*
Applies a hard light blend mode to your image.

`blend_mode='hard_light' blend_color='#904545' blend_opacity='1'`

![Hard Light Example](http://levineuland.com/github/optimus-examples-lena/lena-hardlight.jpg)


###Difference*
Applies a difference blend mode to your image.

`blend_mode='difference' blend_color='#FF0000' blend_opacity='1'`

![Difference Example](http://levineuland.com/github/optimus-examples-lena/lena-difference.jpg)


###Hue*
Applies a hue blend mode to your image.

`blend_mode='hue' blend_color='#FF0000' blend_opacity='1'`

![Hue Example](http://levineuland.com/github/optimus-examples-lena/lena-hue.jpg)


###Exclusion*
Applies a exclusion blend mode to your image.

`blend_mode='exclusion' blend_color='#FF0000' blend_opacity='1'`

![Exclusion Example](http://levineuland.com/github/optimus-examples-lena/lena-exclusion.jpg)

---
## Cover Types

Apply varying cover layers to your image. This action happens at the end of the processing stack, allowing you to add a solid cover or gradient to the top of the image.


###Solid
Overlays a solid color on top of your image.

`cover_type='solid' cover_color='#000000' cover_opacity='0.5'`

![Solid Example](http://levineuland.com/github/optimus-examples-lena/lena-solid.jpg)


###Fade Out
Overlays a solid color on top of your image.

`cover_type='solid' cover_color='#000000' cover_opacity='1'`

![Solid Example](http://levineuland.com/github/optimus-examples-lena/lena-solid.jpg)


###Fade Out Multiply*
Overlays a vertical gradient with a multiply effect. Currently does not support the `cover_opacity` property.

`cover_type='solid' cover_color='#0000FF'`

![Solid Example](http://levineuland.com/github/optimus-examples-lena/lena-fadeoutmultiply.jpg)


###Fade Out Screen*
Overlays a vertical gradient with a screen effect. Currently does not support the `cover_opacity` property.

`cover_type='solid' cover_color='#0000FF'`

![Solid Example](http://levineuland.com/github/optimus-examples-lena/lena-fadeoutscreen.jpg)

---
## The End

If you like this plugin and want to see new features added please [star this repository](https://github.com/levineuland/Statamic-Optimus/star) to let me know you are interested. Have a feature or effect you'd like to see added? Send me a message on Twitter [@levineuland](http://twitter.com/levineuland/).


## Version History
1.0 - Official Release


## Author
Created by Levi Neuland. 
Twitter: [@levineuland](http://twitter.com/levineuland/)  
Website: [levineuland.com](http://levineuland.com) 
