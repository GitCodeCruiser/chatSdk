<?php

namespace App\Utilities;

class Constants
{
    const USER_PROFILE_IMAGES_PATH = 'assets/img/users';
    const USER_PROFILE_IMAGE_PREFIX = 'users';

    const ROOM_TYPE = [
        'Private Chat' => '0',
        'Group Chat' => '1',
    ];

    const MESSAGE_TYPE = [
        'Text' => 0,
        'Image' => 1,
        'Video' => 2,
        'Location' => 3,
        'Document' => 4,
        'image_text' => 5,
        'video_text' => 6,
    ];

    const MESSAGES = [
        'generic_error' => 'Something went wrong!',
        'room_created' => 'Room created successfully!',
        'success' => 'Successfully!',
        'message_delete' => 'Message Deleted Successfully!',
    ];
}