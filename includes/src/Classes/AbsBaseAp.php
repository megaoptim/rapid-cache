<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Classes;

use MegaOptim\RapidCache\Traits;

/**
 * Abstract Base for Advanced Cache and Plugin.
 *
 * @since 1.0.0
 */
abstract class AbsBaseAp extends AbsBase
{
    /*[.build.php-auto-generate-use-Traits]*/
    use Traits\Shared\ArrayUtils;
    use Traits\Shared\BlogUtils;
    use Traits\Shared\CacheDirUtils;
    use Traits\Shared\CacheLockUtils;
    use Traits\Shared\CachePathUtils;
    use Traits\Shared\ConditionalUtils;
    use Traits\Shared\DomainMappingUtils;
    use Traits\Shared\EscapeUtils;
    use Traits\Shared\FsUtils;
    use Traits\Shared\HookUtils;
    use Traits\Shared\HttpUtils;
    use Traits\Shared\I18nUtils;
    use Traits\Shared\IpAddrUtils;
    use Traits\Shared\PatternUtils;
    use Traits\Shared\ReplaceUtils;
    use Traits\Shared\ServerUtils;
    use Traits\Shared\StringUtils;
    use Traits\Shared\SysUtils;
    use Traits\Shared\TokenUtils;
    use Traits\Shared\TrimUtils;
    use Traits\Shared\UrlUtils;
    /*[/.build.php-auto-generate-use-Traits]*/

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Magic/overload property setter.
     *
     * @param string $property Property to set.
     * @param mixed  $value    The value for this property.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __set($property, $value)
    {
        $property          = (string) $property;
        $this->{$property} = $value;
    }

    /**
     * Closure overloading.
     *
     * @since 1.0.0
     */
    public function __call($closure, $args)
    {
        $closure = (string) $closure;

        if (isset($this->{$closure}) && is_callable($this->{$closure})) {
            return call_user_func_array($this->{$closure}, $args);
        }
        throw new \Exception(sprintf('Undefined method/closure: `%1$s`.', $closure));
    }
}
