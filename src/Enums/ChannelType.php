<?php

namespace visifo\Rocket\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Channel ()
 * @method static static Private ()
 */

final class ChannelType extends Enum
{
    public const Channel = 'c';
    public const Private = 'p';
}
