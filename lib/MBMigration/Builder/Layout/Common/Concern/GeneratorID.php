<?php

namespace MBMigration\Builder\Layout\Common\Concern;

trait GeneratorID
{
    protected function generateUniqueId($length = 8): string
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $lettersLength = strlen($letters);
        $charactersLength = strlen($characters);

        $randomString = $letters[rand(0, $lettersLength - 1)];

        for ($i = 2; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
