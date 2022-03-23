<?php

namespace Itwmw\Docker\Structure;

class ImageName
{
    protected string $imageName;

    protected string $imageVersion = 'latest';

    public function __construct(protected string $image)
    {
        $imageInfo = explode(':', $this->image);
        if (count($imageInfo) < 2) {
            $this->imageName = $imageInfo[0];
        } else {
            list($this->imageName, $this->imageVersion) = $imageInfo;
        }
    }

    public function __toString(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getImageVersion(): string
    {
        return $this->imageVersion;
    }

    /**
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }
}
