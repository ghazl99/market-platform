<?php

namespace Modules\Category\Support\PathGenerators;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;

class CategoryPathGenerator implements BasePathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        $key = $this->getDirectoryKey($media);
        return "categories/{$key}/";
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        $key = $this->getDirectoryKey($media);
        return "categories/{$key}/conversions/";
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        $key = $this->getDirectoryKey($media);
        return "categories/{$key}/responsive/";
    }

    /**
     * Get the directory key for the media.
     *
     * @param  Media  $media
     * @return string
     */
    protected function getDirectoryKey(Media $media): string
    {
        if ($model = $media->model) {
            return (string) $model->getKey();
        }

        return 'orphaned';
    }
}

