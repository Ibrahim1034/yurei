<?php
// config/intasend.php

return [
    'token' => env('INTASEND_TOKEN'),
    'publishable_key' => env('INTASEND_PUBLISHABLE_KEY'),
    'test_mode' => env('INTASEND_TEST_MODE', true), // Add this line
];