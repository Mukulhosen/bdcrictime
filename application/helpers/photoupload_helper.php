<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function photo_upload($image, $post_id, array $sizes = []) {
    $handle = new upload($image);
    if ($handle->uploaded) {
        $photo = [];
        //$sizes = array(100, 200, 300, 400, 500);
        foreach ($sizes as $key => $value) {
            $photo[] = multiple_upload($handle, $post_id, $value);
        }
    }
    return $photo;
}

function multiple_upload($handle, $post_id, $size) {
    $handle->file_name_body_pre = $post_id;
    $handle->file_new_name_body = '_photo_' . rand(1000, 9999) . '_' . $size;
    $handle->allowed = array('image/*');
    $handle->image_resize = true;
    $handle->image_x = $size;
    $handle->image_ratio_y = true;

    $photo = $handle->file_name_body_pre . $handle->file_new_name_body . '.' . $handle->file_src_name_ext;

    $handle->image_watermark = 'uploads/watermark.png';
    // $handle->image_watermark_x = 125;
    // $handle->image_watermark_y = 0;
    $handle->process('uploads/layout/images');

    $handle->processed;
    return $photo;
}

function cropImageToThisSize($handle, $post_id, $width, $height, $name, $path, $rotate = null) {
    $ext = 'jpg';
    $handle->file_new_name_body = $name . '_' . $width;
    $handle->allowed = array('image/*');

    $handle->image_resize = true;
    $handle->image_x = $width;   // width 
    $handle->image_y = $height;  // Height 
    $handle->image_ratio = true;
    $handle->image_ratio_fill = true;
    $handle->image_background_color = '#000000';
    $handle->file_new_name_ext = 'jpg';
    $handle->image_rotate = $rotate;

    $photo = $handle->file_new_name_body . '.' . $ext;
    $handle->process(dirname(BASEPATH) . '/uploads/' . $path);

    // (`id`, `service_id`, `photo`, `size`, `featured`, `type`) VALUES (NULL, '91', 'wedwedtywt', '100', 'Yes', 'Photo');

    if ($handle->processed) {
        $data['service_id'] = $post_id;
        $data['photo'] = $photo;
        $data['size'] = $width;
        $data['featured'] = 'No';
        $data['type'] = 'Photo';
        return $data;
    } else {
        return ['service_id' => $post_id, 'photo' => 'Problem', 'size' => 0, 'featured' => 'No', 'type' => 'Photo'];
    }
}
