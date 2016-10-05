<?php

class RecallsModel extends BaseModel
{


    public function getRecalls($getNotApproved = false, $sort = 'date')
    {

        $query = 'SELECT * FROM recalls';
        if (!$getNotApproved) {
            $query .= ' WHERE is_approved = 1';
        }

        if ($sort == 'name') {
            $field = 'name';
        } else {
            if ($field = 'email') {
                $field = 'email';
            } else {
                $field = 'id';
            }
        }
        $query .= ' ORDER BY ' . $field . ' DESC';

        $res = $this->db->query($query);
        $result = [];
        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }

        return $result;
    }


    public function addRecall($data, $file)
    {
        $name = $this->getString($data, 'name');
        $email = $this->getString($data, 'email');
        $text = $this->getString($data, 'text');

        if (empty($name) || empty($email) || empty($text)) {
            return false;
        }

        $photo = '';
        if (!empty($file)) {
            $photo = $this->uploadFile($file);
            if (!$photo) {
                return false;
            }
        }

        $query = 'INSERT INTO recalls(`name`, `email`, `text`, `photo_path`) VALUES ("' .
            $name . '", "' .
            $email . '", "' .
            $text . '", "' .
            $photo . '")';

        if ($this->db->query($query)) {
            return true;
        }

        return false;
    }

    private function generateRandomString($length = 15)
    {
        return substr(sha1(rand()), 0, $length);
    }

    function smartResizeImage(
        $file,
        $string = null,
        $width = 0,
        $height = 0,
        $proportional = false,
        $output = 'file',
        $delete_original = true,
        $use_linux_commands = false,
        $quality = 100
    ) {

        if ($height <= 0 && $width <= 0) {
            return false;
        }
        if ($file === null && $string === null) {
            return false;
        }

        # Setting defaults and meta
        $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
        $image = '';
        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;
        $cropHeight = $cropWidth = 0;

        # Calculating proportionality
        if ($proportional) {
            if ($width == 0) {
                $factor = $height / $height_old;
            } elseif ($height == 0) {
                $factor = $width / $width_old;
            } else {
                $factor = min($width / $width_old, $height / $height_old);
            }

            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        } else {
            $final_width = ($width <= 0) ? $width_old : $width;
            $final_height = ($height <= 0) ? $height_old : $height;
            $widthX = $width_old / $width;
            $heightX = $height_old / $height;

            $x = min($widthX, $heightX);
            $cropWidth = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
        }

        # Loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
                break;
            case IMAGETYPE_GIF:
                $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
                break;
            case IMAGETYPE_PNG:
                $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
                break;
            default:
                return false;
        }


        # This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor($final_width, $final_height);
        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $transparency = imagecolortransparent($image);
            $palletsize = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {
                $transparent_color = imagecolorsforindex($image, $transparency);
                $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            } elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }
        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);


        # Taking care of original, if needed
        if ($delete_original) {
            if ($use_linux_commands) {
                exec('rm ' . $file);
            } else {
                @unlink($file);
            }
        }

        # Preparing a method of providing result
        switch (strtolower($output)) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = null;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        # Writing image according to type to the output destination and image quality
        switch ($info[2]) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, $output);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, $output, $quality);
                break;
            case IMAGETYPE_PNG:
                $quality = 9 - (int)((0.9 * $quality) / 10.0);
                imagepng($image_resized, $output, $quality);
                break;
            default:
                return false;
        }

        return true;
    }


    private function uploadFile($file)
    {

        $file = $this->getValue($file, 'file');
        if (!$file) {
            return false;
        }

        $tmpName = $this->getString($file, 'tmp_name');
        $ext = $this->getString($file, 'type');

        $allowed = [
            'image/jpeg' => 'jpeg',
            'image/gif' => 'gif',
            'image/png' => 'png'
        ];

        if (!$tmpName || !array_key_exists($ext, $allowed)) {
            return false;
        }

        $uploadFile = Application::getApplication()->getPath() .
            Application::getApplication()->getConfig('upload_dir');
        $filename = $this->generateRandomString(15) . '.' . $allowed[$ext];
        $uploadFile .= $filename;


        if (move_uploaded_file($tmpName, $uploadFile)) {
            $this->smartResizeImage($uploadFile, null, 320, 240);
            return $filename;
        } else {
            return false;
        }

    }

    public function publish($data, $value)
    {
        $id = $this->getInt($data, 'id');
        if (!$id) {
            return false;
        }

        $query = 'UPDATE recalls SET `is_approved` = ' . $value . ' WHERE id = ' . $id;
        if (!$this->db->query($query)) {
            return false;
        }

        return true;
    }

    public function getById($data)
    {
        $id = $this->getInt($data, 'id');
        if (!$id) {
            return null;
        }

        $query = 'SELECT * FROM recalls WHERE id =' . $id;
        $res = $this->db->query($query);

        $raw = $res->fetch_assoc();
        if (!$raw) {
            return null;
        }

        return $raw;
    }

    public function update($data)
    {
        $name = $this->getString($data, 'name');
        $email = $this->getString($data, 'email');
        $text = $this->getString($data, 'text');
        $id = $this->getInt($data, 'id');

        if (empty($name) || empty($email) || empty($text)) {
            return false;
        }

        $query = 'UPDATE recalls SET `is_moderated` = 1, `name`="' .
            $name . '", `email` = "' . $email . '", `text` = "' . $text . '" WHERE id = ' . $id;

        if (!$this->db->query($query)) {
            return false;
        }

        return true;
    }

    public function migrate()
    {
        $query = "
                CREATE TABLE IF NOT EXISTS `recalls` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(256) COLLATE utf8_bin NOT NULL,
          `email` varchar(256) COLLATE utf8_bin NOT NULL,
          `photo_path` varchar(256) COLLATE utf8_bin DEFAULT NULL,
          `is_approved` tinyint(4) NOT NULL DEFAULT '0',
          `is_moderated` tinyint(4) NOT NULL DEFAULT '0',
          `text` text COLLATE utf8_bin NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1";
        if (!$this->db->query($query)) {
            return false;
        }
        $query = "CREATE TABLE IF NOT EXISTS `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(256) COLLATE utf8_bin NOT NULL,
          `password` varchar(256) COLLATE utf8_bin NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1";


        if (!$this->db->query($query)) {
            return false;
        }

        $query = "INSERT INTO `users` (`id`, `name`, `password`) VALUES
                    (1, 'admin', '202cb962ac59075b964b07152d234b70');";
        if (!$this->db->query($query)) {
            return false;
        }

        return true;
    }


}