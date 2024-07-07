<?php

declare(strict_types=1);

/*
 * This file is part of the Laravel Cloudinary package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL
    |--------------------------------------------------------------------------
    |
    | URL that used for uploading media files to Cloudinary.
    |
    */
    'cloud_url'        => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | An HTTP or HTTPS URL to notify your application (a webhook) when the process of uploads, deletes, and any API
    | that accepts `notification_url` has completed.
    |
    |
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload preset
    |--------------------------------------------------------------------------
    |
    | Upload preset From Cloudinary dashboard.
    |
    */
    'upload_preset'    => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Upload route
    |--------------------------------------------------------------------------
    |
    | Route to get `cloud_image_url` from Blade Upload Widget.
    |
    */
    'upload_route'     => env('CLOUDINARY_UPLOAD_ROUTE'),

    /*
    |--------------------------------------------------------------------------
    | Upload action
    |--------------------------------------------------------------------------
    |
    | Controller action to get `cloud_image_url` from Blade Upload Widget.
    |
    */
    'upload_action'    => env('CLOUDINARY_UPLOAD_ACTION'),

    /*
    |--------------------------------------------------------------------------
    | Default folder
    |--------------------------------------------------------------------------
    |
    | Default folder to store media on Cloudinary.
    |
    */

    'default_folder' => env('CLOUDINARY_DEFAULT_FOLDER', ''),

    /*
    |--------------------------------------------------------------------------
    | Default image
    |--------------------------------------------------------------------------
    |
    | Default image url that will be used when no image is provided.
    |
    */
    'default_image'  => env('CLOUDINARY_DEFAULT_IMAGE', ''),
];
