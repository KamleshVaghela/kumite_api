<?php

namespace App\Helpers;


class FileHelper {
    public static function delete_files($outputFilePath) {
        $file_path = public_path($outputFilePath);
        if (file_exists($file_path)) {
            // Attempt to delete the file
            if (unlink($file_path)) {
                // echo "File deleted successfully.";
            } 
        }
        return "1";
    }
}