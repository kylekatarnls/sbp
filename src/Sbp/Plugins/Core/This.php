<?php

namespace Sbp\Plugins\Core;

class This
{
    public static function chevronToThisCall($content, $caller)
    {
        return array(
            '#([\(;\s\.+/*:+\/\*\?\&\|\!\^\~\[\{]\s*|return(?:\(\s*|\s+)|[=-]\s+)>(\$?'.constant($caller.'::VALIDNAME').')#',
            '$1$this->$2',
        );
    }
}
