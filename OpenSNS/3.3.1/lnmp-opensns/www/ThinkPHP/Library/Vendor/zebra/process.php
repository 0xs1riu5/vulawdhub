<?php

// if we have to generate a CAPTCHA image
if (isset($_GET['captcha']) && ($_GET['captcha'] == 1 || $_GET['captcha'] == 2)) {

    // storage method
    $storage = ($_GET['captcha'] == 2 ? 'session' : 'cookie');

    // if storage method is "session", start a session
    if ($storage == 'session') session_start();

    // as this file actually generates an image we set the headers accordingly
    header('Content-type:image/jpeg');

    $font_path = rtrim(implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), 0, -2)), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'includes/';

    // the number of characters to be used in the generated image
    $charactersNumber = 5;

    // spacing between characters (this can also be a negative number)
    // you should leave this as it is (unless you want to increase it)
    // decreasing this value may result in characters overlapping and being hardly distinguishable
    $characterSpacing = -2;

    // each character's size will be randomly selected from this range
    $fontSizeVariation = array(20, 40);

    // each character's angle will be randomly selected from this range
    // (remember to also change the character spacing if you change these to avoid character overlapping)
    $fontAngleVariation = array(-10, 10);

    // if you changed anything above, you should probably change this too si that all the characters fit into the image
    // and there's not too much of blank space
    $imageWidth = 140;

    // if you changed anything above, you should probably change this too si that all the characters fit into the image
    // and there's not too much of blank space
    $imageHeight = 50;

    // the quality, in percents, of the generated image
    $imageQuality = 70;

    // list of characters from which to choose
    // (notice that characters that can be (in some circumstances) confused with others, are missing)
    // you should not alter this setting
    $charList = 'bcdkmpsx345678';

    $captcha = array();

    $resultString = '';

    $totalWidth = 0;

    // this is the used font
    $font = $font_path . 'babelsans-bold.ttf';

    // first we figure out how much space the character would take
    for ($i = 0; $i < $charactersNumber; $i++) {

        // get a random character
        $char = $charList[rand(0, strlen($charList) - 1)];

        $resultString .= $char;

        // get a random size for the character
        $charSize = rand($fontSizeVariation[0], $fontSizeVariation[1]);

        // get a random angle for the character
        $charAngle = rand($fontAngleVariation[0], $fontAngleVariation[1]);

        // get the bounding box of the character
        $bbox = imagettfbbox($charSize, $charAngle, $font, $char);

        // resolve the returned measurements
        $bbox['left'] = abs(min($bbox[0], $bbox[2], $bbox[4], $bbox[6]));

		$bbox['top'] = abs(min($bbox[1], $bbox[3], $bbox[5], $bbox[7]));

		$bbox['width'] = max($bbox[0], $bbox[2], $bbox[4], $bbox[6]) -  min($bbox[0], $bbox[2], $bbox[4], $bbox[6]);

		$bbox['height'] = max($bbox[1], $bbox[3], $bbox[5], $bbox[7]) - min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);

        // this will be the total width of the random generated word
        $totalWidth += $bbox['width'] + $characterSpacing;

        // save info about the current character
        $captcha[] = array(

            'char'  =>  $char,
            'size'  =>  $charSize,
            'angle' =>  $charAngle,
            'box'   =>  $bbox

        );

    }

    // encode value
    $value = md5(md5(md5($resultString)));

    // if storage is "session", store the value in session
    if ($storage == 'session') $_SESSION['captcha'] = $value;

    // otherwise, store the value in a cookie
    else setcookie('captcha', $value, time() + 3600, '/');

    // either ways, the value will later be read by the form generator
    // and used to see if the user entered the correct characters

    // create the image
    $img = imagecreatetruecolor($imageWidth, $imageHeight);

    // allocate some colors
    $white = imagecolorallocate($img, 255, 255, 255);

    $black = imagecolorallocate($img, 0, 0, 0);

    // fill the canvas to white
    imagefilledrectangle($img, 0, 0, $imageWidth, $imageHeight, $white);

    // write some random characters in the background
    for ($i = 0; $i <10; $i++) {

        // ...having random washed-out colors
        $color = imagecolorallocate($img, rand(150, 200), rand(150, 200), rand(150, 200));

        imagettftext(
            $img,
            20,
            rand($fontAngleVariation[0],
            $fontAngleVariation[1]),
            rand(0, $imageWidth),
            rand(20, $imageHeight) ,
            $color,
            $font,
            chr(rand(65, 90))
        );

    }

    // draw a bounding rectangle
    // imagerectangle($img, 0, 0, $imageWidth - 1, $imageHeight - 1, $black);

    // this is to keep the word centered in the box
    $left = (($imageWidth - $totalWidth) / 2);

    // iterate through the chosen characters
    foreach ($captcha as $values) {

        // print each character
        imagettftext(
            $img,
            $values['size'],
            $values['angle'],
            $left ,
            ($imageHeight + $values['box']['height']) / 2 ,
            $black,
            $font,
            $values['char']
        );

        // compute the position of the next character
        $left += $values['box']['width'] + $characterSpacing;

    }

    // and finally output the image at the specified quality
    imagejpeg($img, null, $imageQuality);

    // free memory
    imagedestroy($img);

// if we're performing a file upload
} elseif (

    isset($_FILES) &&
    is_array($_FILES) &&
    !empty($_FILES) &&
    isset($_GET['form']) &&
    isset($_GET['control']) &&
    isset($_FILES[$_GET['control']])

) {

    function process() {

        // the form that initiated the request
        $form = $_GET['form'];

        // the file upload control on the form that initiated the request
        $control = $_GET['control'];

        // if file could be uploaded
        if (isset($_FILES[$_GET['control']])) {

            // save some information about the uploaded file
            $file['name'] = $_FILES[$control]['name'];
            $file['type'] = $_FILES[$control]['type'];
            $file['error'] = $_FILES[$control]['error'];
            $file['size'] = $_FILES[$control]['size'];

        // if there were problems uploading the file
        } elseif (empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && isset($_SERVER['CONTENT_TYPE']) && (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false || strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') !== false)) {

            // send these values
            $file['name'] = '';
            $file['type'] = 'unknown';
            $file['error'] = 1;
            $file['size'] = $_SERVER['CONTENT_LENGTH'];
        }

        ob_start();

        ?>

        <script type="text/javascript">
            var f=parent.window.$('#<?php echo $form?>');
            if(undefined!=f){
                f.data('Zebra_Form').end_file_upload('<?php echo $control . '\'' . (isset($file) ? ',[\'' . addcslashes($file['name'], '\'') . '\',\'' . $file['type'] . '\',\'' . $file['error'] . '\',\'' . $file['size'] . '\']' : '')?>)
            }
        </script>

        <?php

        $contents = ob_get_contents();

        ob_end_clean();

        $patterns = array(
            '/^\s*/m',
            '/\r/m',
            '/\n/m',
            '/\r\n/m',
        );

        $replacements = array(
            '',
        );

        echo preg_replace($patterns, $replacements, $contents);

    }

    register_shutdown_function('process');

}

?>
