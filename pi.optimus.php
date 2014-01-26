<?php

use Intervention\Image\Image;

/**
 * Plugin_optimus
 * Enhancement of the original transform plugin.
 * @author  Levi Neuland <say@hellolevi.com>
 *
 * @link    https://github.com/levineuland/Statamic-Optimus/
 *
 *
 * Transform plugin originally authored by:
 * @author  Jack McDade <jack@statamic.com>
 * @author  Fred LeBlanc <fred@statamic.com>
 * @author  Mubashar Iqbal <mubs@statamic.com>
 *
 */

class Plugin_optimus extends Plugin
{

    public $meta = array(
        'name'       => 'Optimus',
        'version'    => '1.0',
        'author'     => 'Levi Neuland',
        'author_url' => 'http://levineuland.com'
    );
    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | Check for image
        |--------------------------------------------------------------------------
        |
        | Transform just needs the path to an image to get started. If it exists,
        | the fun begins.
        |
        */

        $image_src = $this->fetchParam('src', null, false, false, false);

        // Set full system path
        $image_path = Path::tidy(BASE_PATH . '/' . $image_src);

        // Check if image exists before doing anything.
        if ( ! File::isImage($image_path)) {

            Log::error("Could not find requested image to transform: " . $image_path, "core", "Optimus");

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Resizing and cropping options
        |--------------------------------------------------------------------------
        |
        | The first transformations we want to run is for size to reduce the
        | memory usage for future effects.
        |
        */

        $width  = $this->fetchParam('width', null, 'is_numeric');
        $height = $this->fetchParam('height', null, 'is_numeric');

        // resize specific
        $ratio  = $this->fetchParam('ratio', true, false, true);
        $upsize = $this->fetchParam('upsize', true, false, true);

        // crop specific
        $pos_x  = $this->fetchParam('pos_x', 0, 'is_numeric');
        $pos_y  = $this->fetchParam('pos_y', 0, 'is_numeric');

        $quality = $this->fetchParam('quality', '75', 'is_numeric');


        /*
        |--------------------------------------------------------------------------
        | Action
        |--------------------------------------------------------------------------
        |
        | Available actions: resize, crop, and guess.
        |
        | "Guess" will find the best fitting aspect ratio of your given width and
        | height on the current image automatically, cut it out and resize it to
        | the given dimension.
        |
        */

        $action = $this->fetchParam('action', 'resize');


        /*
        |--------------------------------------------------------------------------
        | Extra bits
        |--------------------------------------------------------------------------
        |
        | Delicious and probably rarely used options.
        |
        */

        $angle     = $this->fetchParam('rotate', false);
        $flip_side = $this->fetchParam('flip' , false);
        $blur      = $this->fetchParam('blur', false, 'is_numeric');
        $pixelate  = $this->fetchParam('pixelate', false, 'is_numeric');

        $grayscale = $this->fetchParam('grayscale', false, false, true); // Silly brits
        $greyscale = $this->fetchParam('greyscale', $grayscale, false, true);

        $blend_mode  = $this->fetchParam('blend_mode', false);
        $blend_opacity  = $this->fetchParam('blend_opacity', 1, 'is_numeric');
        $blend_color = $this->fetchParam('blend_color', false);
        $effect = $this->fetchParam('effect', false);
        $effect_strength = $this->fetchParam('effect_strength', 1, 'is_numeric');
        $cover_type = $this->fetchParam('cover_type', false);
        $cover_color = $this->fetchParam('cover_color', false);
        $cover_opacity  = $this->fetchParam('cover_opacity', 1, 'is_numeric');
        $brightness = $this->fetchParam('brightness', false, 'is_numeric');
        $contrast = $this->fetchParam('contrast', false, 'is_numeric');

        $blend_mode = $blend_mode === 'none' ? false : $blend_mode;
        $effect = $effect === 'none' ? false : $effect;
        $cover_type = $cover_type === 'none' ? false : $cover_type;


        if ($effect === 'gif'){
            return File::cleanURL($image_path);
        }

        /*
        |--------------------------------------------------------------------------
        | Assemble filename and check for duplicate
        |--------------------------------------------------------------------------
        |
        | We need to make sure we don't already have this image created, so we
        | defer any action until we've processed the parameters, which create
        | a unique filename.
        |
        */

        // Late modified time of original image
        $last_modified = File::getLastModified($image_path);

        // Find .jpg, .png, etc
        $extension = File::getExtension($image_path);

        // Filename with the extension removed so we can append our unique filename flags
        $stripped_image_path = str_replace('.' . $extension, '', $image_path);


        //Local to check for existence for Imagick
        $has_imagick = false;

        if(class_exists("Imagick")){
            $imagick_effects = array(
                'darken'      => Imagick::COMPOSITE_DARKEN,
                'multiply'    => Imagick::COMPOSITE_MULTIPLY,
                'color_burn'  => Imagick::COMPOSITE_COLORBURN,
                'lighten'     => Imagick::COMPOSITE_LIGHTEN,
                'screen'      => Imagick::COMPOSITE_SCREEN,
                'color_dodge' => Imagick::COMPOSITE_COLORDODGE,
                'linear_dodge'=> Imagick::COMPOSITE_ADD,
                'overlay'     => Imagick::COMPOSITE_OVERLAY,
                'hard_light'  => Imagick::COMPOSITE_HARDLIGHT,
                'difference'  => Imagick::COMPOSITE_DIFFERENCE,
                'hue'         => Imagick::COMPOSITE_HUE,
                'saturation'  => Imagick::COMPOSITE_SATURATE,
                'exclusion'   => Imagick::COMPOSITE_EXCLUSION,
                'fade_out_multiply'    => 'fade_out_multiply',
                'fade_out_screen'    => 'fade_out_screen',
                'plasma_out'     => 'plasma_out',
                'nashville'   => 'nashville',
                'noir'        => 'noir',
                'gotham'      => 'gotham',
                'geocities'   => 'geocities',
                'sepia'       => 'sepia',
                'toaster'     => 'toaster',
                'lomo'        => 'lomo'
            );
            $has_imagick = true;
        }

        // The possible filename flags
        $parameter_flags = array(
            'width'     => $width,
            'height'    => $height,
            'quality'   => $quality,
            'rotate'    => $angle,
            'flip'      => $flip_side,
            'pos_x'     => $pos_x,
            'pos_y'     => $pos_y,
            'blur'      => $blur,
            'pixelate'  => $pixelate,
            'greyscale' => $greyscale,
            'blend_mode' => $blend_mode,
            'blend_opacity' => $blend_opacity,
            'blend_color'=> str_replace('#','',$blend_color),
            'cover_type' => $cover_type,
            'cover_color' =>  str_replace('#','',$cover_color),
            'cover_opacity' => $cover_opacity,
            'effect'    => $effect,
            'effect_strength' => $effect_strength,
            'brightness' => $brightness,
            'contrast' => $contrast,
            'modified'  => $last_modified
        );
        /*
        if ($postprocess !== '' && $postprocess !== null && class_exists("Imagick")) {
            $paramater_flags['postprocess'] = $postprocess;
        }
        */
        // Start with a 1 character action flag
        $file_breadcrumbs = '-'.$action[0];

        foreach ($parameter_flags as $param => $value) {
            if ($value) {
                $flag = is_bool($value) ? '' : $value; // don't show boolean flags
                $file_breadcrumbs .= '-' . $param[0] . $flag;
            }
        }

        // Allow converting filetypes (jpg, png, gif)
        $extension = $this->fetchParam('type', $extension);

        // Allow saving in a different directory
        $destination = $this->fetchParam('destination', Config::get('transform_destination', false), false, false, false);


        if ($destination) {

            $destination = Path::tidy(BASE_PATH . '/' . $destination);

            // Method checks to see if folder exists before creating it
            Folder::make($destination);

            $stripped_image_path = Path::tidy($destination . '/' . basename($stripped_image_path));
        }

        // Reassembled filename with all flags filtered and delimited
        $new_image_path = $stripped_image_path . $file_breadcrumbs . '.' . $extension;

        // Check if we've already built this image before
        if (File::exists($new_image_path)) {

            return File::cleanURL($new_image_path);
        }

        /*
        |--------------------------------------------------------------------------
        | Initialize Values
        |--------------------------------------------------------------------------
        |
        | Setup the RGBA values that have been passed in.
        |
        */

        $blend_r = 0;
        $blend_g = 0;
        $blend_b = 0;
        $blend_a = $blend_opacity;

        if ($blend_color) {
           if (preg_match('/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i', $blend_color, $matches)) {
                $blend_r = strlen($matches[1]) == '1' ? '0x'.$matches[1].$matches[1] : '0x'.$matches[1];
                $blend_g = strlen($matches[2]) == '1' ? '0x'.$matches[2].$matches[2] : '0x'.$matches[2];
                $blend_b = strlen($matches[3]) == '1' ? '0x'.$matches[3].$matches[3] : '0x'.$matches[3];
            } elseif (preg_match('/^rgb ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3})\)$/i', $blend_color, $matches)) {
                $blend_r = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
                $blend_g = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
                $blend_b = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            }
        }
        $cover_r = 0;
        $cover_g = 0;
        $cover_b = 0;
        $cover_a = $cover_opacity;

        if ($cover_color) {
           if (preg_match('/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i', $cover_color, $matches)) {
                $cover_r = strlen($matches[1]) == '1' ? '0x'.$matches[1].$matches[1] : '0x'.$matches[1];
                $cover_g = strlen($matches[2]) == '1' ? '0x'.$matches[2].$matches[2] : '0x'.$matches[2];
                $cover_b = strlen($matches[3]) == '1' ? '0x'.$matches[3].$matches[3] : '0x'.$matches[3];
            } elseif (preg_match('/^rgb ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3})\)$/i', $cover_color, $matches)) {
                $cover_r = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
                $cover_g = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
                $cover_b = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Image
        |--------------------------------------------------------------------------
        |
        | Transform just needs the path to an image to get started. The image is
        | created in memory so we can start manipulating it.
        |
        */

        $image = Image::make($image_path);


        /*
        |--------------------------------------------------------------------------
        | Perform Actions
        |--------------------------------------------------------------------------
        |
        | This is fresh transformation. Time to work the magic!
        |
        */



        if ($action === 'resize' && ($width || $height) ) {
            $image->resize($width, $height, $ratio, $upsize);
        }

        if ($action === 'crop' && $width && $height) {
            $image->crop($width, $height, $pos_x, $pos_y);
        }

        if ($action === 'smart') {
            $image->grab($width, $height);
        }

        if ($angle) {
            $image->rotate($angle);
        }

        if ($flip_side === 'h' || $flip_side === 'v') {
            $image->flip($flip_side);
        }

        //Adjust brightness/contrast prior to any other adjustments

        if ($contrast) {
            $cont = -10*$contrast;
            imagefilter($image->resource, IMG_FILTER_CONTRAST, $cont);
        }

        if ($brightness) {
            imagefilter($image->resource, IMG_FILTER_BRIGHTNESS, $brightness*100);
        }

        //If we don't have a defined height or width, define them
        
        if ($width == null) { $width = $image->width;  }
        if ($height == null) { $height = $image->height;  }

        //Initialize IMagick objects
        if( $has_imagick && ($blend_mode || $effect || $cover_type)) {
            $ppimage = new Imagick();
            $zdata = $this->getImageStringData($image);
            $ppimage->readImageBlob($zdata);
            
            $quantum_range = $ppimage->getQuantumRange();
            $max_quantum = $quantum_range["quantumRangeLong"];
        }

        //Perform IMagick Effects
        if ( $has_imagick && $effect && array_key_exists($effect, $imagick_effects)) {
                $update = false;
                switch ($effect) {
                    case 'gotham':
                         $ppimage->modulateImage(120,10,100);
                         $ppimage->colorizeImage('#8186a7',1);
                         $ppimage->gammaImage(0.65);
                         $this->setContrast($ppimage, 7);
                         $layerOverlay = $this->getPanel('#080f1d',1,$width,$height);
                         $ppimage->compositeImage($layerOverlay, Imagick::COMPOSITE_LIGHTEN, 0, 0);
                         $update = true;
                    break;
                    case 'lomo':
                        $black_point = $max_quantum * .33;
                        $white_point = $max_quantum - $black_point;
                        $gamma = 1.0;
                        $ppimage->levelImage($black_point, $gamma, $white_point, Imagick::CHANNEL_RED);
                        $ppimage->levelImage($black_point, $gamma, $white_point, Imagick::CHANNEL_GREEN);
                        $ppimage->setimagebackgroundcolor('black'); 
                        //$ppimage->vignetteImage(100,50,0,0);
                        $this->addVignette($ppimage,'#000000',100);
                        $update = true;
                    break;
                    case 'toaster':
                        $ppimage->modulateImage(150,80,100);
                        $ppimage->gammaImage(1.2);

                        $this->setContrast($ppimage, 2);

                        $layerOverlay = new ImagickDraw();
                        $layerOverlay = $this->getPanel('#330000',1,$width,$height);
                        $ppimage->compositeImage($layerOverlay, Imagick::COMPOSITE_SCREEN, 0, 0);
                        $layerOverlay = $this->getRadialGradient('#9b8766',$width,$height);
                        $ppimage->compositeImage($layerOverlay, Imagick::COMPOSITE_MULTIPLY, 0, 0); 
                        //$this->addVignette($ppimage,'#ff9966',20);
                        $update = true;

                    break;
                    case 'sepia':
                        $ppimage->sepiaToneImage( 100 * $effect_strength ); 
                        $update = true;
                    break;
                    case 'noir':
                        $ppimage->normalizeImage();
                        $ppimage->modulateImage(110,1,100);

                        $this->setContrast($ppimage, 4);

                        $ppimage->adaptiveBlurImage(3, 3);
                        $this->addVignette($ppimage,'#000000',20);
                        $update = true;
                    break;
                    case 'nashville':
                        //Brightness, Saturation, Hue
                        $ppimage->modulateImage(140,100,100);
                        $ppimage->sigmoidalContrastImage ( 0, 2, 30, Imagick::CHANNEL_ALL);

                        $layerOverlay = $this->getPanel('#222b6d',0.9,$width,$height);
                        $ppimage->compositeImage($layerOverlay, Imagick::COMPOSITE_LIGHTEN, 0, 0); 
                        
                        $ppimage->sigmoidalContrastImage( 0, 3, 30, Imagick::CHANNEL_YELLOW);


                        $layerOverlay = $this->getPanel('#283591',0.5,$width,$height);
                        $ppimage->compositeImage($layerOverlay, Imagick::COMPOSITE_DIFFERENCE, 0, 0); 
                        $update = true;
                    break;
                    case 'geocities':
                        $ppimage->colorizeImage('#a3a476',0.1);

                        $layerOverlay = new Imagick();
                        $layerOverlay->readImageBlob($zdata);
                        $ppimage->compositeImage($layerOverlay, Imagick::COMPOSITE_SCREEN, 0, 0); 

                        
                        $ppimage->adaptiveThresholdImage(150,150,2000); 
                        $this->setContrast($ppimage, 5);
                        $update = true;
                    break;
                    case 'kelvin':

                    break;
                }
            if ($update) {
                $image->resource = imagecreatefromstring($ppimage->getImageBlob());
            }
        }



        //Check for standard Transform tag Effects

        if ($greyscale || $effect === 'greyscale') {
            $image->greyscale();
            if ($effect === 'greyscale' && $effect_strength != '' && $effect_strength > 0){
                $cont = -10*$effect_strength;
                imagefilter($image->resource, IMG_FILTER_CONTRAST, $cont);
            }
        }

        if ($pixelate) {
            $image->pixelate($pixelate);
        }

        if ($effect === 'pixelate') {
            $image->pixelate($effect_strength*40);
        }

        if ($blur) {
            $image->blur($blur);
        }
        if ($effect === 'blur') {
            $image->blur($effect_strength*20);
        }


       //Check for additonal blend modes
        if($blend_mode === 'colorize' && $blend_color) {
            imagefilter($image->resource, IMG_FILTER_COLORIZE, $blend_r, $blend_g, $blend_b, (127-($blend_opacity*127)));
        }

        //Check for available IMagick blends
        if ($has_imagick && ($blend_mode || $cover_type)){

            $zdata = $this->getImageStringData($image);
            $ppimage->readImageBlob($zdata);
            $cover = new Imagick();
            $update = false;

            if ($blend_mode && array_key_exists($blend_mode, $imagick_effects)){
                if ($imagick_effects[$blend_mode] !== '' && $imagick_effects[$blend_mode] !== null && !is_string($imagick_effects[$blend_mode])){
                    $cover = $this->getPanel($blend_color,$blend_opacity,$width,$height);
                    $ppimage->compositeImage($cover, $imagick_effects[$blend_mode], 0, 0); 
                    $update = true;
                }
            }
            if ($cover_type && array_key_exists($cover_type, $imagick_effects)) {
                $cover = new Imagick();
                if ($cover_type == 'fade_out_multiply') {
                    $cover->newPseudoImage($width, $height, "gradient:".$cover_color."-transparent");
                    $ppimage->compositeImage($cover, Imagick::COMPOSITE_MULTIPLY, 0, 0); 
                    $update = true;
                }else if ($cover_type == 'fade_out_screen') {
                    $cover->newPseudoImage($width, $height, "gradient:".$cover_color."-transparent");
                    $ppimage->compositeImage($cover, Imagick::COMPOSITE_SCREEN, 0, 0); 
                    $update = true;
                } else if($cover_type == 'plasma_out'){
                    $cover->newPseudoImage($width, $height, "plasma:".$cover_color."-transparent");
                    $ppimage->compositeImage($cover, Imagick::COMPOSITE_MULTIPLY, 0, 0);  
                    $update = true;
                }
            }
            if ($update){
                $image->resource = imagecreatefromstring($ppimage->getImageBlob());
            }
        }


        if ($cover_type === 'solid' && $cover_color) {
            $alpha = 127 * $cover_opacity;
            $alpha = 127-$alpha;
            $cover = imagecreatetruecolor(30, 30);

            $color = imagecolorallocatealpha($cover, $cover_r, $cover_g, $cover_b, $alpha);
            imagefill($cover, 0, 0, $color);
            imagesavealpha($cover, TRUE);
            imagesettile($image->resource, $cover);
            imagefilledrectangle($image->resource, 0, 0, $image->width, $image->height, IMG_COLOR_TILED);
        }
        if ($cover_type === 'fade_out' && $cover_color) {
            $input = $image->resource;
            $output = imagecreatetruecolor($width, $height);

            $startpoint = 0 + (127-(127*$cover_opacity));
            $endpoint = 127;
            $steps = ($endpoint-$startpoint)/$height;

            $trans_color = imagecolorallocatealpha($output, 0, 0, 0, 127);
            imagefill($output, 0, 0, $trans_color);
            for ($y=0; $y < $height; ++$y) {
                $alpha = ceil(($y*$steps)+$startpoint);
                $new_color = imagecolorallocatealpha($output, $cover_r, $cover_g, $cover_b, $alpha);
                imageline($output, 0, $y, $width, $y, $new_color);
            }
            imagecopyresampled($input, $output, 0, 0, 0, 0, $width, $height, $width, $height);
            $image->resource = $input;
        }


        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        |
        | Get out of dodge!
        |
        */

        $image->save($new_image_path, $quality);
        return File::cleanURL($new_image_path);
    }
    private function getPanel($col,$op,$w,$h) {
        $draw = new ImagickDraw();
        $draw->setFillColor($col);
        $draw->setFillAlpha($op);
        $draw->rectangle(0, 0, $w, $h);

        $layerOverlay = new Imagick();
        $layerOverlay->newImage($w,$h, new ImagickPixel('none'));

        $layerOverlay->drawImage($draw);
        return $layerOverlay;
    }
    private function setContrast($obj, $amount) {
        for ($z=0; $z<$amount; $z++) {
            $obj->contrastImage(1);
        }
    }
    private function getRadialGradient($col,$w,$h) {
        $layerOverlay = new Imagick();
        echo('width '. $w);
        $layerOverlay->newPseudoImage( $w, $h, "radial-gradient:transparent-".$col );
        return $layerOverlay;
    }
    private function addVignette($obj,$col,$str) {
        $str = 200-$str;
        $obj->setimagebackgroundcolor($col); 
        $obj->vignetteImage($str,($str/2),0,0);
    }
    private function getImageStringData($img){
        ob_clean();
        ob_start();
        imagepng($img->resource);
        $stringdata = ob_get_contents(); 
        ob_end_clean();
        return $stringdata;
    }
}