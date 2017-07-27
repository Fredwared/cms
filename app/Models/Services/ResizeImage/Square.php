<?php
namespace App\Models\Services\ResizeImage;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Square implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(250, 250);
    }
}
