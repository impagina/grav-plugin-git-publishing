<?php

namespace GitPublishing;

class Utilities
{
    // TODO: check that this function does not yet exist in Grav
    public static function joinPath($base, $path)
    {
        return rtrim($base, '/').'/'.ltrim($path, '/');
    }

}
