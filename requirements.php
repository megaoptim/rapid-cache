<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

/**
 * Class RapidCache_Requirement_Checker_Issue
 */
class RapidCache_Requirement_Checker_Issue
{

    public $reason;
    public $missing_functions;
    public $missing_extensions;

    public static function create($params)
    {
        $reason             = isset($params['reason']) ? $params['reason'] : null;
        $missing_functions  = isset($params['php_missing_functions']) ? $params['php_missing_functions'] : null;
        $missing_extensions = isset($params['php_missing_extensions']) ? $params['php_missing_extensions'] : null;

        $instance                     = new self();
        $instance->reason             = $reason;
        $instance->missing_extensions = $missing_extensions;
        $instance->missing_functions  = $missing_functions;

        return $instance;
    }
}

/**
 * Class RapidCache_Requirement_Checker
 */
class RapidCache_Requirement_Checker
{
    /**
     * The brand name
     * @var string
     */
    protected $name;

    /**
     * Initialize requirements
     * @var array
     */
    private $requirements;

    /**
     * RapidCache_Requirement_Checker constructor.
     *
     * @param $name
     * @param  array  $params
     */
    public function __construct($name, $params = array())
    {

        $this->name         = $name;
        $this->requirements = array(
            'os'         => '',
            'min'        => '',
            'max'        => '',
            'bits'       => 0,
            'functions'  => array(),
            'extensions' => array(),
            'wp'         => array(
                'min' => '',
                'max' => '',
            ),
        );

        if ( ! empty($params)) {
            if (is_string($params)) {
                $this->requirements['min'] = $params;
            } elseif (is_array($params)) {
                if ( ! empty($params['os'])) {
                    $this->requirements['os'] = (string) $params['os'];
                }
                if ( ! empty($params['min'])) {
                    $this->requirements['min'] = (string) $params['min'];

                } elseif ( ! empty($params['rv'])) {
                    $this->requirements['min'] = (string) $params['rv'];
                }
                if ( ! empty($params['max'])) {
                    $this->requirements['max'] = (string) $params['max'];
                }
                if ( ! empty($params['bits'])) {
                    $this->requirements['bits'] = (int) $params['bits'];
                }
                if ( ! empty($params['functions'])) {
                    $this->requirements['functions'] = (array) $params['functions'];
                }
                if ( ! empty($params['extensions'])) {
                    $this->requirements['extensions'] = (array) $params['extensions'];
                } elseif ( ! empty($params['re'])) {
                    $this->requirements['extensions'] = (array) $params['re'];
                }
                if ( ! empty($params['wp']['min'])) {
                    $this->requirements['wp']['min'] = (string) $params['wp']['min'];
                }
                if ( ! empty($params['wp']['max'])) {
                    $this->requirements['wp']['max'] = (string) $params['wp']['max'];
                }
            }
        }

        if ( ! empty($_REQUEST['rapid_cache_mbstring_deprecated_warning_bypass']) && is_admin()) {
            update_site_option('rapid_cache_mbstring_deprecated_warning_bypass', time());
        }
    }

    /**
     * Return the Human readable OS style
     * @param $os
     *
     * @return string
     */
    public function getOsName($os)
    {
        if ($os === 'win') {
            return 'Windows®';
        } else {
            return 'Unix-like';
        }
    }

    /**
     * Detect the OS
     * @return string
     */
    public function detectOs()
    {
        if (stripos(PHP_OS, 'win') === 0) {
            return $os = 'win';
        } else {
            return $os = 'nix';
        }
    }

    /**
     * Try to detect issues
     * @return RapidCache_Requirement_Checker_Issue|null
     */
    public function detectIssue()
    {

        global $wp_version;

        $required_os             = $this->requirements['os'];
        $php_min_version         = $this->requirements['min'];
        $php_max_version         = $this->requirements['max'];
        $php_minimum_bits        = $this->requirements['bits'];
        $php_required_functions  = $this->requirements['functions'];
        $php_required_extensions = $this->requirements['extensions'];
        $wp_min_version          = $this->requirements['wp']['min'];
        $wp_max_version          = $this->requirements['wp']['max'];

        if ($required_os && $this->detectOs() !== $required_os) {
            return RapidCache_Requirement_Checker_Issue::create(array('reason' => 'os-incompatible'));
        } elseif ($php_min_version && version_compare(PHP_VERSION, $php_min_version, '<')) {
            return RapidCache_Requirement_Checker_Issue::create(array('reason' => 'php-needs-upgrade'));
        } elseif ($php_max_version && version_compare(PHP_VERSION, $php_max_version, '>')) {
            return RapidCache_Requirement_Checker_Issue::create(array('reason' => 'php-needs-downgrade'));
        } elseif ($php_minimum_bits && $php_minimum_bits / 8 > PHP_INT_SIZE) {
            return RapidCache_Requirement_Checker_Issue::create(array('reason' => 'php-missing-bits'));
        }

        if ($php_required_functions) { // Requires PHP functions?
            $php_missing_functions = array(); // Initialize.

            foreach ($php_required_functions as $_required_function) {
                if ( ! $this->can_call_function($_required_function)) {
                    $php_missing_functions[] = $_required_function;
                }
            }

            if ($php_missing_functions) { // Missing PHP functions?
                return RapidCache_Requirement_Checker_Issue::create(array(
                    'php_missing_functions' => $php_missing_functions,
                    'reason'                => 'php-missing-functions',
                ));
            }
        }

        if ($php_required_extensions) { // Requires PHP extensions?
            $php_missing_extensions = array();
            foreach ($php_required_extensions as $_required_extension) {
                if ( ! extension_loaded($_required_extension)) {
                    $php_missing_extensions[] = $_required_extension;
                }
            }
            if ($php_missing_extensions) { // Missing PHP extensions?
                return RapidCache_Requirement_Checker_Issue::create(array(
                    'php_missing_extensions' => $php_missing_extensions,
                    'reason'                 => 'php-missing-extensions',
                ));
            }
        }

        if ($wp_min_version && version_compare($wp_version, $wp_min_version, '<')) {
            return RapidCache_Requirement_Checker_Issue::create(array('reason' => 'wp-needs-upgrade'));
        } elseif ($wp_max_version && version_compare($wp_version, $wp_max_version, '>')) {
            return RapidCache_Requirement_Checker_Issue::create(array('reason' => 'wp-needs-downgrade'));
        }

        return null; // No Problem found.
    }


    /**
     * Creates a WP Dashboard notice regarding PHP requirements.
     *
     * @param RapidCache_Requirement_Checker_Issue $issue
     */
    public function output($issue)
    {
        global $wp_version;

        if(empty($issue)) {
            return;
        }

        # Only in the admin area.
        if ( ! is_admin()) {
            return; // Not applicable.
        }

        # Establish the brand name.
        $brand_name = $this->name;

        $required_os             = $this->requirements['os'];
        $php_min_version         = $this->requirements['min'];
        $php_max_version         = $this->requirements['max'];
        $php_minimum_bits        = $this->requirements['bits'];
        $wp_min_version          = $this->requirements['wp']['min'];
        $wp_max_version          = $this->requirements['wp']['max'];

        # Fill-in additional variables needed down below.
        $action          = 'all_admin_notices';
        $action_priority = 10; // Default priority.
        $php_version = strpos(PHP_VERSION, '+') !== false ? strstr(PHP_VERSION, '+', true) : PHP_VERSION; // e.g., minus `+donate.sury.org~trusty+1`, etc.

        # Defined pre-styled icons needed below for markup generation.
        $arrow = '<span class="dashicons dashicons-editor-break" style="-webkit-transform:scale(-1, 1); transform:scale(-1, 1);"></span>';
        $icon  = '<span class="dashicons dashicons-admin-tools" style="display:inline-block; width:64px; height:64px; font-size:64px; float:left; margin:-5px 10px 0 -2px;"></span>';

        # Generate markup for the PHP dependency notice.
        switch ($issue->reason) { // Based on reason.

            case 'os-incompatible': // OS incomaptible.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('Incompatible Operating System', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires a %2$s operating system.', 'wp-php-rv'), esc_html($brand_name), esc_html(___wp_php_rv_os_name($required_os))).'<br />';
                $markup .= sprintf(__('You\'re currently running %1$s, which is not supported by %2$s at this time.', 'wp-php-rv'), esc_html(PHP_OS), esc_html($brand_name)).'<br />';
                $markup .= $arrow.' '.__('A compatible OS is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, change the OS or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'php-needs-upgrade': // Upgrade to latest version of PHP.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('PHP Upgrade Required', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires PHP v%2$s (or higher).', 'wp-php-rv'), esc_html($brand_name), esc_html($php_min_version)).'<br />';
                $markup .= sprintf(__('You are currently running the older PHP v%1$s, which is not supported by %2$s.', 'wp-php-rv'), esc_html($php_version), esc_html($brand_name)).'<br />';
                $markup .= $arrow.' '.__('An update is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, upgrade PHP or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'php-needs-downgrade': // Downgrade to older version of PHP.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('PHP Downgrade Required', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires an older version of PHP.', 'wp-php-rv'), esc_html($brand_name)).'<br />';
                $markup .= sprintf(__('This software is compatible up to PHP v%1$s, but you\'re running the newer PHP v%2$s.', 'wp-php-rv'), esc_html($php_max_version), esc_html($php_version)).'<br />';
                $markup .= $arrow.' '.__('A downgrade is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, downgrade PHP or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'php-missing-bits': // Upgrade to a more powerful architecture.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('System Upgrade Required', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires PHP on a %2$s-bit+ architecture.', 'wp-php-rv'), esc_html($brand_name), esc_html($php_minimum_bits)).'<br />';
                $markup .= sprintf(__('You\'re running an older %1$s-bit architecture, which is not supported by %2$s.', 'wp-php-rv'), esc_html(PHP_INT_SIZE * 8), esc_html($brand_name)).'<br />';
                $markup .= $arrow.' '.__('An update is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, upgrade your system or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'php-missing-functions': // PHP is missing required functions.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('PHP Function(s) Missing', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It depends on PHP function(s): %2$s.', 'wp-php-rv'), esc_html($brand_name), '<code>'.implode('</code>, <code>', array_map('esc_html', $issue->missing_extensions)).'</code>').'<br />';
                $markup .= $arrow.' '.__('An action is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, enable missing function(s) or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'php-missing-extensions': // PHP is missing required extensions.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('PHP Extension(s) Missing', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It depends on PHP extension(s): %2$s.', 'wp-php-rv'), esc_html($brand_name), '<code>'.implode('</code>, <code>', array_map('esc_html', $issue->missing_functions)).'</code>').'<br />';
                $markup .= $arrow.' '.__('An action is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, enable missing extension(s) or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'wp-needs-upgrade': // Upgrade to latest version of WP.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('WP Upgrade Required', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires WP v%2$s (or higher).', 'wp-php-rv'), esc_html($brand_name), esc_html($wp_min_version)).'<br />';
                $markup .= sprintf(__('You are currently running the older WP v%1$s, which is not supported by %2$s.', 'wp-php-rv'), esc_html($wp_version), esc_html($brand_name)).'<br />';
                $markup .= $arrow.' '.sprintf(__('An upgrade is necessary. <strong>Please <a href="%1$s">click here to upgrade now</a></strong>.', 'wp-php-rv'), esc_url(network_admin_url('/update-core.php'))).'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, upgrade WordPress or deactivate %1$s.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            case 'wp-needs-downgrade': // Downgrade to older version of WP.
                $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
                $markup .= __('WP Downgrade Required', 'wp-php-rv');
                $markup .= '</p>';
                $markup .= '<p style="margin:0 0 .5em 0;">';
                $markup .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires an older version of WP.', 'wp-php-rv'), esc_html($brand_name)).'<br />';
                $markup .= sprintf(__('This software is compatible up to WP v%1$s, but you\'re running the newer WP v%2$s.', 'wp-php-rv'), esc_html($wp_max_version), esc_html($wp_version)).'<br />';
                $markup .= $arrow.' '.sprintf(__('A downgrade is necessary. <strong>Please see: <a href="%1$s">WordPress.org release archive</a></strong>.', 'wp-php-rv'), esc_url('https://wordpress.org/download/release-archive/')).'<br />';
                $markup .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, downgrade WordPress or deactivate %1$s.</em>', 'wp-php-rv'), esc_html($brand_name));
                $markup .= '</p>';
                break; // All done here.

            default: // Default case handler; i.e., anything else.
                return; // Nothing to do here.
        }

        # Attach an action to display the notice now.
        add_action($action, function() use ($markup) {
            global $pagenow;
            if(!current_user_can('activate_plugins')) {
                return;
            }
            if(in_array($pagenow, array('update-core.php'), true)) {
                return;
            }
            if(in_array($pagenow, array('plugins.php', 'themes.php', 'update.php'), true)){
                return;
            }
            echo '<div class="notice notice-warning" style="min-height: 7.5em;">'.str_replace("'", "\\'", $markup).'</div>';
        }, $action_priority);

    }


    public function apcEnabled() {
        return extension_loaded('apc') && filter_var(ini_get('apc.enabled'),
                FILTER_VALIDATE_BOOLEAN) && filter_var(ini_get('apc.cache_by_default'),
                FILTER_VALIDATE_BOOLEAN) && mb_stripos((string) ini_get('apc.filters'),
                'rapid-cache') === false;
    }

    /**
     * Can call a PHP function?
     *
     * @param $function
     *
     * @return bool True if callable.
     */
    public function can_call_function($function)
    {

        $start = microtime(true);

        static $can        = array();
        static $constructs = array(
            'die',
            'echo',
            'empty',
            'exit',
            'eval',
            'include',
            'include_once',
            'isset',
            'list',
            'require',
            'require_once',
            'return',
            'print',
            'unset',
            '__halt_compiler',
        );
        static $functions_disabled; // Set below.

        if (isset($can[$function = strtolower($function)])) {
            return $can[$function]; // Already cached this.
        }
        if (!isset($functions_disabled)) {
            $functions_disabled = array(); // Initialize.

            if (($_ini_disable_functions = (string) @ini_get('disable_functions'))) {
                $_ini_disable_functions = strtolower($_ini_disable_functions);
                $functions_disabled     = array_merge($functions_disabled, preg_split('/[\s;,]+/u', $_ini_disable_functions, -1, PREG_SPLIT_NO_EMPTY));
            }
            if (($_ini_suhosin_blacklist_functions = (string) @ini_get('suhosin.executor.func.blacklist'))) {
                $_ini_suhosin_blacklist_functions = strtolower($_ini_suhosin_blacklist_functions);
                $functions_disabled               = array_merge($functions_disabled, preg_split('/[\s;,]+/u', $_ini_suhosin_blacklist_functions, -1, PREG_SPLIT_NO_EMPTY));
            }
            if (filter_var(@ini_get('suhosin.executor.disable_eval'), FILTER_VALIDATE_BOOLEAN)) {
                $functions_disabled[] = 'eval'; // The `eval()` construct is disabled also.
            }
        } // We now have a full list of all disabled functions.

        if ($functions_disabled && in_array($function, $functions_disabled, true)) {
            return $can[$function] = false; // Not possible; e.g., `eval()`.
        } elseif ((!function_exists($function) || !is_callable($function)) && !in_array($function, $constructs, true)) {
            return $can[$function] = false; // Not possible.
        }
        return $can[$function] = true;
    }

}

/**
 * Returns instance of the requirements checker
 *
 * @return RapidCache_Requirement_Checker
 */
function rapid_cache_get_requirements_checker() {
    return  new RapidCache_Requirement_Checker(__('Rapid Cache'), array(
        'min' => '5.4.0',
    ));
}
