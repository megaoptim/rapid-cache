<?php
namespace MegaOptim\RapidCache\Classes;

/**
 * Options Page.
 *
 * @since 1.0.0
 */
class MenuPageOptions extends MenuPage
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(); // Parent constructor.

        echo '<form id="plugin-menu-page" class="plugin-menu-page" method="post" enctype="multipart/form-data" autocomplete="off"'.
             ' action="'.esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, '_wpnonce' => wp_create_nonce()]), self_admin_url('/admin.php'))).'">'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-heading">'."\n";

        if (is_multisite()) {
            echo '<button type="button" class="plugin-menu-page-wipe-cache" style="float:right; margin-left:15px;" title="'.esc_attr(__('Wipe Cache (Start Fresh); clears the cache for all sites in this network at once!', 'rapid-cache')).'"'.
                 '  data-action="'.esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, '_wpnonce' => wp_create_nonce(), MEGAOPTIM_RAPID_CACHE_GLOBAL_NS => ['wipeCache' => '1']]), self_admin_url('/admin.php'))).'">'.
                 '  '.__('Wipe', 'rapid-cache').' <img src="'.esc_attr($this->plugin->url('/assets/images/wipe.png')).'" style="width:16px; height:16px; display:inline-block;" /></button>'."\n";
        }
        echo '   <button type="button" class="plugin-menu-page-clear-cache" style="float:right;" title="'.esc_attr(__('Clear Cache (Start Fresh)', 'rapid-cache').((is_multisite()) ? __('; affects the current site only.', 'rapid-cache') : '')).'"'.
             '      data-action="'.esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, '_wpnonce' => wp_create_nonce(), MEGAOPTIM_RAPID_CACHE_GLOBAL_NS => ['clearCache' => '1']]), self_admin_url('/admin.php'))).'">'.
             '      '.__('Clear', 'rapid-cache').' <img src="'.esc_attr($this->plugin->url('/assets/images/clear.png')).'" style="width:16px; height:16px; display:inline-block;" /></button>'."\n";

        echo '   <button type="button" class="plugin-menu-page-restore-defaults"'.// Restores default options.
             '      data-confirmation="'.esc_attr(__('Restore default plugin options? You will lose all of your current settings! Are you absolutely sure about this?', 'rapid-cache')).'"'.
             '      data-action="'.esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, '_wpnonce' => wp_create_nonce(), MEGAOPTIM_RAPID_CACHE_GLOBAL_NS => ['restoreDefaultOptions' => '1']]), self_admin_url('/admin.php'))).'">'.
             '      '.__('Restore', 'rapid-cache').' <i class="si si-ambulance"></i></button>'."\n";

        echo '   <div class="plugin-menu-page-panel-togglers" title="'.esc_attr(__('All Panels', 'rapid-cache')).'">'."\n";
        echo '      <button type="button" class="plugin-menu-page-panels-open"><i class="si si-chevron-down"></i></button>'."\n";
        echo '      <button type="button" class="plugin-menu-page-panels-close"><i class="si si-chevron-up"></i></button>'."\n";
        echo '   </div>'."\n";

        echo '  <div class="plugin-menu-page-support-links">'."\n";
        echo '      <a href="'.esc_attr('https://github.com/megaoptim/rapid-cache-kb').'" target="_blank"><i class="si si-book"></i> '.__('Knowledge Base', 'rapid-cache').'</a>'."\n";
        echo '      <a href="'.esc_attr('https://megaoptim.com/blog/category/rapid-cache/').'" target="_blank"><i class="si si-rss-square"></i> '.__('Blog', 'rapid-cache').'</a>'."\n";
        echo '      <a href="'.esc_attr('https://github.com/megaoptim/rapid-cache-kb/blob/master/other/reporting-issues.md').'" target="_blank"><i class="si si-ticket"></i> '.__('Report Issue', 'rapid-cache').'</a>'."\n";
        echo '   </div>'."\n";

        echo '<div class="plugin-menu-page-version">'."\n";
        echo    sprintf(__('%1$s v%2$s', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html(MEGAOPTIM_RAPID_CACHE_VERSION))."\n";
        echo    '(<a href="'.esc_attr('https://wordpress.org/plugins/rapid-cache/#developers').'" target="_blank">'.__('changelog', 'rapid-cache').'</a>)'."\n";
        echo '</div>'."\n";
        echo '    <img src="'.$this->plugin->url('/assets/images/options-logo.png').'" alt="'.esc_attr(__('Plugin Options', 'rapid-cache')).'" />'."\n";

        echo '<div style="clear:both;"></div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<hr />'."\n";

        /* ----------------------------------------------------------------------------------------- */

        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_updated'])) {
            echo '<div class="plugin-menu-page-notice notice">'."\n";
            echo '   <i class="si si-thumbs-up"></i> '.__('Options updated successfully.', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_restored'])) {
            echo '<div class="plugin-menu-page-notice notice">'."\n";
            echo '   <i class="si si-thumbs-up"></i> '.__('Default options successfully restored.', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_cache_wiped'])) {
            echo '<div class="plugin-menu-page-notice notice">'."\n";
            echo '   <img src="'.esc_attr($this->plugin->url('/assets/images/wipe.png')).'" /> '.__('Cache wiped across all sites; re-creation will occur automatically over time.', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_cache_cleared'])) {
            echo '<div class="plugin-menu-page-notice notice">'."\n";
            if (is_multisite() && is_main_site()) {
                echo '<img src="'.esc_attr($this->plugin->url('/assets/images/clear.png')).'" /> '.__('Cache cleared for main site; re-creation will occur automatically over time.', 'rapid-cache')."\n";
            } else {
                echo '<img src="'.esc_attr($this->plugin->url('/assets/images/clear.png')).'" /> '.__('Cache cleared for this site; re-creation will occur automatically over time.', 'rapid-cache')."\n";
            }
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_add_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.sprintf(__('Failed to update your <code>/.htaccess</code> file automatically. Most likely a permissions error. Please make sure it has permissions <code>644</code> or higher (perhaps <code>666</code>). Once you\'ve done this, please try saving the %1$s options again.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME))."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_remove_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.sprintf(__('Failed to update your <code>/.htaccess</code> file automatically. Most likely a permissions error. Please make sure it has permissions <code>644</code> or higher (perhaps <code>666</code>). Once you\'ve done this, please try saving the %1$s options again.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME))."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_nginx_notice'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.__('It appears that your server is running NGINX and does not support <code>.htaccess</code> rules. Please <a href="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/4-recommended-nginx-configuration-for-rapid-cache.md" target="_new">update your server configuration manually</a>. If you\'ve already updated your NGINX configuration, you can safely <a href="https://github.com/megaoptim/rapid-cache-kb/blob/master/questions/12-how-do-i-disable-the-nginx-htaccess-notice.md" target="_new">ignore this message</a>.', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_config_wp_cache_add_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.__('Failed to update your <code>/wp-config.php</code> file automatically. Please add the following line to your <code>/wp-config.php</code> file (right after the opening <code>&lt;?php</code> tag; on it\'s own line). <pre class="code"><code>define( \'WP_CACHE\', true );</code></pre>', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_config_wp_cache_remove_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.__('Failed to update your <code>/wp-config.php</code> file automatically. Please remove the following line from your <code>/wp-config.php</code> file, or set <code>WP_CACHE</code> to a <code>FALSE</code> value. <pre class="code"><code>define( \'WP_CACHE\', true );</code></pre>', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_advanced_cache_add_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            if ($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_advanced_cache_add_failure'] === 'advanced-cache') {
                echo '<i class="si si-thumbs-down"></i> '.sprintf(__('Failed to update your <code>/wp-content/advanced-cache.php</code> file. Cannot write file: <code>%1$s/%2$s-advanced-cache</code>. Please be sure this directory exists (and that it\'s writable): <code>%1$s</code>. Please use directory permissions <code>755</code> or higher (perhaps <code>777</code>). Once you\'ve done this, please try again.', 'rapid-cache'), esc_html($this->plugin->cacheDir()), esc_html(mb_strtolower(MEGAOPTIM_RAPID_CACHE_NAME)))."\n";
            } else {
                echo '<i class="si si-thumbs-down"></i> '.__('Failed to update your <code>/wp-content/advanced-cache.php</code> file. Most likely a permissions error. Please create an empty file here: <code>/wp-content/advanced-cache.php</code> (just an empty PHP file, with nothing in it); give it permissions <code>644</code> or higher (perhaps <code>666</code>). Once you\'ve done this, please try again.', 'rapid-cache')."\n";
            }
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_advanced_cache_remove_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.__('Failed to remove your <code>/wp-content/advanced-cache.php</code> file. Most likely a permissions error. Please delete (or empty the contents of) this file: <code>/wp-content/advanced-cache.php</code>.', 'rapid-cache')."\n";
            echo '</div>'."\n";
        }
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_ua_info_dir_population_failure'])) {
            echo '<div class="plugin-menu-page-notice error">'."\n";
            echo '   <i class="si si-thumbs-down"></i> '.sprintf(__('Failed to populate User-Agent detection files for Mobile-Adaptive Mode. User-Agent detection files are pulled from a remote location so you\'ll always have the most up-to-date information needed for accurate detection. However, it appears the remote source of this information is currently unvailable. Please wait 15 minutes, then try saving your %1$s options again.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME))."\n";
            echo '</div>'."\n";
        }
        if (!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-notice info">'."\n";
            echo '<a href="'.add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]), self_admin_url('/admin.php')).'" class="pull-right" style="margin:0 0 15px 25px; float:right; font-variant:small-caps; text-decoration:none;">'.__('close', 'rapid-cache').' <i class="si si-eye-slash"></i></a>'."\n";
            echo '   <i class="si si-eye"></i> '.sprintf(__('<strong>Pro Features (Preview)</strong> ~ New option panels below. Please explore before <a href="#" target="_blank">upgrading <i class="si si-heart-o"></i></a>.<br /><small>NOTE: the free version of %1$s (this lite version) is more-than-adequate for most sites. Please upgrade only if you desire advanced features or would like to support the developer.</small>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME))."\n";
            echo '</div>'."\n";
        }
        if (!$this->plugin->options['enable']) {
            echo '<div class="plugin-menu-page-notice warning">'."\n";
            echo '   <i class="si si-warning"></i> '.sprintf(__('%1$s is currently disabled; please review options below.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME))."\n";
            echo '</div>'."\n";
        }
        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-body">'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<h2 class="plugin-menu-page-section-heading">'.
             '  '.__('Basic Configuration (Required)', 'rapid-cache').
             '  <small><span>'.sprintf(__('Review these basic options and %1$s will be ready-to-go!', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</span></small>'.
             '</h2>';

        /* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading'.((!$this->plugin->options['enable']) ? ' open' : '').'">'."\n";
        echo '      <i class="si si-enty-gauge"></i> '.__('Enable/Disable', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body'.((!$this->plugin->options['enable']) ? ' open' : '').' clearfix">'."\n";
        echo '      <p class="speed"><img src="'.esc_attr($this->plugin->url('/assets/images/tach.png')).'" style="float:right; width:100px; margin-left:1em;" />'.sprintf(__('%1$s&trade; = SPEED<em>!!</em>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><label class="switch-primary"><input type="radio" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][enable]" value="1"'.checked($this->plugin->options['enable'], '1', false).' /> '.sprintf(__('Yes, enable %1$s&trade;', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).' <i class="si si-magic si-flip-horizontal"></i></label> &nbsp;&nbsp;&nbsp; <label><input type="radio" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][enable]" value="0"'.checked($this->plugin->options['enable'], '0', false).' /> '.__('No, disable.', 'rapid-cache').'</label></p>'."\n";
        echo '      <p class="info" style="font-family:\'Georgia\', serif; font-size:110%; margin-top:1.5em;">'.sprintf(__('<strong>HUGE Time-Saver:</strong> Approx. 95%% of all WordPress sites running %1$s, simply enable it here; and that\'s it :-) <strong>No further configuration is necessary (really).</strong> All of the other options (down below) are already tuned for the BEST performance on a typical WordPress installation. Simply enable %1$s here and click "Save All Changes". If you get any warnings please follow the instructions given. Otherwise, you\'re good <i class="si si-smile-o"></i>. This plugin is designed to run just fine like it is. Take it for a spin right away; you can always fine-tune things later if you deem necessary.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <hr />'."\n";
        echo '      <img src="'.esc_attr($this->plugin->url('/assets/images/source-code-ss.png')).'" class="screenshot" />'."\n";
        echo '      <h3>'.sprintf(__('How Can I Tell %1$s is Working?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</h3>'."\n";
        echo '      <p>'.sprintf(__('First of all, please make sure that you\'ve enabled %1$s here; then scroll down to the bottom of this page and click "Save All Changes". All of the other options (below) are already pre-configured for typical usage. Feel free to skip them all for now. You can go back through all of these later and fine-tune things the way you like them.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p>'.sprintf(__('Once %1$s has been enabled, <strong>you\'ll need to log out (and/or clear browser cookies)</strong>. By default, cache files are NOT served to visitors who are logged-in, and that includes you too ;-) Cache files are NOT served to recent comment authors either. If you\'ve commented (or replied to a comment lately); please clear your browser cookies before testing.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p>'.sprintf(__('<strong>To verify that %1$s is working</strong>, navigate your site like a normal visitor would. Right-click on any page (choose View Source), then scroll to the very bottom of the document. At the bottom, you\'ll find comments that show %1$s stats and information. You should also notice that page-to-page navigation is <i class="si si-flash"></i> <strong>lightning fast</strong> now that %1$s is running; and it gets faster over time!', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][debugging_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['debugging_enable'], '1', false).'>'.__('Yes, enable notes in the source code so I can see it\'s working (recommended).', 'rapid-cache').'</option>'."\n";
        echo '            <option value="2"'.selected($this->plugin->options['debugging_enable'], '2', false).'>'.__('Yes, enable notes in the source code AND show debugging details (not recommended for production).', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['debugging_enable'], '0', false).'>'.__('No, I don\'t want my source code to contain any of these notes.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-shield"></i> '.__('Plugin Deletion Safeguards', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <i class="si si-shield si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Uninstall on Plugin Deletion; or Safeguard Options?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.sprintf(__('<strong>Tip:</strong> By default, if you delete %1$s using the plugins menu in WordPress, nothing is lost. However, if you want to completely uninstall %1$s you should set this to <code>Yes</code> and <strong>THEN</strong> deactivate &amp; delete %1$s from the plugins menu in WordPress. This way %1$s will erase your options for the plugin, erase directories/files created by the plugin, remove the <code>advanced-cache.php</code> file, terminate CRON jobs, etc. It erases itself from existence completely.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][uninstall_on_deletion]">'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['uninstall_on_deletion'], '0', false).'>'.__('Safeguard my options and the cache (recommended).', 'rapid-cache').'</option>'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['uninstall_on_deletion'], '1', false).'>'.sprintf(__('Yes, uninstall (completely erase) %1$s on plugin deletion.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<h2 class="plugin-menu-page-section-heading">'.
             '  '.__('Advanced Configuration (All Optional)', 'rapid-cache').
             '  <small>'.__('Recommended for advanced site owners only; already pre-configured for most WP installs.', 'rapid-cache').'</small>'.
             '</h2>';

        /* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-broom"></i> '.__('Manual Cache Clearing', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <h3>'.__('Clearing the Cache Manually', 'rapid-cache').'</h3>'."\n";
            echo '      <img src="'.esc_attr($this->plugin->url('/assets/images/clear-cache-ops1-ss.png')).'" class="-clear-cache-ops-ss screenshot" />'."\n";
            echo '      <p>'.sprintf(__('Once %1$s is enabled, you will find this new option in your WordPress Admin Bar (screenshot on right). Clicking this button will clear the cache and you can start fresh at anytime (e.g., you can do this manually; and as often as you wish).', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p>'.sprintf(__('Depending on the structure of your site, there could be many reasons to clear the cache. However, the most common reasons are related to Post/Page edits or deletions, Category/Tag edits or deletions, and Theme changes. %1$s handles most scenarios all by itself. However, many site owners like to clear the cache manually; for a variety of reasons (just to force a refresh).', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_admin_bar_enable]" data-target=".-cache-clear-admin-bar-options, .-cache-clear-admin-bar-roles-caps" style="width:auto;">'."\n";
            echo '          <option value="1"'.selected($this->plugin->options['cache_clear_admin_bar_enable'], '1', false).'>'.__('Yes, enable &quot;Clear Cache&quot; button in admin bar', 'rapid-cache').'</option>'."\n";
            echo '          <option value="0"'.selected($this->plugin->options['cache_clear_admin_bar_enable'], '0', false).'>'.__('No, I don\'t intend to clear the cache manually.', 'rapid-cache').'</option>'."\n";
            echo '         </select>'."\n";
            echo '         <span class="plugin-menu-page-panel-if-enabled -cache-clear-admin-bar-options"><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_admin_bar_options_enable]" class="-no-if-enabled" style="width:auto;">'."\n";
            echo '             <option value="1"'.selected($this->plugin->options['cache_clear_admin_bar_options_enable'], '1', false).'>'.__('w/ dropdown options.', 'rapid-cache').'</option>'."\n";
            echo '             <option value="2"'.selected($this->plugin->options['cache_clear_admin_bar_options_enable'], '2', false).'>'.__('w/ dropdown options in split menu.', 'rapid-cache').'</option>'."\n";
            echo '             <option value="0"'.selected($this->plugin->options['cache_clear_admin_bar_options_enable'], '0', false).'>'.__('w/o dropdown options.', 'rapid-cache').'</option>'."\n";
            echo '         </select></span></p>'."\n";

            if (is_multisite()) {
                echo '  <div class="plugin-menu-page-panel-if-enabled -cache-clear-admin-bar-roles-caps">'."\n";
                echo '      <h4 style="margin-bottom:0;">'.__('Also allow Child Sites in a Network to clear the cache from their Admin Bar?', 'rapid-cache').'</h4>'."\n";
                echo '      <p style="margin-top:2px;">'.sprintf(__('In a Multisite Network, each child site can clear its own cache. If you want child sites to see the "Clear Cache" button in their WordPress Admin Bar, you can specify a comma-delimited list of <a href="https://wordpress.org/support/article/roles-and-capabilities/" target="_blank">Roles and/or Capabilities</a> that are allowed. For example, if I want Administrators to be capable of clearing the cache from their Admin Bar, I could enter <code>administrator</code> here. If I also want to allow Editors, I can use a comma-delimited list: <code>administrator,editor</code>. Or, I could use a single Capability of: <code>edit_others_posts</code>; which covers both Administrators &amp; Editors at the same time.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
                echo '      <p style="margin-bottom:0;"><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_admin_bar_roles_caps]" value="'.esc_attr($this->plugin->options['cache_clear_admin_bar_roles_caps']).'" /></p>'."\n";
                echo '      <p style="margin-top:0;">'.sprintf(__('<strong>Note:</strong> As a security measure, in addition to the Role(s) and/or Capabilities that you list here, each child site owner must also have the ability to <code>%1$s</code>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_IS_PRO ? $this->plugin->clear_min_cap : 'edit_posts')).'</p>'."\n";
                echo '  </div>'."\n";
            } else {
                echo '  <div class="plugin-menu-page-panel-if-enabled -cache-clear-admin-bar-roles-caps">'."\n";
                echo '      <h4 style="margin-bottom:0;">'.__('Also allow others to clear the cache from their Admin Bar?', 'rapid-cache').'</h4>'."\n";
                echo '      <p style="margin-top:2px;">'.sprintf(__('If you want others to see the "Clear Cache" button in their WordPress Admin Bar, you can specify a comma-delimited list of <a href="https://wordpress.org/support/article/roles-and-capabilities/" target="_blank">Roles and/or Capabilities</a> that are allowed. For example, if I want Editors to be capable of clearing the cache from their Admin Bar, I could enter <code>editor</code> here. If I also want to allow Authors, I can use a comma-delimited list: <code>editor,author</code>. Or, I could use a single Capability of: <code>publish_posts</code>; which covers both Editors &amp; Authors at the same time.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
                echo '      <p style="margin-bottom:0;"><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_admin_bar_roles_caps]" value="'.esc_attr($this->plugin->options['cache_clear_admin_bar_roles_caps']).'" /></p>'."\n";
                echo '      <p style="margin-top:0;">'.sprintf(__('<strong>Note:</strong> As a security measure, in addition to the Role(s) and/or Capabilities that you list here, each user must also have the ability to <code>%1$s</code>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_IS_PRO ? $this->plugin->clear_min_cap : 'edit_posts')).'</p>'."\n";
                echo '  </div>'."\n";
            }
            if ($this->plugin->functionIsPossible('opcache_reset')) {
                echo '  <hr />'."\n";
                echo '  <h3>'.__('Clear the <a href="https://www.php.net/manual/en/book.opcache.php" target="_blank">PHP OPcache</a> Too?', 'rapid-cache').'</h3>'."\n";
                echo '  <p>'.sprintf(__('If you clear the cache manually, do you want %1$s to clear the PHP OPcache too? This is not necessary, but if you want a truly clean start, this will clear all PHP files in the server\'s opcode cache also. Note: If you don\'t already know what the PHP OPcache is, it is suggested that you leave this disabled. It really is not necessary. This is just an added feature for advanced users.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
                echo '  <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_opcache_enable]" class="-no-if-enabled">'."\n";
                echo '      <option value="0"'.selected($this->plugin->options['cache_clear_opcache_enable'], '0', false).'>'.__('No, I don\'t use the PHP OPcache extension; or, I don\'t want the opcode cache cleared.', 'rapid-cache').'</option>'."\n";
                echo '      <option value="1"'.selected($this->plugin->options['cache_clear_opcache_enable'], '1', false).'>'.__('Yes, if the PHP OPcache extension is enabled, also clear the entire PHP opcode cache.', 'rapid-cache').'</option>'."\n";
                echo '  </select></p>'."\n";
            }
            if ($this->plugin->functionIsPossible('s2clean')) {
                echo '  <hr />'."\n";
                echo '  <h3>'.__('Clear the <a href="http://websharks-inc.com/product/s2clean/" target="_blank">s2Clean</a> Cache Too?', 'rapid-cache').'</h3>'."\n";
                echo '  <p>'.sprintf(__('If the s2Clean theme is installed, and you clear the cache manually, %1$s can clear the s2Clean Markdown cache too (if you\'ve enabled Markdown processing with s2Clean).', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
                echo '  <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_s2clean_enable]" class="-no-if-enabled">'."\n";
                echo '      <option value="1"'.selected($this->plugin->options['cache_clear_s2clean_enable'], '1', false).'>'.__('Yes, if the s2Clean theme is installed, also clear s2Clean-related caches.', 'rapid-cache').'</option>'."\n";
                echo '      <option value="0"'.selected($this->plugin->options['cache_clear_s2clean_enable'], '0', false).'>'.__('No, I don\'t use s2Clean; or, I don\'t want s2Clean-related caches cleared.', 'rapid-cache').'</option>'."\n";
                echo '  </select></p>'."\n";
            }
            echo '      <hr />'."\n";
            echo '      <h3>'.__('Evaluate Custom PHP Code when Clearing the Cache?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.sprintf(__('If you have any custom routines you\'d like to process when the cache is cleared manually, please enter PHP code here. If your PHP code outputs a message, it will be displayed along with any other notes from %1$s itself. This feature is intended for developers, and it may come in handy if you need to clear any system caches not already covered by %1$s configuration options.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p style="margin-bottom:0;"><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_eval_code]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['cache_clear_eval_code']).'</textarea></p>'."\n";
            echo '      <p class="info" style="margin-top:0;">'.__('<strong>Example:</strong> <code>&lt;?php apc_clear_cache(); echo \'&lt;p&gt;Also cleared APC cache.&lt;/p&gt;\'; ?&gt;</code>', 'rapid-cache').'</p>'."\n";

            echo '      <hr />'."\n";
            echo '      <h3>'.__('Clear the CDN Cache Too?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.sprintf(__('If you clear the cache manually, do you want %1$s to automatically bump the CDN invalidation counter too? i.e., automatically increment the <code>?%2$s=[counter]</code> in all static CDN URLs?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html($this->plugin->options['cdn_invalidation_var'])).'</p>'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_cdn_enable]" class="-no-if-enabled">'."\n";
            echo '            <option value="0"'.selected($this->plugin->options['cache_clear_cdn_enable'], '0', false).'>'.__('No, I don\'t use Static CDN Filters; or, I don\'t want the CDN cache cleared.', 'rapid-cache').'</option>'."\n";
            echo '            <option value="1"'.selected($this->plugin->options['cache_clear_cdn_enable'], '1', false).'>'.__('Yes, if Static CDN Filters are enabled, also clear the CDN cache.', 'rapid-cache').'</option>'."\n";
            echo '      </select></p>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }
        /* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? ' pro-preview' : '').'">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading" data-additional-pro-features="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? __('additional pro features', 'rapid-cache') : '').'">'."\n";
        echo '      <i class="si si-server"></i> '.__('Automatic Cache Clearing', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";

        echo '      <h3>'.__('Clearing the Cache Automatically', 'rapid-cache').'</h3>'."\n";
        echo '      <img src="'.esc_attr($this->plugin->url('/assets/images/auto-clear-ss.png')).'" class="screenshot" />'."\n";
        echo '      <p>'.sprintf(__('This is built into the %1$s plugin; i.e., this functionality is "always on". If you edit a Post/Page (or delete one), %1$s will automatically clear the cache file(s) associated with that content. This way a new updated version of the cache will be created automatically the next time this content is accessed. Simple updates like this occur each time you make changes in the Dashboard, and %1$s will notify you of these as they occur. %1$s monitors changes to Posts (of any kind, including Pages), Categories, Tags, Links, Themes (even Users), and more.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '  <div data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][change_notifications_enable]" class="-no-if-enabled" style="width:auto;">'."\n";
            echo '          <option value="1"'.selected($this->plugin->options['change_notifications_enable'], '1', false).'>'.sprintf(__('Yes, enable %1$s notifications in the Dashboard when cache files are cleared automatically.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</option>'."\n";
            echo '          <option value="0"'.selected($this->plugin->options['change_notifications_enable'], '0', false).'>'.sprintf(__('No, I don\'t want to know (don\'t really care) what %1$s is doing behind-the-scene.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</option>'."\n";
            echo '      </select></p>'."\n";
            echo '  </div>'."\n";
        }
        echo '      <hr />'."\n";
        echo '      <h3>'.__('Primary Page Options', 'rapid-cache').'</h3>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear Designated "Home Page" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('On many sites, the Home Page (aka: the Front Page) offers an archive view of all Posts (or even Pages). Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the "Home Page"?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_home_page_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_home_page_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the "Home Page".', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_home_page_enable'], '0', false).'>'.__('No, my Home Page does not provide a list of Posts/Pages; e.g., this is not necessary.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear Designated "Posts Page" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('On many sites, the Posts Page (aka: the Blog Page) offers an archive view of all Posts (or even Pages). Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the "Posts Page"?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_posts_page_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_posts_page_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the "Posts Page".', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_posts_page_enable'], '0', false).'>'.__('No, I don\'t use a separate Posts Page; e.g., my Home Page IS my Posts Page.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <hr />'."\n";
        echo '      <h3>'.__('Author, Archive, and Tag/Term Options', 'rapid-cache').'</h3>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "Author Page" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('On many sites, each author has a related "Author Page" that offers an archive view of all posts associated with that author. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the related "Author Page"?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_author_page_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_author_page_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the "Author Page".', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_author_page_enable'], '0', false).'>'.__('No, my site doesn\'t use multiple authors and/or I don\'t have any "Author Page" archive views.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "Category Archives" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('On many sites, each post is associated with at least one Category. Each category then has an archive view that contains all the posts within that category. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the associated Category archive views?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_term_category_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_term_category_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the associated Category archive views.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_term_category_enable'], '0', false).'>'.__('No, my site doesn\'t use Categories and/or I don\'t have any Category archive views.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "Tag Archives" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('On many sites, each post may be associated with at least one Tag. Each tag then has an archive view that contains all the posts assigned that tag. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the associated Tag archive views?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_term_post_tag_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_term_post_tag_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the associated Tag archive views.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_term_post_tag_enable'], '0', false).'>'.__('No, my site doesn\'t use Tags and/or I don\'t have any Tag archive views.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "Date-Based Archives" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('Date-Based Archives allow visitors to browse Posts by the year, month, or day they were originally published. If a single Post (of any type) is changed in some way; and %1$s clears/resets the cache for that Post, would you like %1$s to also clear any existing cache files for Dated-Based Archives that match the publication time?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_date_archives_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_date_archives_enable'], '1', false).'>'.__('Yes, if any single Post is cleared/reset, also clear the associated Date archive views.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="2"'.selected($this->plugin->options['cache_clear_date_archives_enable'], '2', false).'>'.__('Yes, but only clear the associated Day and Month archive views.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="3"'.selected($this->plugin->options['cache_clear_date_archives_enable'], '3', false).'>'.__('Yes, but only clear the associated Day archive view.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_date_archives_enable'], '0', false).'>'.__('No, don\'t clear any associated Date archive views.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "Custom Term Archives" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('Most sites do not use any custom Terms so it should be safe to leave this disabled. However, if your site uses custom Terms and they have their own Term archive views, you may want to clear those when the associated post is cleared. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the associated Tag archive views?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_term_other_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_term_other_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear any associated custom Term archive views.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_term_other_enable'], '0', false).'>'.__('No, my site doesn\'t use any custom Terms and/or I don\'t have any custom Term archive views.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "Custom Post Type Archives" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('Most sites do not use any Custom Post Types so it should be safe to disable this option. However, if your site uses Custom Post Types and they have their own Custom Post Type archive views, you may want to clear those when any associated post is cleared. Therefore, if a single Post with a Custom Post Type is changed in some way; and %1$s clears/resets the cache for that post, would you like %1$s to also clear any existing cache files for the associated Custom Post Type archive views?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_custom_post_type_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_custom_post_type_enable'], '1', false).'>'.__('Yes, if any single Post with a Custom Post Type is cleared/reset; also clear any associated Custom Post Type archive views.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_custom_post_type_enable'], '0', false).'>'.__('No, my site doesn\'t use any Custom Post Types and/or I don\'t have any Custom Post Type archive views.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <hr />'."\n";
        echo '      <h3>'.__('Feed-Related Options', 'rapid-cache').'</h3>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "RSS/RDF/ATOM Feeds" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('If you enable Feed Caching (below), this can be quite handy. If enabled, when you update a Post/Page, approve a Comment, or make other changes where %1$s can detect that certain types of Feeds should be cleared to keep your site up-to-date, then %1$s will do this for you automatically. For instance, the blog\'s master feed, the blog\'s master comments feed, feeds associated with comments on a Post/Page, term-related feeds (including mixed term-related feeds), author-related feeds, etc. Under various circumstances (i.e., as you work in the Dashboard) these can be cleared automatically to keep your site up-to-date.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_xml_feeds_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_xml_feeds_enable'], '1', false).'>'.__('Yes, automatically clear RSS/RDF/ATOM Feeds from the cache when certain changes occur.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_xml_feeds_enable'], '0', false).'>'.__('No, I don\'t have Feed Caching enabled, or I prefer not to automatically clear Feeds.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";

        echo '      <hr />'."\n";
        echo '      <h3>'.__('Sitemap-Related Options', 'rapid-cache').'</h3>'."\n";

        echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear "XML Sitemaps" Too?', 'rapid-cache').'</h4>'."\n";
        echo '      <p style="margin-top:2px;">'.sprintf(__('If you\'re generating XML Sitemaps with a plugin like <a href="http://wordpress.org/plugins/google-sitemap-generator/" target="_blank">Google XML Sitemaps</a>, you can tell %1$s to automatically clear the cache of any XML Sitemaps whenever it clears a Post/Page. Note: This does NOT clear the XML Sitemap itself of course, only the cache. The point being, to clear the cache and allow changes to a Post/Page to be reflected by a fresh copy of your XML Sitemap; sooner rather than later.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_xml_sitemaps_enable]" data-target=".-cache-clear-xml-sitemap-patterns">'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_clear_xml_sitemaps_enable'], '1', false).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the cache for any XML Sitemaps.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_clear_xml_sitemaps_enable'], '0', false).'>'.__('No, my site doesn\'t use any XML Sitemaps and/or I prefer NOT to clear the cache for XML Sitemaps.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '      <div class="plugin-menu-page-panel-if-enabled -cache-clear-xml-sitemap-patterns">'."\n";
        echo '          <p>'.__('<strong style="font-size:110%;">XML Sitemap Patterns (one per line):</strong> A default value of <code>/sitemap**.xml</code> covers all XML Sitemaps for most installations. However, you may customize this further if you deem necessary. Please list one pattern per line. XML Sitemap Pattern searches are performed against the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank">REQUEST_URI</a>. A wildcard <code>**</code> (double asterisk) can be used when necessary; e.g., <code>/sitemap**.xml</code>. Note that <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes. <code>*</code> (a single asterisk) means 0 or more characters that are NOT a slash <code>/</code>. Your patterns must match from beginning to end; i.e., the special chars: <code>^</code> (beginning of string) and <code>$</code> (end of the string) are always on for these patterns (i.e., applied internally). For that reason, if you want to match part of a URI, use <code>**</code> to match anything before and/or after the fragment you\'re searching for. For example, <code>**/sitemap**.xml</code> will match any URI containing <code>/sitemap</code> (anywhere), so long as the URI also ends with <code>.xml</code>. On the other hand, <code>/sitemap*.xml</code> will only match URIs that begin with <code>/sitemap</code>, and it will only match URIs ending in <code>.xml</code> in that immediate directory — bypassing any inside nested sub-directories. To learn more about this syntax, please see <a href="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
        echo '          <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_xml_sitemap_patterns]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['cache_clear_xml_sitemap_patterns']).'</textarea></p>'."\n";
        if (is_multisite()) {
            echo '      <p class="info" style="display:block; margin-top:-15px;">'.__('In a Multisite Network, each child blog (whether it be a sub-domain, a sub-directory, or a mapped domain); will automatically change the leading <code>http://[sub.]domain/[sub-directory]</code> used in pattern matching. In short, there is no need to add sub-domains or sub-directories for each child blog in these patterns. Please include only the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank">REQUEST_URI</a> (i.e., the path) which leads to the XML Sitemap on all child blogs in the network.', 'rapid-cache').'</p>'."\n";
        }
        echo '      </div>'."\n";

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '      <hr />'."\n";
            echo '      <h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'.__('Misc. Auto-Clear Options', 'rapid-cache').'</h3>'."\n";
            echo '      <h4 style="margin-bottom:0;">'.__('Auto-Clear Custom URL Patterns Too?', 'rapid-cache').'</h4>'."\n";
            echo '      <p style="margin-top:2px;">'.sprintf(__('<strong>Auto-Clear Custom URL Patterns (one per line):</strong> When you update a Post/Page, approve a Comment, etc., %1$s will detect that a Post/Page cache should be cleared to keep your site up-to-date. When this occurs, %1$s can also clear a list of custom URLs that you enter here. Please list one URL per line. A wildcard <code>*</code> character can be used when necessary; e.g., <code>https://example.com/category/abc/**</code>. Note that <code>**</code> (double asterisk) means 0 or more characters of any kind, including <code>/</code> slashes. <code>*</code> (a single asterisk) means 0 or more characters that are NOT a slash <code>/</code>. Your patterns must match from beginning to end; i.e., the special chars: <code>^</code> (beginning of string) and <code>$</code> (end of the string) are always on for these patterns (i.e., applied internally). For that reason, if you want to match part of a URL, use <code>**</code> to match anything before and/or after the fragment you\'re searching for. For example, <code>https://**/category/abc/**</code> will find all URLs containing <code>/category/abc/</code> (anywhere); whereas <code>https://*/category/abc/*</code> will match URLs on any domain, but the path must then begin with <code>/category/abc/</code> and the pattern will only match paths in that immediate directory — bypassing any additional paths in sub-directories. To learn more about this syntax, please see <a href="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_clear_urls]" spellcheck="false" wrap="off" rows="5">'.format_to_edit($this->plugin->options['cache_clear_urls']).'</textarea></p>'."\n";
            echo '      <p class="info" style="display:block;">'.__('<strong>Note:</strong> Relative URLs (e.g., <code>/name-of-post</code>) should NOT be used. Each entry above should start with <code>http://</code> or <code>https://</code> and include a fully qualified domain name (or wildcard characters in your pattern that will match the domain).', 'rapid-cache').'</p>'."\n";
        }
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-pie-chart"></i> '.__('Cache-Related Statistics', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            if ($this->plugin->isProPreview()) {
                echo '      <img src="'.esc_attr($this->plugin->url('/assets/images/stats-preview.png')).'" style="width:100%;border: 1px dashed #cac9c9;margin-bottom: 20px;">';
            }
            echo '      <i class="si si-pie-chart si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
            echo '      <h3>'.__('Enable Cache-Related Stats &amp; Charts?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.sprintf(__('%1$s can collect and display cache-related statistics (including charts). Stats are displayed in the WordPress Admin Bar, and also in your Dashboard under: <strong>%1$s → Stats/Charts</strong>. Cache-related stats provide you with a quick look at what\'s happening behind-the-scenes. Your site grows faster and faster as the cache grows larger in size.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][stats_enable]" data-target=".-stats-admin-bar-enable">'."\n";
            echo '            <option value="1"'.selected($this->plugin->options['stats_enable'], '1', false).'>'.__('Yes, enable stats collection &amp; the menu page in WordPress for viewing stats.', 'rapid-cache').'</option>'."\n";
            echo '            <option value="0"'.selected($this->plugin->options['stats_enable'], '0', false).'>'.__('No, I have a VERY large site and I want to avoid any unnecessary directory scans.', 'rapid-cache').'</option>'."\n";
            echo '         </select></p>'."\n";
            echo '      <p class="info">'.sprintf(__('<strong>Note:</strong> %1$s does a great job of collecting stats, in ways that don\'t cause a performance issue. In addition, as your cache grows larger than several hundred files in total size, statistics are collected less often and at longer intervals. All of that being said, if you run a VERY large site (e.g., more than 20K posts), you might want to disable stats collection in favor of blazing fast speeds not impeded by any directory scans needed to collect stats.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <hr />'."\n";

            echo '      <div class="plugin-menu-page-panel-if-enabled -stats-admin-bar-enable">'."\n";
            echo '          <h3>'.__('Show Stats in the WordPress Admin Bar?', 'rapid-cache').'</h3>'."\n";
            echo '          <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][stats_admin_bar_enable]" data-target=".-stats-admin-bar-roles-caps">'."\n";
            echo '              <option value="1"'.selected($this->plugin->options['stats_admin_bar_enable'], '1', false).'>'.__('Yes, enable stats in the WordPress admin bar.', 'rapid-cache').'</option>'."\n";
            echo '              <option value="0"'.selected($this->plugin->options['stats_admin_bar_enable'], '0', false).'>'.__('No, I\'ll review stats from the menu page in WordPress if I need to.', 'rapid-cache').'</option>'."\n";
            echo '          </select></p>'."\n";
            if (is_multisite()) {
                echo '      <div class="plugin-menu-page-panel-if-enabled -stats-admin-bar-roles-caps">'."\n";
                echo '          <h4 style="margin-bottom:0;">'.__('Allow Child Sites in a Network to See Stats in Admin Bar?', 'rapid-cache').'</h4>'."\n";
                echo '          <p style="margin-top:2px;">'.sprintf(__('In a Multisite Network, each child site has stats of its own. If you want child sites to see cache-related stats in their WordPress Admin Bar, you can specify a comma-delimited list of <a href="https://wordpress.org/support/article/roles-and-capabilities/" target="_blank">Roles and/or Capabilities</a> that are allowed to see stats. For example, if I want the Administrator to see stats in their Admin Bar, I could enter <code>administrator</code> here. If I also want to show stats to Editors, I can use a comma-delimited list: <code>administrator,editor</code>. Or, I could use a single Capability of: <code>edit_others_posts</code>; which covers both Administrators &amp; Editors at the same time.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
                echo '          <p style="margin-bottom:0;"><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][stats_admin_bar_roles_caps]" value="'.esc_attr($this->plugin->options['stats_admin_bar_roles_caps']).'" /></p>'."\n";
                echo '          <p style="margin-top:0;">'.sprintf(__('<strong>Note:</strong> As a security measure, in addition to the Role(s) and/or Capabilities that you list here, each child site owner must also have the ability to <code>%1$s</code>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_IS_PRO ? $this->plugin->stats_min_cap : 'edit_posts')).'</p>'."\n";
                echo '      </div>'."\n";
            } else {
                echo '      <div class="plugin-menu-page-panel-if-enabled -stats-admin-bar-roles-caps">'."\n";
                echo '          <h4 style="margin-bottom:0;">'.__('Allow Others to See Stats in Admin Bar?', 'rapid-cache').'</h4>'."\n";
                echo '          <p style="margin-top:2px;">'.sprintf(__('If you want others to see cache-related stats in their WordPress Admin Bar, you can specify a comma-delimited list of <a href="https://wordpress.org/support/article/roles-and-capabilities/" target="_blank">Roles and/or Capabilities</a> that are allowed to see stats. For example, if I want Editors to see stats in their Admin Bar, I could enter <code>editor</code> here. If I also want to show stats to Authors, I can use a comma-delimited list: <code>editor,author</code>. Or, I could use a single Capability of: <code>publish_posts</code>; which covers both Editors &amp; Authors at the same time.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
                echo '          <p style="margin-bottom:0;"><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][stats_admin_bar_roles_caps]" value="'.esc_attr($this->plugin->options['stats_admin_bar_roles_caps']).'" /></p>'."\n";
                echo '          <p style="margin-top:0;">'.sprintf(__('<strong>Note:</strong> As a security measure, in addition to the Role(s) and/or Capabilities that you list here, each user must also have the ability to <code>%1$s</code>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_IS_PRO ? $this->plugin->stats_min_cap : 'edit_posts')).'</p>'."\n";
                echo '      </div>'."\n";
            }
            echo '      </div>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }
        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-folder-open"></i> '.__('Cache Directory', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <h3>'.__('Base Cache Directory (Must be Writable; i.e., <a href="https://wordpress.org/support/article/changing-file-permissions/" target="_blank">Permissions</a> <code>755</code> or Higher)', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.sprintf(__('This is where %1$s will store the cached version of your site. If you\'re not sure how to deal with directory permissions, don\'t worry too much about this. If there is a problem, %1$s will let you know about it. By default, this directory is created by %1$s and the permissions are setup automatically. In most cases there is nothing more you need to do.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:pre;">'.esc_html(WP_CONTENT_DIR).'/</td><td><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][base_dir]" value="'.esc_attr($this->plugin->options['base_dir']).'" /></td><td style="width:1px; font-weight:bold; white-space:pre;">/</td></tr></table>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? ' pro-preview' : '').'">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading" data-additional-pro-features="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? __('additional pro features', 'rapid-cache') : '').'">'."\n";
        echo '      <i class="si si-clock-o"></i> '.__('Cache Expiration Time', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <i class="si si-clock-o si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Automatic Expiration Time (Max Age)', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('If you don\'t update your site much, you could set this to <code>6 months</code> and optimize everything even further. The longer the Cache Expiration Time is, the greater your performance gain. Alternatively, the shorter the Expiration Time, the fresher everything will remain on your site. A default value of <code>7 days</code> (recommended); is a good conservative middle-ground.', 'rapid-cache').'</p>'."\n";
        echo '      <p>'.sprintf(__('Keep in mind that your Expiration Time is only one part of the big picture. %1$s will also clear the cache automatically as changes are made to the site (i.e., you edit a post, someone comments on a post, you change your theme, you add a new navigation menu item, etc., etc.). Thus, your Expiration Time is really just a fallback; e.g., the maximum amount of time that a cache file could ever possibly live.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p>'.sprintf(__('All of that being said, you could set this to just <code>60 seconds</code> and you would still see huge differences in speed and performance. If you\'re just starting out with %1$s (perhaps a bit nervous about old cache files being served to your visitors); you could set this to something like <code>30 minutes</code> and experiment with it while you build confidence in %1$s. It\'s not necessary to do so, but many site owners have reported this makes them feel like they\'re more-in-control when the cache has a short expiration time. All-in-all, it\'s a matter of preference <i class="si si-smile-o"></i>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_max_age]" value="'.esc_attr($this->plugin->options['cache_max_age']).'" /></p>'."\n";
        echo '      <p class="info">'.__('<strong>Tip:</strong> the value that you specify here MUST be compatible with PHP\'s <a href="http://php.net/manual/en/function.strtotime.php" target="_blank" style="text-decoration:none;"><code>strtotime()</code></a> function. Examples: <code>30 seconds</code>, <code>2 hours</code>, <code>7 days</code>, <code>6 months</code>, <code>1 year</code>.', 'rapid-cache').'</p>'."\n";
        echo '      <p class="info">'.sprintf(__('<strong>Note:</strong> %1$s will never serve a cache file that is older than what you specify here (even if one exists in your cache directory; stale cache files are never used). In addition, a WP Cron job will automatically cleanup your cache directory (once per hour); purging expired cache files periodically. This prevents a HUGE cache from building up over time, creating a potential storage issue.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";

        echo '      <hr />'."\n";

        echo '      <h3>'.__('Cache Cleanup Schedule', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.sprintf(__('If you have an extremely large site and you lower the default Cache Expiration Time of <code>7 days</code>, expired cache files can build up more quickly. By default, %1$s cleans up expired cache files via <a href="https://codex.wordpress.org/Category:WP-Cron_Functions" target="_blank">WP Cron</a> at an <code>hourly</code> interval, but you can tell %1$s to use a custom Cache Cleanup Schedule below to run the cleanup process more or less frequently, depending on your specific needs.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_cleanup_schedule]">'."\n";
        foreach (wp_get_schedules() as $_wp_cron_schedule_key => $_wp_cron_schedule) {
            echo '       <option value="'.esc_attr($_wp_cron_schedule_key).'"'.selected($this->plugin->options['cache_cleanup_schedule'], $_wp_cron_schedule_key, false).'>'.esc_html($_wp_cron_schedule['display']).'</option>'."\n";
        } // This builds the list of options using WP_Cron schedules configured for this WP installation.
        unset($_wp_cron_schedule_key, $_wp_cron_schedule);
        echo '      </select></p>'."\n";

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            $_sys_getloadavg_unavailable = ($this->plugin->isProPreview() ? false : !$this->plugin->sysLoadAverages());
            echo '  <div>'."\n";
            echo '      <hr />'."\n";
            echo '      <h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'" style="'.($_sys_getloadavg_unavailable ? 'opacity: 0.5;' : '').'">'.__('Disable Cache Expiration If Server Load Average is High?', 'rapid-cache').'</h3>'."\n";
            echo '      <p style="'.($_sys_getloadavg_unavailable ? 'opacity: 0.5;' : '').'">'.sprintf(__('If you have high traffic at certain times of the day, %1$s can be told to check the current load average via <a href="https://www.php.net/manual/en/function.sys-getloadavg.php" target="_blank"><code>sys_getloadavg()</code></a>. If your server\'s load average has been high in the last 15 minutes or so, cache expiration is disabled automatically to help reduce stress on the server; i.e., to avoid generating a new version of the cache while the server is very busy.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p style="'.($_sys_getloadavg_unavailable ? 'opacity: 0.5;' : '').'">'.sprintf(__('To enable this functionality you should first determine what a high load average is for your server. If you log into your machine via SSH you can run the <code>top</code> command to get a feel for what a high load average looks like. Once you know the number, you can enter it in the field below; e.g., <code>1.05</code> might be a high load average for a server with one CPU. See also: <a href="https://bit.ly/2QZLwkI" target="_blank">Understanding Load Average</a>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p><input '.($_sys_getloadavg_unavailable ? 'disabled' : '').' type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_max_age_disable_if_load_average_is_gte]" value="'.esc_attr($this->plugin->options['cache_max_age_disable_if_load_average_is_gte']).'" /></p>'."\n";
            if ($_sys_getloadavg_unavailable && mb_stripos(PHP_OS, 'win') === 0) { // See: <http://jas.xyz/1HZsZ9v>
                echo '  <p class="warning">'.__('<strong>Note:</strong> It appears that your server is running Windows. The <code>sys_getloadavg()</code> function has not been implemented in PHP for Windows servers yet.', 'rapid-cache').'</p>'."\n";
            } elseif ($_sys_getloadavg_unavailable && mb_stripos(PHP_OS, 'win') !== 0) {
                echo '  <p class="warning">'.__('<strong>Note:</strong> <code>sys_getloadavg()</code> has been disabled by your web hosting company or is not available on your server.', 'rapid-cache').'</p>'."\n";
            }
            echo '   </div>'."\n";
        }
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-octi-tach"></i> '.__('Client-Side Cache', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <i class="si si-desktop si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Allow Double-Caching In The Client-Side Browser?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('Recommended setting: <code>No</code> (for membership sites, very important). Otherwise, <code>Yes</code> would be better (if users do NOT log in/out of your site).', 'rapid-cache').'</p>'."\n";
        echo '      <p>'.__('<strong>This option is NOT the same as "Leverage Browser Caching"</strong>, which refers to the caching of static resources in the browser (e.g., images, CSS, JS). This Client-Side Cache option is different in that it controls the caching of <em>page content</em> in the browser, i.e., the caching of HTML content generated by PHP itself, which is generally NOT static. If you\'re looking to Leverage Browser Caching for static resources (highly recommended), see the <strong>Apache Optimizations</strong> panel below.', 'rapid-cache').'</p>'."\n";
        echo '      <p>'.sprintf(__('%1$s handles content delivery through its ability to communicate with a browser using PHP. If you allow a browser to (cache) the caching system itself, you are momentarily losing some control; and this can have a negative impact on users that see more than one version of your site; e.g., one version while logged-in, and another while NOT logged-in. For instance, a user may log out of your site, but upon logging out they report seeing pages on the site which indicate they are STILL logged in (even though they\'re not — that\'s bad). This can happen if you allow a client-side cache, because their browser may cache web pages they visited while logged into your site which persist even after logging out. Sending no-cache headers will work to prevent this issue.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p>'.__('All of that being said, if all you care about is blazing fast speed and users don\'t log in/out of your site (only you do); you can safely set this to <code>Yes</code> (recommended in this case). Allowing a client-side browser cache will improve speed and reduce outgoing bandwidth when this option is feasible.', 'rapid-cache').'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][allow_client_side_cache]" data-toggle="enable-disable" data-target=".-client-side-cache-options">'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['allow_client_side_cache'], '0', false).'>'.__('No, prevent a client-side browser cache of dynamic page content (safest option).', 'rapid-cache').'</option>'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['allow_client_side_cache'], '1', false).'>'.__('Yes, I will allow a client-side browser cache of pages on the site.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '      <p class="info">'.__('<strong>Tip:</strong> Setting this to <code>No</code> is highly recommended when running a membership plugin like <a href="http://wordpress.org/plugins/s2member/" target="_blank">s2Member</a> (as one example). In fact, many plugins like s2Member will send <a href="http://codex.wordpress.org/Function_Reference/nocache_headers" target="_blank">nocache_headers()</a> on their own, so your configuration here will likely be overwritten when you run such plugins (which is better anyway). In short, if you run a membership plugin, you should NOT allow a client-side browser cache.', 'rapid-cache').'</p>'."\n";
        echo '      <p class="info">'.__('<strong>Tip:</strong> Setting this to <code>No</code> will NOT impact static content; e.g., CSS, JS, images, or other media. This setting pertains only to dynamic PHP scripts which produce content generated by WordPress.', 'rapid-cache').'</p>'."\n";
        echo '      <p class="info">'.sprintf(__('<strong>Advanced Tip:</strong> if you have this set to <code>No</code>, but you DO want to allow a few special URLs to be cached by the browser; you can add this parameter to your URL <code>?%2$sABC=1</code>. This tells %1$s that it\'s OK for the browser to cache that particular URL. In other words, the <code>%2$sABC=1</code> parameter tells %1$s NOT to send no-cache headers to the browser.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html(mb_strtolower(MEGAOPTIM_RAPID_CACHE_NAME))).'</p>'."\n";
        echo '      <hr />'."\n";
        echo '      <div class="plugin-menu-page-panel-if-enabled -client-side-cache-options">'."\n";
        echo '        <h3>'.__('Exclusion Patterns for Client-Side Caching', 'rapid-cache').'</h3>'."\n";
        echo '        <p>'.__('When you enable Client-Side Caching above, you may want to prevent certain pages on your site from being cached by a client-side browser. This is where you will enter those if you need to (one per line). Searches are performed against the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank" style="text-decoration:none;"><code>REQUEST_URI</code></a>; i.e., <code>/path/?query</code> (caSe insensitive). So, don\'t put in full URLs here, just word fragments found in the file path (or query string) is all you need, excluding the http:// and domain name. A wildcard <code>*</code> character can also be used when necessary; e.g., <code>/category/abc-followed-by-*</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
        echo '        <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][exclude_client_side_uris]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_client_side_uris']).'</textarea></p>'."\n";
        echo '        <p class="info">'.__('<strong>Tip:</strong> let\'s use this example URL: <code>http://www.example.com/post/example-post-123</code>. To exclude this URL, you would put this line into the field above: <code>/post/example-post-123</code>. Or, you could also just put in a small fragment, like: <code>example</code> or <code>example-*-123</code> and that would exclude any URI containing that word fragment.', 'rapid-cache').'</p>'."\n";
        echo '        <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
        echo '      </div>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' pro-preview' : '').'">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-octi-organization"></i> '.__('Logged-In Users', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <i class="si si-group si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
            echo '      <h3>'.__('Caching Enabled for Logged-In Users &amp; Comment Authors?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.__('This should almost always be set to <code>No</code>. Most sites don\'t cache content generated while a user is logged-in. Doing so could result in a cache of dynamic content generated specifically for a particular user, where the content being cached may contain details that pertain only to the user that was logged-in when the cache was generated. In short, don\'t turn this on unless you know what you\'re doing. Note also that most sites get most (sometimes all) of their traffic from users who <em>are not</em> logged-in. When a user <em>is</em> logged-in, disabling the cache is generally a good idea because a logged-in user has a session open with your site. The content they view should remain very dynamic in this scenario.', 'rapid-cache').'</p>'."\n";
            echo '      <i class="si si-sitemap si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
            echo '      <p>'.sprintf(__('<strong>Exception (Membership Sites):</strong> If you run a site with many users and the majority of your traffic comes from users who <em>are</em> logged-in, choose: <code>Yes (maintain separate cache)</code>. %1$s will operate normally, but when a user is logged-in the cache is user-specific. %1$s will intelligently refresh the cache when/if a user submits a form on your site with the GET or POST method. Or, if you make changes to their account (or another plugin makes changes to their account); including user <a href="http://codex.wordpress.org/Function_Reference/update_user_option" target="_blank">option</a>|<a href="http://codex.wordpress.org/Function_Reference/update_user_meta" target="_blank">meta</a> additions, updates &amp; deletions too. However, please note that enabling this feature (i.e., user-specific cache entries) will eat up much more disk space. That being said, the benefits of this feature for most sites will outweigh the disk overhead; i.e., it\'s not an issue in most cases. In other words, unless you\'re short on disk space, or you have thousands of users, the disk overhead is neglible.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][when_logged_in]" data-toggle="enable-disable" data-enabled-strings="1,postload" data-target=".-logged-in-users-options">'."\n";
            echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['when_logged_in'], '0', false)).'>'.__('No, do NOT cache; or serve a cache file when a user is logged-in (safest option).', 'rapid-cache').'</option>'."\n";
            echo '            <option value="postload"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' selected' : selected($this->plugin->options['when_logged_in'], 'postload', false)).'>'.__('Yes, and maintain a separate cache for each user (recommended for membership sites).', 'rapid-cache').'</option>'."\n";
            if ($this->plugin->options['when_logged_in'] === '1' || get_site_option(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_when_logged_in_was_1')) {
                update_site_option(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_when_logged_in_was_1', '1');
                echo '            <option value="1"'.selected($this->plugin->options['when_logged_in'], '1', false).'>'.__('Yes, but DON\'T maintain a separate cache for each user (I know what I\'m doing).', 'rapid-cache').'</option>'."\n";
            }
            echo '         </select></p>'."\n";
            if ($this->plugin->options['when_logged_in'] === '1' && $this->plugin->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_when_logged_in_no_admin_bar', true)) {
                echo '<p class="warning">'.sprintf(__('<strong>Warning:</strong> Whenever you enable caching for logged-in users (without a separate cache for each user), the WordPress Admin Bar <em>must</em> be disabled to prevent one user from seeing another user\'s details in the Admin Bar. <strong>Given your current configuration, %1$s will automatically hide the WordPress Admin Bar on the front-end of your site.</strong>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            }
            echo '      <p class="info">'.sprintf(__('<strong>Note:</strong> %1$s includes comment authors as part of it\'s logged-in user check. This way comment authors will be able to see updates to comment threads immediately. And, so that any dynamically-generated messages displayed by your theme will work as intended. In short, %1$s thinks of a comment author as a logged-in user, even though technically they are not. Users who gain access to password-protected Posts/Pages are also considered by the logged-in user check.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";

            echo '      <hr />'."\n";

            echo '      <div class="plugin-menu-page-panel-if-enabled -logged-in-users-options">'."\n";
            echo '        <h3>'.__('Cache Pages Containing Nonce Values in Markup?', 'rapid-cache').'</h3>'."\n";
            echo '        <p>'.sprintf(__('This should almost always be set to <code>Yes</code>. WordPress injects Nonces (<a href="https://developer.wordpress.org/themes/theme-security/using-nonces/" target="_blank" rel="external">numbers used once</a>) into the markup on any given page that a logged-in user lands on. These Nonce values are generally used to improve security when actions are taken by a user; e.g., posting a form or clicking a link that performs an action. If you set this to <code>No</code>, any page containing an Nonce will bypass the cache and be served dynamically (a performance hit). Even the Admin Bar in WordPress injects Nonce values. That\'s reason enough to leave this at the default value of <code>Yes</code>; i.e., so Nonce values in the markup don\'t result in a cache bypass. In short, don\'t set this to <code>No</code> unless you know what you\'re doing.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '        <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_nonce_values_when_logged_in]">'."\n";
            echo '           <option value="1"'.selected($this->plugin->options['cache_nonce_values_when_logged_in'], '1', false).'>'.__('Yes, for logged-in users, intelligently cache pages containing Nonce values (recommended).', 'rapid-cache').'</option>'."\n";
            echo '           <option value="0"'.selected($this->plugin->options['cache_nonce_values_when_logged_in'], '0', false).'>'.__('No, for logged-in users, refuse to cache pages containing Nonce values.', 'rapid-cache').'</option>'."\n";
            echo '           </select></p>'."\n";
            echo '        <p class="info">'.sprintf(__('<strong>Note:</strong> Nonce values in WordPress have a limited lifetime. They can expire just 12 hours after they were first generated. For this reason, %1$s will automatically force cache files containing Nonce values to expire once they are 12+ hours old; i.e., a new request for an expired page containing Nonce values will be rebuilt automatically, generating new Nonces that will continue to operate as expected. This rule is enforced no matter what your overall Cache Expiration Time is set to.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '        <hr />'."\n";
            echo '        <h3>'.__('Static CDN Filters Enabled for Logged-In Users &amp; Comment Authors?', 'rapid-cache').'</h3>'."\n";
            echo '        <p>'.__('While this defaults to a value of <code>No</code>, it should almost always be set to <code>Yes</code>. This value defaults to <code>No</code> only because Logged-In User caching (see above) defaults to <code>No</code> and setting this value to <code>Yes</code> by default can cause confusion for some site owners. Once you understand that Static CDN Filters can be applied safely for all visitors (logged-in or not logged-in), please choose <code>Yes</code> in the dropdown below. If you are not using Static CDN Filters, the value below is ignored.', 'rapid-cache').'</p>'."\n";
            echo '        <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cdn_when_logged_in]">'."\n";
            echo '              <option value="0"'.selected($this->plugin->options['cdn_when_logged_in'], '0', false).'>'.__('No, disable Static CDN Filters when a user is logged-in.', 'rapid-cache').'</option>'."\n";
            echo '                <option value="postload"'.selected($this->plugin->options['cdn_when_logged_in'], 'postload', false).'>'.__('Yes, enable Static CDN Filters for logged-in users (recommended) .', 'rapid-cache').'</option>'."\n";
            echo '          </select></p>'."\n";
            echo '        <p class="info">'.__('<strong>Note:</strong> Static CDN Filters serve <em>static</em> resources. Static resources, are, simply put, static. Thus, it is not a problem to cache these resources for any visitor (logged-in or not logged-in). To avoid confusion, this defaults to a value of <code>No</code>, and we ask that you set it to <code>Yes</code> on your own so that you\'ll know to expect this behavior; i.e., that static resources will always be served from the CDN (logged-in or not logged-in) even though Logged-In User caching may be disabled above.', 'rapid-cache').'</p>'."\n";
            echo '        <hr />'."\n";
            echo '        <h3>'.__('Enable HTML Compression for Logged-In Users?', 'rapid-cache').'</h3>'."\n";
            echo '        <p>'.__('Disabled by default. This setting is only applicable when HTML Compression is enabled. HTML Compression should remain disabled for logged-in users because the user-specific cache has a much shorter Time To Live (TTL) which means their cache is likely to expire more quickly than a normal visitor. Rebuilding the HTML Compressor cache is time-consuming and doing it too frequently will actually slow things down for them. For example, if you\'re logged into the site as a user and you submit a form, that triggers a clearing of the cache for that user, including the HTML Compressor cache. Lots of little actions you take can result in a clearing of the cache. This shorter TTL is not ideal when running the HTML Compressor because it does a deep analysis of the page content and the associated resources in order to intelligently compress things. For logged-in users, it is better to skip that extra work and just cache the HTML source as-is, avoiding that extra overhead. In short, do NOT turn this on unless you know what you\'re doing.', 'rapid-cache').'</p>'."\n";
            echo '        <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_when_logged_in]">'."\n";
            echo '           <option value="0"'.selected($this->plugin->options['htmlc_when_logged_in'], '0', false).'>'.__('No, disable HTML Compression for logged-in users (recommended).', 'rapid-cache').'</option>'."\n";
            echo '           <option value="postload"'.selected($this->plugin->options['htmlc_when_logged_in'], 'postload', false).'>'.__('Yes, enable HTML Compression for logged-in users.', 'rapid-cache').'</option>'."\n";
            echo '           </select></p>'."\n";
            echo '      </div>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }
        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? ' pro-preview' : '').'">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading" data-additional-pro-features="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? __('additional pro features', 'rapid-cache') : '').'">'."\n";
        echo '      <i class="si si-question-circle"></i> '.__('GET Requests', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";

        echo '      <i class="si si-question-circle si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Caching Enabled for GET (Query String) Requests?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('This should almost always be set to <code>No</code>. UNLESS, you\'re using unfriendly Permalinks; i.e., if all of your URLs contain a query string (like <code>?p=123</code>). In such a case, you should set this option to <code>Yes</code>. However, it\'s better to update your Permalink options and use friendly Permalinks, which also optimizes your site for search engines. Again, if you\'re using friendly Permalinks (recommended) you can leave this at the default value of <code>No</code>.', 'rapid-cache').'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][get_requests]">'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['get_requests'], '0', false).'>'.__('No, do NOT cache (or serve a cache file) when a query string is present.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['get_requests'], '1', false).'>'.__('Yes, I would like to cache URLs that contain a query string.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '      <p class="info">'.sprintf(__('<strong>Advanced Tip:</strong> If you are not caching GET requests (recommended), but you <em>do</em> want to allow some special URLs that include query string parameters to be cached, you can add this special parameter to any URL <code>?%2$sAC=1</code>. This tells %1$s that it\'s OK to cache that particular URL, even though it contains query string arguments. If you <em>are</em> caching GET requests and you want to force %1$s to <em>not</em> cache a specific request, you can add this special parameter to any URL <code>?%2$sAC=0</code>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html(mb_strtolower(MEGAOPTIM_RAPID_CACHE_NAME))).'</p>'."\n";
        echo '      <p style="font-style:italic;">'.__('<strong>Other Request Types:</strong> POST requests (i.e., forms with <code>method=&quot;post&quot;</code>) are always excluded from the cache, which is the way it should be. Any <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html" target="_blank">POST/PUT/DELETE</a> request should never, ever be cached. CLI and self-serve requests are also excluded from the cache automatically. A CLI request is one that comes from the command line; commonly used by CRON jobs and other automated routines. A self-serve request is an HTTP connection established from your site, to your site. For instance, a WP Cron job, or any other HTTP request that is spawned not by a user, but by the server itself.', 'rapid-cache').'</p>'."\n";

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div>'."\n";
            echo    '<hr />'."\n";
            echo    '<h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'.__('List of GET Variable Names to Ignore', 'rapid-cache').'</h3>'."\n";
            echo    '<p>'.__('You can enter one variable name per line. Each of the variable names that you list here will be ignored entirely; i.e., not considered when caching any given page, and not considered when serving any page that is already cached. For example, many sites use Google Analytics and there are <a href="https://support.google.com/analytics/answer/1033863?hl=en" target="_blank" rel="external">GET request variables used by Google Analytics</a>, which are read by client-side JavaScript only. Those GET variables can be ignored altogether when it comes to the cache algorithm — speeding up your site even further.', 'rapid-cache').'</p>'."\n";
            echo    '<p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][ignore_get_request_vars]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['ignore_get_request_vars']).'</textarea></p>'."\n";
            echo    '<p style="font-style:italic;">'.__('A wildcard <code>*</code> character can also be used when necessary; e.g., <code>utm_*</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
            echo '</div>'."\n";
        }
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-chain-broken"></i> '.__('404 Requests', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <i class="si si-question-circle si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Caching Enabled for 404 Requests?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.sprintf(__('When this is set to <code>No</code>, %1$s will ignore all 404 requests and no cache file will be served. While this is fine for most site owners, caching the 404 page on a high-traffic site may further reduce server load. When this is set to <code>Yes</code>, %1$s will cache the 404 page (see <a href="https://codex.wordpress.org/Creating_an_Error_404_Page" target="_blank">Creating an Error 404 Page</a>) and then serve that single cache file to all future 404 requests.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][cache_404_requests]">'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['cache_404_requests'], '0', false).'>'.__('No, do NOT cache (or serve a cache file) for 404 requests.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['cache_404_requests'], '1', false).'>'.__('Yes, I would like to cache the 404 page and serve the cached file for 404 requests.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '      <p class="info">'.sprintf(__('<strong>How does %1$s cache 404 requests?</strong> %1$s will create a special cache file (<code>----404----.html</code>, see Advanced Tip below) for the first 404 request and then <a href="http://www.php.net/manual/en/function.symlink.php" target="_blank">symlink</a> future 404 requests to this special cache file. That way you don\'t end up with lots of 404 cache files that all contain the same thing (the contents of the 404 page). Instead, you\'ll have one 404 cache file and then several symlinks (i.e., references) to that 404 cache file.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p class="info">'.__('<strong>Advanced Tip:</strong> The default 404 cache filename (<code>----404----.html</code>) is designed to minimize the chance of a collision with a cache file for a real page with the same name. However, if you want to override this default and define your own 404 cache filename, you can do so by adding <code>define(\'RAPID_CACHE_404_CACHE_FILENAME\', \'your-404-cache-filename\');</code> to your <code>wp-config.php</code> file (note that the <code>.html</code> extension should be excluded when defining a new filename).', 'rapid-cache').'</p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-feed"></i> '.__('Feed Caching', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <i class="si si-question-circle si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Caching Enabled for RSS, RDF, Atom Feeds?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('This should almost ALWAYS be set to <code>No</code>. UNLESS, you\'re sure that you want to cache your feeds. If you use a web feed management provider like Google® Feedburner and you set this option to <code>Yes</code>, you may experience delays in the detection of new posts. <strong>NOTE:</strong> If you do enable this, it is highly recommended that you also enable automatic Feed Clearing too. Please see the section above: "Automatic Cache Clearing". Find the sub-section titled: "Auto-Clear RSS/RDF/ATOM Feeds".', 'rapid-cache').'</p>'."\n";
        echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][feeds_enable]" class="-no-if-enabled">'."\n";
        echo '            <option value="0"'.selected($this->plugin->options['feeds_enable'], '0', false).'>'.__('No, do NOT cache (or serve a cache file) when displaying a feed.', 'rapid-cache').'</option>'."\n";
        echo '            <option value="1"'.selected($this->plugin->options['feeds_enable'], '1', false).'>'.__('Yes, I would like to cache feed URLs.', 'rapid-cache').'</option>'."\n";
        echo '         </select></p>'."\n";
        echo '      <p class="info">'.__('<strong>Note:</strong> This option affects all feeds served by WordPress, including the site feed, the site comment feed, post-specific comment feeds, author feeds, search feeds, and category and tag feeds. See also: <a href="http://codex.wordpress.org/WordPress_Feeds" target="_blank">WordPress Feeds</a>.', 'rapid-cache').'</p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        $exclude_hosts_option_enable = is_multisite() &&
            ((defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) || $this->plugin->canConsiderDomainMapping());

        if ($this->plugin->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_exclude_hosts_option_enable', $exclude_hosts_option_enable)) {
            echo '<div class="plugin-menu-page-panel">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
            echo '      <i class="si si-ban"></i> '.__('Host Exclusions', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <h3>'.__('Don\'t Cache These Special Host Exclusion Patterns?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.__('If there are specific domains that should not be cached, you can enter them here so they are excluded automatically. The easiest way to exclude a host is to enter the full domain name on a line of it\'s own in the field below, e.g., <code>site1.example.com</code>.', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('This field also supports <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank" style="text-decoration:none;">Watered-Down Regex</a> syntax, which means that you can also exclude a pattern like: <code>*.example.com</code> or <code>*.example.*</code>. So for instance, if you wanted to exclude all child sites and only cache pages on the Main Site of a Network installation, you could exclude all sub-domains using: <code>*.mynetwork.com</code>. That excludes all sub-domains, but not <code>mynetwork.com</code> by itself.', 'rapid-cache').'</p>'."\n";

            echo '      <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][exclude_hosts]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_hosts']).'</textarea></p>'."\n";

            echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";

            echo '   </div>'."\n";
            echo '</div>'."\n";
        }

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-ban"></i> '.__('URI Exclusions', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <h3>'.__('Don\'t Cache These Special URI Exclusion Patterns?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('Sometimes there are certain cases where a particular file, or a particular group of files, should never be cached. This is where you will enter those if you need to (one per line). Searches are performed against the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank" style="text-decoration:none;"><code>REQUEST_URI</code></a>; i.e., <code>/path/?query</code> (caSe insensitive). So, don\'t put in full URLs here, just word fragments found in the file path (or query string) is all you need, excluding the http:// and domain name. A wildcard <code>*</code> character can also be used when necessary; e.g., <code>/category/abc-followed-by-*</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
        echo '      <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][exclude_uris]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_uris']).'</textarea></p>'."\n";

        echo '      <p class="info">'.__('<strong>Tip:</strong> let\'s use this example URL: <code>http://www.example.com/post/example-post-123</code>. To exclude this URL, you would put this line into the field above: <code>/post/example-post-123</code>. Or, you could also just put in a small fragment, like: <code>example</code> or <code>example-*-123</code> and that would exclude any URI containing that word fragment.', 'rapid-cache').'</p>'."\n";
        echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
        if (is_multisite() && defined('SUBDOMAIN_INSTALL') && !SUBDOMAIN_INSTALL) {
            echo '      <p class="info">'.__('<strong>Multisite Network w/ Sub-Directories:</strong> You can also use URI Exclusion Patterns to exclude specific sites from being cached, e.g., <code>/site1/*</code>.', 'rapid-cache').'</p>'."\n";
        }
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-ban"></i> '.__('HTTP Referrer Exclusions', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <h3>'.__('Don\'t Cache These Special HTTP Referrer Exclusion Patterns?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('Sometimes there are special cases where a particular referring URL (or referring domain) that sends you traffic; or even a particular group of referring URLs or domains that send you traffic; should result in a page being loaded on your site that is NOT from the cache (and that resulting page should never be cached). This is where you will enter those if you need to (one per line). Searches are performed against the <a href="http://www.php.net//manual/en/reserved.variables.server.php" target="_blank" style="text-decoration:none;"><code>HTTP_REFERER</code></a> (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g., <code>*.domain.com</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
        echo '      <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][exclude_refs]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_refs']).'</textarea></p>'."\n";
        echo '      <p class="info">'.__('<strong>Tip:</strong> let\'s use this example URL: <code>http://www.referring-domain.com/search/?q=search+terms</code>. To exclude this referring URL, you could put this line into the field above: <code>www.referring-domain.com</code>. Or, you could also just put in a small fragment, like: <code>/search/</code> or <code>q=*</code>; and that would exclude any referrer containing that word fragment.', 'rapid-cache').'</p>'."\n";
        echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-ban"></i> '.__('User-Agent Exclusions', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <h3>'.__('Don\'t Cache These Special User-Agent Exclusion Patterns?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.__('Sometimes there are special cases when a particular user-agent (e.g., a specific browser or a specific type of device); should be shown a page on your site that is NOT from the cache (and that resulting page should never be cached). This is where you will enter those if you need to (one per line). Searches are performed against the <a href="http://www.php.net//manual/en/reserved.variables.server.php" target="_blank" style="text-decoration:none;"><code>HTTP_USER_AGENT</code></a> (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g., <code>Android *; Chrome/* Mobile</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
        echo '      <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][exclude_agents]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_agents']).'</textarea></p>'."\n";
        echo '      <p class="info">'.sprintf(__('<strong>Tip:</strong> if you wanted to exclude iPhones put this line into the field above: <code>iPhone;*AppleWebKit</code>. Or, you could also just put in a small fragment, like: <code>iphone</code>; and that would exclude any user-agent containing that word fragment. Note, this is just an example. With a default installation of %1$s, there is no compelling reason to exclude iOS devices (or any mobile device for that matter).', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' pro-preview' : '').'">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-sitemap"></i> '.__('Auto-Cache Engine', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <i class="si si-question-circle si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
            echo '      <h3>'.__('Enable the Auto-Cache Engine?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.sprintf(__('After using %1$s for awhile (or any other page caching plugin, for that matter); it becomes obvious that at some point (based on your configured Expiration Time) %1$s has to refresh itself. It does this by ditching its cached version of a page, reloading the database-driven content, and then recreating the cache with the latest data. This is a never ending regeneration cycle that is based entirely on your configured Expiration Time.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p>'.__('Understanding this, you can see that 99% of your visitors are going to receive a lightning fast response from your server. However, there will always be around 1% of your visitors that land on a page for the very first time (before it\'s been cached), or land on a page that needs to have its cache regenerated, because the existing cache has become outdated. We refer to this as a <em>First-Come Slow-Load Issue</em>. Not a huge problem, but if you\'re optimizing your site for every ounce of speed possible, the Auto-Cache Engine can help with this. The Auto-Cache Engine has been designed to combat this issue by taking on the responsibility of being that first visitor to a page that has not yet been cached, or has an expired cache. The Auto-Cache Engine is powered, in part, by <a href="http://codex.wordpress.org/Category:WP-Cron_Functions" target="_blank">WP-Cron</a> (already built into WordPress). The Auto-Cache Engine runs at 15-minute intervals via WP-Cron. It also uses the <a href="http://core.trac.wordpress.org/browser/trunk/wp-includes/http.php" target="_blank">WP_Http</a> class, which is also built into WordPress already.', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('The Auto-Cache Engine obtains its list of URLs to auto-cache, from two different sources. It can read an <a href="http://wordpress.org/extend/plugins/google-sitemap-generator/" target="_blank">XML Sitemap</a> and/or a list of specific URLs that you supply. If you supply both sources, it will use both sources collectively. The Auto-Cache Engine takes ALL of your other configuration options into consideration too, including your Expiration Time, as well as any cache exclusion rules.', 'rapid-cache').'</p>'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][auto_cache_enable]" data-target=".-auto-cache-options">'."\n";
            echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['auto_cache_enable'], '0', false)).'>'.__('No, leave the Auto-Cache Engine disabled please.', 'rapid-cache').'</option>'."\n";
            echo '            <option value="1"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' selected' : selected($this->plugin->options['auto_cache_enable'], '1', false)).'>'.__('Yes, I want the Auto-Cache Engine to keep pages cached automatically.', 'rapid-cache').'</option>'."\n";
            echo '         </select></p>'."\n";

            echo '      <hr />'."\n";

            echo '      <div class="plugin-menu-page-panel-if-enabled -auto-cache-options">'."\n";
            echo '         <h3>'.__('XML Sitemap URL (or an XML Sitemap Index)', 'rapid-cache').'</h3>'."\n";
            echo '         <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:pre;">'.esc_html(home_url('/')).'</td><td><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][auto_cache_sitemap_url]" value="'.esc_attr($this->plugin->options['auto_cache_sitemap_url']).'" /></td></tr></table>'."\n";
            if (is_multisite()) {
                echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][auto_cache_ms_children_too]">'."\n";
                echo '            <option value="0"'.selected($this->plugin->options['auto_cache_ms_children_too'], '0', false).'>'.__('All URLs in this network are in the sitemap for the main site.', 'rapid-cache').'</option>'."\n";
                echo '            <option value="1"'.selected($this->plugin->options['auto_cache_ms_children_too'], '1', false).'>'.__('Using the path I\'ve given, look for blog-specific sitemaps in each child blog also.', 'rapid-cache').'</option>'."\n";
                echo '         </select></p>'."\n";
                echo '      <p class="info" style="display:block; margin-top:0;">'.sprintf(__('<strong>↑</strong> If enabled here, each child blog can be auto-cached too. %1$s will dynamically change the leading <code>%2$s</code> as necessary; for each child blog in the network. %1$s supports both sub-directory &amp; sub-domain networks, including domain mapping plugins. For more information about how the Auto-Cache Engine caches child blogs, see <a href="#" target="_blank">this article</a>.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html(home_url('/'))).'</p>'."\n";
            }
            echo '         <hr />'."\n";

            echo '         <h3>'.__('And/Or; a List of URLs to Auto-Cache (One Per Line)', 'rapid-cache').'</h3>'."\n";
            echo '         <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][auto_cache_other_urls]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['auto_cache_other_urls']).'</textarea></p>'."\n";
            echo '         <p class="info" style="display:block; margin-top:-5px;">'.__('<strong>Note:</strong> Wildcards are NOT supported here. If you are going to supply a list of URLs above, each line must contain one full URL for the Auto-Cache Engine to auto-cache. If you have many URLs, we recommend using an <a href="https://en.wikipedia.org/wiki/Sitemaps" target="_blank">XML Sitemap</a>.', 'rapid-cache').'</p>'."\n";

            echo '         <hr />'."\n";

            echo '         <h3>'.__('Auto-Cache Delay Timer (in Milliseconds)', 'rapid-cache').'</h3>'."\n";
            echo '         <p>'.__('As the Auto-Cache Engine runs through each URL, you can tell it to wait X number of milliseconds between each connection that it makes. It is strongly suggested that you DO have some small delay here. Otherwise, you run the risk of hammering your own web server with multiple repeated connections whenever the Auto-Cache Engine is running. This is especially true on very large sites; where there is the potential for hundreds of repeated connections as the Auto-Cache Engine goes through a long list of URLs. Adding a delay between each connection will prevent the Auto-Cache Engine from placing a heavy load on the processor that powers your web server. A value of <code>500</code> milliseconds is suggested here (half a second). If you experience problems, you can bump this up a little at a time, in increments of <code>500</code> milliseconds; until you find a happy place for your server. <em>Please note that <code>1000</code> milliseconds = <code>1</code> full second.</em>', 'rapid-cache').'</p>'."\n";
            echo '         <p><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][auto_cache_delay]" value="'.esc_attr($this->plugin->options['auto_cache_delay']).'" /></p>'."\n";

            echo '         <hr />'."\n";

            echo '         <h3>'.__('Auto-Cache User-Agent String', 'rapid-cache').'</h3>'."\n";
            echo '         <table style="width:100%;"><tr><td><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][auto_cache_user_agent]" value="'.esc_attr($this->plugin->options['auto_cache_user_agent']).'" /></td><td style="width:1px; font-weight:bold; white-space:pre;">; '.esc_html(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.' '.MEGAOPTIM_RAPID_CACHE_VERSION).'</td></tr></table>'."\n";
            echo '         <p class="info" style="display:block;">'.__('This is how the Auto-Cache Engine identifies itself when connecting to URLs. See <a href="http://en.wikipedia.org/wiki/User_agent" target="_blank">User Agent</a> in the Wikipedia.', 'rapid-cache').'</p>'."\n";
            echo '      </div>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }
        /* ----------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' pro-preview' : '').'">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-html5"></i> '.__('HTML Compression', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <i class="si si-question-circle si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
            echo '      <h3>'.__('Enable WebSharks™ HTML Compression?', 'rapid-cache').'</h3>'."\n";
            if (is_plugin_active('autoptimize/autoptimize.php')) {
                echo '      <p class="warning">'.__('<strong>Autoptimize + Rapid Cache:</strong> Rapid Cache has detected that you are running the Autoptimize plugin. Autoptimize and the HTML Compressor feature of Rapid Cache are both designed to compress HTML, CSS, and JavaScript. Enabling the HTML Compressor alongside Autoptimize may result in unexpected behavior. If you\'re happy with Autoptimize, you can leave the HTML Compressor disabled. All other Rapid Cache features run great alongside Autoptimize.', 'rapid-cache').' <i class="si si-smile-o"></i></p>';
            }
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_enable]" data-target=".-htmlc-options">'."\n";
            echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['htmlc_enable'], '0', false)).'>'.__('No, do NOT compress HTML/CSS/JS code at runtime.', 'rapid-cache').'</option>'."\n";
            echo '            <option value="1"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' selected' : selected($this->plugin->options['htmlc_enable'], '1', false)).'>'.__('Yes, I want to compress HTML/CSS/JS for blazing fast speeds.', 'rapid-cache').'</option>'."\n";
            echo '         </select></p>'."\n";
            echo '      <hr />'."\n";
            echo '      <div class="plugin-menu-page-panel-if-enabled -htmlc-options">'."\n";
            echo '         <h3>'.__('HTML Compression Options', 'rapid-cache').'</h3>'."\n";
            echo '         <p>'.__('You can <a href="https://github.com/websharks/html-compressor" target="_blank">learn more about all of these options here</a>.', 'rapid-cache').'</p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_combine_head_body_css]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_head_body_css'], '1', false).'>'.__('Yes, combine CSS from &lt;head&gt; and &lt;body&gt; into fewer files.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_head_body_css'], '0', false).'>'.__('No, do not combine CSS from &lt;head&gt; and &lt;body&gt; into fewer files.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_css_code]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_css_code'], '1', false).'>'.__('Yes, compress the code in any unified CSS files.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_css_code'], '0', false).'>'.__('No, do not compress the code in any unified CSS files.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_combine_head_js]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_head_js'], '1', false).'>'.__('Yes, combine JS from &lt;head&gt; into fewer files.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_head_js'], '0', false).'>'.__('No, do not combine JS from &lt;head&gt; into fewer files.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_combine_footer_js]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_footer_js'], '1', false).'>'.__('Yes, combine JS footer scripts into fewer files.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_footer_js'], '0', false).'>'.__('No, do not combine JS footer scripts into fewer files.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_combine_remote_css_js]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_remote_css_js'], '1', false).'>'.__('Yes, combine CSS/JS from remote resources too.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_remote_css_js'], '0', false).'>'.__('No, do not combine CSS/JS from remote resources.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_js_code]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_js_code'], '1', false).'>'.__('Yes, compress the code in any unified JS files.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_js_code'], '0', false).'>'.__('No, do not compress the code in any unified JS files.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_inline_js_code]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_inline_js_code'], '1', false).'>'.__('Yes, compress inline JavaScript snippets.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_inline_js_code'], '0', false).'>'.__('No, do not compress inline JavaScript snippets.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_compress_html_code]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_html_code'], '1', false).'>'.__('Yes, compress (remove extra whitespace) in the final HTML code too.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_html_code'], '0', false).'>'.__('No, do not compress the final HTML code.', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_amp_exclusions_enable]" autocomplete="off">'."\n";
            echo '               <option value="1"'.selected($this->plugin->options['htmlc_amp_exclusions_enable'], '1', false).'>'.__('Yes, auto-detect AMP (Accelerated Mobile Pages) and selectively disable incompatible features.', 'rapid-cache').'</option>'."\n";
            echo '               <option value="0"'.selected($this->plugin->options['htmlc_amp_exclusions_enable'], '0', false).'>'.__('No, do not auto-detect AMP (Accelerated Mobile Pages) and selectively disable incompatible features', 'rapid-cache').'</option>'."\n";
            echo '            </select></p>'."\n";
            echo '         <hr />'."\n";
            echo '         <h3>'.__('CSS Exclusion Patterns?', 'rapid-cache').'</h3>'."\n";
            echo '         <p>'.__('Sometimes there are special cases when a particular CSS file should NOT be consolidated or compressed in any way. This is where you will enter those if you need to (one per line). Searches are performed against the <code>&lt;link href=&quot;&quot;&gt;</code> value, and also against the contents of any inline <code>&lt;style&gt;</code> tags (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g., <code>xy*-framework</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
            echo '         <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_css_exclusions]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['htmlc_css_exclusions']).'</textarea></p>'."\n";
            echo '         <p class="info" style="display:block;">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
            echo '         <h3>'.__('JavaScript Exclusion Patterns?', 'rapid-cache').'</h3>'."\n";
            echo '         <p>'.__('Sometimes there are special cases when a particular JS file should NOT be consolidated or compressed in any way. This is where you will enter those if you need to (one per line). Searches are performed against the <code>&lt;script src=&quot;&quot;&gt;</code> value, and also against the contents of any inline <code>&lt;script&gt;</code> tags (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g., <code>xy*-framework</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
            echo '         <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_js_exclusions]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['htmlc_js_exclusions']).'</textarea></p>'."\n";
            echo '         <p class="info" style="display:block;">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
            echo '         <h3>'.__('URI Exclusions for HTML Compressor?', 'rapid-cache').'</h3>'."\n";
            echo '         <p>'.__('When you enable HTML Compression above, you may want to prevent certain pages on your site from being cached by the HTML Compressor. This is where you will enter those if you need to (one per line). Searches are performed against the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank" style="text-decoration:none;"><code>REQUEST_URI</code></a>; i.e., <code>/path/?query</code> (caSe insensitive). So, don\'t put in full URLs here, just word fragments found in the file path (or query string) is all you need, excluding the http:// and domain name. A wildcard <code>*</code> character can also be used when necessary; e.g., <code>/category/abc-followed-by-*</code> (where <code>*</code> = 0 or more characters that are NOT a slash <code>/</code>). Other special characters include: <code>**</code> = 0 or more characters of any kind, including <code>/</code> slashes; <code>^</code> = beginning of the string; <code>$</code> = end of the string. To learn more about this syntax, please see <a href ="https://github.com/megaoptim/rapid-cache-kb/blob/master/tutorials/5-watered-down-regex-syntax.md" target="_blank">this KB article</a>.', 'rapid-cache').'</p>'."\n";
            echo '         <p><textarea name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_uri_exclusions]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['htmlc_uri_exclusions']).'</textarea></p>'."\n";
            echo '         <p class="info">'.__('<strong>Tip:</strong> let\'s use this example URL: <code>http://www.example.com/post/example-post-123</code>. To exclude this URL, you would put this line into the field above: <code>/post/example-post-123</code>. Or, you could also just put in a small fragment, like: <code>example</code> or <code>example-*-123</code> and that would exclude any URI containing that word fragment.', 'rapid-cache').'</p>'."\n";
            echo '         <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g., one exclusion pattern per line.', 'rapid-cache').'</p>'."\n";
            echo '         <hr />'."\n";
            echo '         <h3>'.__('HTML Compression Cache Expiration', 'rapid-cache').'</h3>'."\n";
            echo '         <p><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htmlc_cache_expiration_time]" value="'.esc_attr($this->plugin->options['htmlc_cache_expiration_time']).'" /></p>'."\n";
            echo '         <p class="info" style="display:block;">'.__('<strong>Tip:</strong> the value that you specify here MUST be compatible with PHP\'s <a href="http://php.net/manual/en/function.strtotime.php" target="_blank" style="text-decoration:none;"><code>strtotime()</code></a> function. Examples: <code>2 hours</code>, <code>7 days</code>, <code>6 months</code>, <code>1 year</code>.', 'rapid-cache').'</p>'."\n";
            echo '         <p>'.sprintf(__('<strong>Note:</strong> This does NOT impact the overall cache expiration time that you configure with %1$s. It only impacts the sub-routines provided by the HTML Compressor. In fact, this expiration time is mostly irrelevant. The HTML Compressor uses an internal checksum, and it also checks <code>filemtime()</code> before using an existing cache file. The HTML Compressor class also handles the automatic cleanup of your cache directories to keep it from growing too large over time. Therefore, unless you have VERY little disk space there is no reason to set this to a lower value (even if your site changes dynamically quite often). If anything, you might like to increase this value which could help to further reduce server load. You can <a href="https://github.com/websharks/HTML-Compressor" target="_blank">learn more here</a>. We recommend setting this value to at least double that of your overall %1$s expiration time.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      </div>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }

        /* ----------------------------------------------------------------------------------------- */

        if ($this->plugin->isApache() || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? ' pro-preview' : '').'">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-additional-pro-features="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isProPreview() ? __('additional pro features', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-server"></i> '.__('Apache Optimizations', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <img src="'.esc_attr($this->plugin->url('/assets/images/apache.png')).'" class="screenshot" />'."\n";
            echo '      <h3>'.__('Apache Performance Tuning (Optional; Highly Recommended)', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.__('You don\'t need to use an <code>.htaccess</code> file to enjoy the performance enhancements provided by this plugin; caching is handled automatically by WordPress/PHP alone. That being said, if you want to take advantage of additional speed enhancements by optimizing the Apache web server to achieve maximize performance (and we do recommend this), then you WILL need an <code>.htaccess</code> file to accomplish that part.', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('WordPress itself uses the <code>.htaccess</code> file to create Apache rewrite rules when you enable fancy Permalinks, so there\'s a good chance you already have an <code>.htaccess</code> file. The options below allow for additional performance tuning using recommendations provided by Rapid Cache.', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('When you enable one of the options below, Rapid Cache will attempt to automatically insert the appropriate configuration into your <code>.htaccess</code> file (or remove it automatically if you are disabling an option). If Rapid Cache is unable to update the file, or if you would prefer to add the configuration yourself, the recommended configuration to add to the file can be viewed at the bottom of each option.', 'rapid-cache').'</p>'."\n";
            echo '              <p class="info" style="display:block;">'.__('<strong>Note:</strong> The <code>.htaccess</code> file is parsed by the web server directly, before WordPress is even loaded. For that reason, if something goes wrong in the file you can end up with a broken site. We recommend creating a backup of your current <code>.htaccess</code> file before making any modifications.', 'rapid-cache').'</p>'."\n";
            echo '      <hr />'."\n";
            echo '      <h3>'.__('Enable GZIP Compression?', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.__('<a href="https://bit.ly/3jUtUD1" target="_blank">GZIP compression</a> is highly recommended. It\'s not uncommon to achieve compression rates as high as 70-90%, which is a huge savings in the amount of data that needs to be transferred with each visit to your site.', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.sprintf(__('%1$s fully supports GZIP compression on its output. However, it does not handle GZIP compression directly like some caching plugins. We purposely left GZIP compression out of this plugin because GZIP compression is something that should really be enabled at the Apache level or inside your <code>php.ini</code> file. GZIP compression can be used for things like JavaScript and CSS files as well, so why bother turning it on for only WordPress-generated pages when you can enable GZIP at the server level and cover all the bases!', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htaccess_gzip_enable]" data-target=".-htaccess-gzip-enable-options">'."\n";
            echo '            <option value="0"'.selected($this->plugin->options['htaccess_gzip_enable'], '0', false).'>'.__('No, do NOT enable GZIP Compression (or I\'ll update my configuration manually; see below)', 'rapid-cache').'</option>'."\n";
            echo '            <option value="1"'.selected($this->plugin->options['htaccess_gzip_enable'], '1', false).'>'.__('Yes, enable GZIP Compression (recommended)', 'rapid-cache').'</option>'."\n";
            echo '         </select></p>'."\n";
            echo '      <p>'.__('Or, you can update your configuration manually: [<a href="#" data-toggle-target=".'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--gzip-configuration').'"><i class="si si-eye"></i> .htaccess configuration <i class="si si-eye"></i></a>]', 'rapid-cache').'</p>'."\n";
            echo '      <div class="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--gzip-configuration').'" style="display:none; margin-top:1em;">'."\n";
            echo '        <p>'.__('<strong>To enable GZIP compression:</strong> Create or edit the <code>.htaccess</code> file in your WordPress installation directory and add the following lines to the top:', 'rapid-cache').'</p>'."\n";
            echo '        <pre class="code"><code>'.esc_html($this->plugin->fillReplacementCodes(file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'stubs/htaccess/gzip-enable.txt'))).'</code></pre>'."\n";
            echo '        <p class="info" style="display:block;">'.__('<strong>Or</strong>, if your server is missing <code>mod_deflate</code>/<code>mod_filter</code>; open your <code>php.ini</code> file and add this line: <a href="http://php.net/manual/en/zlib.configuration.php" target="_blank" style="text-decoration:none;"><code>zlib.output_compression = on</code></a>', 'rapid-cache').'</p>'."\n";
            echo '      </div>'."\n";

            if ((!MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->isApache()) && !$this->plugin->isProPreview()) {
                echo '      <hr />'."\n";
                echo '      <p class="warning" style="display:block;">'.sprintf(__('<a href="%1$s">Enable the Pro Preview</a> to see <strong>Leverage Browser Caching</strong>, <strong>Enforce Canonical URLs</strong>, and more!', 'rapid-cache'), esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_pro_preview' => '1']), self_admin_url('/admin.php')))).'</p>'."\n";
            }
            if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
                echo '      <hr />'."\n";
                echo '      <h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'.__('Leverage Browser Caching?', 'rapid-cache').'</h3>'."\n";
                echo '      <p>'.__('<a href="https://bit.ly/3lNT8ou" target="_blank">Browser Caching</a> is highly recommended. When loading a single page, downloading all of the resources for that page may require multiple roundtrips between the browser and server, which delays processing and may block rendering of page content. This also incurs data costs for the visitor. With browser caching, your server tells the visitor\'s browser that it is allowed to cache static resources for a certain amount of time (Google recommends 1 week and that\'s what Rapid Cache uses).', 'rapid-cache').'</p>'."\n";
                echo '      <p>'.__('In WordPress, \'Page Caching\' is all about server-side performance (reducing the amount of time it takes the server to generate the page content). With Rapid Cache installed, you\'re drastically reducing page generation time. However, you can make a visitor\'s experience ​<em>even faster</em>​ when you leverage browser caching too. When this option is enabled, the visitor\'s browser will cache static resources from each page and reuse those cached resources on subsequent page loads. In this way, future visits to the same page will not require additional connections to your site to download static resources that the visitor\'s browser has already cached.', 'rapid-cache').'</p>'."\n";
                echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htaccess_browser_caching_enable]" data-target=".-htaccess-browser-caching-enable-options">'."\n";
                echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['htaccess_browser_caching_enable'], '0', false)).'>'.__('No, do NOT enable Browser Caching (or I\'ll update my configuration manually; see below)', 'rapid-cache').'</option>'."\n";
                echo '            <option value="1"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? 'selected' : selected($this->plugin->options['htaccess_browser_caching_enable'], '1', false)).'>'.__('Yes, enable Browser Caching for static resources (recommended)', 'rapid-cache').'</option>'."\n";
                echo '         </select></p>'."\n";
                echo '      <p>'.__('Or, you can update your configuration manually: [<a href="#" data-toggle-target=".'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--leverage-browser-caching').'"><i class="si si-eye"></i> .htaccess configuration <i class="si si-eye"></i></a>]', 'rapid-cache').'</p>'."\n";
                echo '      <div class="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--leverage-browser-caching').'" style="display:none; margin-top:1em;">'."\n";
                echo '        <p>'.__('<strong>To enable Browser Caching:</strong> Create or edit the <code>.htaccess</code> file in your WordPress installation directory and add the following lines to the top:', 'rapid-cache').'</p>'."\n";
                echo '        <pre class="code"><code>'.esc_html($this->plugin->fillReplacementCodes(file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'stubs/htaccess/browser-caching-enable.txt'))).'</code></pre>'."\n";
                echo '      </div>'."\n";
            }
            if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
                echo '      <hr />'."\n";
                echo '      <h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'.__('Enforce an Exact Hostname?', 'rapid-cache').'</h3>'."\n";
                echo '      <p>'.sprintf(__('By enforcing an exact hostname you avoid duplicate cache files, which saves disk space and improves cache performance. For example, if a bot or crawler accesses your site using your server\'s IP address instead of using your domain name (e.g., <code>http://123.456.789/path</code>), this results in duplicate cache files, because the host was an IP address. The \'host\' being an important factor in any cache storage system. The same would be true if a visitor attempted to access your site using a made-up sub-domain; e.g., <code>http://foo.bar.%1$s/path</code>. This sort of thing can be avoided by explicitly enforcing an exact hostname in the request. One that matches exactly what you\'ve configured in <strong>WordPress Settings → General</strong>.', 'rapid-cache'), esc_html(parse_url(network_home_url(), PHP_URL_HOST))).'</p>'."\n";
                echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htaccess_enforce_exact_host_name]" data-target=".-htaccess-enforce-exact-host-name-options">'."\n";
                echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['htaccess_enforce_exact_host_name'], '0', false)).'>'.__('No, do NOT enforce an exact hostname (or I\'ll update my configuration manually; see below)', 'rapid-cache').'</option>'."\n";
                echo '            <option value="1"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? 'selected' : selected($this->plugin->options['htaccess_enforce_exact_host_name'], '1', false)).'>'.sprintf(__('Yes, enforce the exact hostname: %1$s', 'rapid-cache'), esc_html(parse_url(network_home_url(), PHP_URL_HOST))).'</option>'."\n";
                echo '         </select></p>'."\n";
                echo '      <p>'.__('Or, you can update your configuration manually: [<a href="#" data-toggle-target=".'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--enforce-exact-host-name').'"><i class="si si-eye"></i> .htaccess configuration <i class="si si-eye"></i></a>]', 'rapid-cache').'</p>'."\n";
                echo '      <div class="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--enforce-exact-host-name').'" style="display:none; margin-top:1em;">'."\n";
                echo '        <p>'.__('<strong>To enforce an exact hostname:</strong> Create or edit the <code>.htaccess</code> file in your WordPress installation directory and add the following lines to the top:', 'rapid-cache').'</p>'."\n";
                echo '        <pre class="code"><code>'.esc_html($this->plugin->fillReplacementCodes(file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'stubs/htaccess/enforce-exact-host-name.txt'))).'</code></pre>'."\n";
                echo '      </div>'."\n";
            }
            if ((MEGAOPTIM_RAPID_CACHE_IS_PRO && !empty($GLOBALS['wp_rewrite']->permalink_structure)) || $this->plugin->isProPreview()) {
                echo '      <hr />'."\n";
                echo '      <h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'.__('Enforce Canonical URLs?', 'rapid-cache').'</h3>'."\n";
                echo '      <p>'.__('Permalinks (URLs) leading to Posts/Pages on your site (based on your WordPress Permalink Settings) '.($GLOBALS['wp_rewrite']->use_trailing_slashes ? 'require a <code>.../trailing-slash/</code>' : 'do not require a <code>.../trailing-slash</code>').'. Ordinarily, WordPress enforces this by redirecting a request for '.($GLOBALS['wp_rewrite']->use_trailing_slashes ? '<code>.../something</code>' : '<code>.../something/</code>').', to '.($GLOBALS['wp_rewrite']->use_trailing_slashes ? '<code>.../something/</code>' : '<code>.../something</code>').', thereby forcing the final location to match your Permalink configuration. However, whenever you install a plugin like Rapid Cache, much of WordPress (including this automatic redirection) is out of the picture when the cached copy of a page is being served. So enabling this option will add rules to your <code>.htaccess</code> file that make Apache aware of your WordPess Permalink configuration. Apache can do what WordPress normally would, only much more efficiently.', 'rapid-cache').'</p>'."\n";
                echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htaccess_enforce_canonical_urls]" data-target=".-htaccess-enforce-canonical-urls-options">'."\n";
                echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['htaccess_enforce_canonical_urls'], '0', false)).'>'.__('No, do NOT enforce canonical URLs (or I\'ll update my configuration manually; see below)', 'rapid-cache').'</option>'."\n";
                echo '            <option value="1"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? 'selected' : selected($this->plugin->options['htaccess_enforce_canonical_urls'], '1', false)).'>'.__('Yes, enforce canonical URLs (recommended)', 'rapid-cache').'</option>'."\n";
                echo '         </select></p>'."\n";
                echo '      <p>'.__('Or, you can update your configuration manually: [<a href="#" data-toggle-target=".'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--enforce-cononical-urls').'"><i class="si si-eye"></i> .htaccess configuration <i class="si si-eye"></i></a>]', 'rapid-cache').'</p>'."\n";
                echo '      <div class="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--enforce-cononical-urls').'" style="display:none; margin-top:1em;">'."\n";
                echo '        <p>'.__('<strong>To enforce Canonical URLs:</strong> Create or edit the <code>.htaccess</code> file in your WordPress installation directory and add the following lines to the top:', 'rapid-cache').'</p>'."\n";
                if ($GLOBALS['wp_rewrite']->use_trailing_slashes) {
                    echo '        <pre class="code"><code>'.esc_html($this->plugin->fillReplacementCodes(file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'stubs/htaccess/canonical-urls-ts-enable.txt'))).'</code></pre>'."\n";
                } else {
                    echo '        <pre class="code"><code>'.esc_html($this->plugin->fillReplacementCodes(file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'stubs/htaccess/canonical-urls-no-ts-enable.txt'))).'</code></pre>'."\n";
                }
                echo '      </div>'."\n";
            }
            if ((MEGAOPTIM_RAPID_CACHE_IS_PRO && $this->plugin->options['cdn_enable']) || $this->plugin->isProPreview()) {
                echo '      <hr />'."\n";
                echo '      <h3 data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'.__('Send Access-Control-Allow-Origin Header?', 'rapid-cache').'</h3>'."\n";
                if ($this->plugin->options['cdn_enable'] && !$this->plugin->options['htaccess_access_control_allow_origin']) {
                    echo '        <p class="warning" style="display:block;">'.__('<strong>Warning:</strong> Send Access-Control-Allow-Origin Header has been disabled below but <strong>Rapid Cache → Plugin Options → Static CDN Filters</strong> are enabled. We recommend configuring your server to send the <code>Access-Control-Allow-Origin</code> header to avoid <a href="#" target="_blank">CORS errors</a> when a CDN is configured.', 'rapid-cache').'</p>'."\n";
                }
                echo '      <p>'.__('If you are using Static CDN Filters to load resources for your site from another domain, it\'s important that your server sends an <code>Access-Control-Allow-Origin</code> header to prevent Cross Origin Resource Sharing (CORS) errors. This option is enabled automatically when you enable Static CDN Filters. For more information, see <a href="#" target="_blank">this article</a>.', 'rapid-cache').'</p>'."\n";
                echo '      <p><select name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][htaccess_access_control_allow_origin]" data-target=".-htaccess-access-control-allow-origin-options">'."\n";
                echo '            <option value="0"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? '' : selected($this->plugin->options['htaccess_access_control_allow_origin'], '0', false)).'>'.__('No, do NOT send the Access-Control-Allow-Origin header (or I\'ll update my configuration manually; see below)', 'rapid-cache').'</option>'."\n";
                echo '            <option value="1"'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? 'selected' : selected($this->plugin->options['htaccess_access_control_allow_origin'], '1', false)).'>'.__('Yes, send the Access-Control-Allow-Origin header (recommended for Static CDN Filters)', 'rapid-cache').'</option>'."\n";
                echo '         </select></p>'."\n";
                echo '      <p>'.__('Or, you can update your configuration manually: [<a href="#" data-toggle-target=".'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--access-control-allow-origin').'"><i class="si si-eye"></i> .htaccess configuration <i class="si si-eye"></i></a>]', 'rapid-cache').'</p>'."\n";
                echo '      <div class="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-apache-optimizations--access-control-allow-origin').'" style="display:none; margin-top:1em;">'."\n";
                echo '        <p>'.__('<strong>To send the Access-Control-Allow-Origin header:</strong> Create or edit the <code>.htaccess</code> file in your WordPress installation directory and add the following lines to the top:', 'rapid-cache').'</p>'."\n";
                echo '        <pre class="code"><code>'.esc_html($this->plugin->fillReplacementCodes(file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'stubs/htaccess/access-control-allow-origin-enable.txt'))).'</code></pre>'."\n";
                echo '      </div>'."\n";
            }
            echo '   </div>'."\n";
            echo '</div>'."\n";
        }

        /* ----------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' pro-preview' : '').'">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-octi-versions"></i> '.__('Dynamic Version Salt', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <img src="'.esc_attr($this->plugin->url('/assets/images/salt.png')).'" class="screenshot" />'."\n";
            echo '      <h3>'.__('<i class="si si-flask"></i> <span style="display:inline-block; padding:5px; border-radius:3px; background:#FFFFFF; color:#354913;"><span style="font-weight:bold; font-size:80%;">GEEK ALERT</span></span> This is for VERY advanced users only...', 'rapid-cache').'</h3>'."\n";
            echo '      <p>'.sprintf(__('<em>Note: Understanding the %1$s <a href="https://github.com/megaoptim/rapid-cache-kb/blob/master/questions/7-what-is-branched-cache-structure.md" target="_blank">Branched Cache Structure</a> is a prerequisite to understanding how Dynamic Version Salts are added to the mix.</em>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '      <p>'.__('A Version Salt gives you the ability to dynamically create multiple variations of the cache, and those dynamic variations will be served on subsequent visits; e.g., if a visitor has a specific cookie (of a certain value) they will see pages which were cached with that version (i.e., w/ that Version Salt: the value of the cookie). A Version Salt can really be anything.', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('A Version Salt can be a single variable like <code>$_COOKIE[\'my_cookie\']</code>, or it can be a combination of multiple variables, like <code>$_COOKIE[\'my_cookie\'].$_COOKIE[\'my_other_cookie\']</code>. (When using multiple variables, please separate them with a dot, as shown in the example.)', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('Experts could even use PHP ternary expressions that evaluate into something. For example: <code>((preg_match(\'/iPhone/i\', $_SERVER[\'HTTP_USER_AGENT\'])) ? \'iPhones\' : \'\')</code>. This would force a separate version of the cache to be created for iPhones (e.g., <code>PROTOCOL.HOST.URI[...]v/iPhones.html</code>).', 'rapid-cache').'</p>'."\n";
            echo '      <p>'.__('For more documentation, please see <a href="#" target="_blank">Dynamic Version Salts</a>.', 'rapid-cache').'</p>'."\n";
            echo '      <hr />'."\n";
            echo '      <h3>'.sprintf(__('Create a Dynamic Version Salt For %1$s? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:90%%; opacity:0.5;">150%% OPTIONAL</span>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</h3>'."\n";
            echo '      <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:pre;">PROTOCOL.HOST.URI.v.</td><td><input type="text" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[saveOptions][version_salt]" value="'.esc_attr($this->plugin->options['version_salt']).'" class="monospace" placeholder="$_COOKIE[\'my_cookie\']" /></td><td style="width:1px; font-weight:bold; white-space:pre;"></td></tr></table>'."\n";
            echo '      <p class="info" style="display:block;">'.__('<a href="http://php.net/manual/en/language.variables.superglobals.php" target="_blank">Super Globals</a> work here; <a href="http://codex.wordpress.org/Editing_wp-config.php#table_prefix" target="_blank"><code>$GLOBALS[\'table_prefix\']</code></a> is a popular one.<br />Or, perhaps a PHP Constant defined in <code>/wp-config.php</code>; such as <code>WPLANG</code> or <code>DB_HOST</code>.', 'rapid-cache').'</p>'."\n";
            echo '      <p class="notice" style="display:block;">'.__('<strong>Important:</strong> your Version Salt is scanned for PHP syntax errors via <a href="http://phpcodechecker.com/" target="_blank"><code>phpCodeChecker.com</code></a>. If errors are found, you\'ll receive a notice in the Dashboard.', 'rapid-cache').'</p>'."\n";
            echo '      <p class="info" style="display:block;">'.__('If you\'ve enabled a separate cache for each user (optional) that\'s perfectly OK. A Version Salt works with user caching too.', 'rapid-cache').'</p>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }
        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-panel">'."\n";

        echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
        echo '      <i class="si si-octi-plug"></i> '.__('Theme/Plugin Developers', 'rapid-cache')."\n";
        echo '   </a>'."\n";

        echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
        echo '      <i class="si si-puzzle-piece si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
        echo '      <h3>'.__('Developing a Theme or Plugin for WordPress?', 'rapid-cache').'</h3>'."\n";
        echo '      <p>'.sprintf(__('<strong>Tip:</strong> %1$s can be disabled temporarily. If you\'re a theme/plugin developer, you can set a flag within your PHP code to disable the cache engine at runtime. Perhaps on a specific page, or in a specific scenario. In your PHP script, set: <code>$_SERVER[\'RAPID_CACHE_ALLOWED\'] = FALSE;</code> or <code>define(\'RAPID_CACHE_ALLOWED\', FALSE)</code>. %1$s is also compatible with: <code>define(\'DONOTCACHEPAGE\', TRUE)</code>. It does\'t matter where or when you define one of these, because %1$s is the last thing to run before script execution ends.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
        echo '   </div>'."\n";

        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->plugin->isProPreview()) {
            echo '<div class="plugin-menu-page-panel'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' pro-preview' : '').'">'."\n";

            echo '   <a href="#" class="plugin-menu-page-panel-heading" data-pro-version-only="'.(!MEGAOPTIM_RAPID_CACHE_IS_PRO ? __('pro version only', 'rapid-cache') : '').'">'."\n";
            echo '      <i class="si si-arrow-circle-o-up"></i> '.__('Import/Export Options', 'rapid-cache')."\n";
            echo '   </a>'."\n";

            echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
            echo '      <i class="si si-arrow-circle-o-up si-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
            echo '      <h3>'.sprintf(__('Import Options from Another %1$s Installation?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</h3>'."\n";
            echo '      <p>'.sprintf(__('Upload your <code>%1$s-options.json</code> file and click "Save All Changes" below. The options provided by your import file will override any that exist currently.', 'rapid-cache'), MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'</p>'."\n";
            echo '      <p><input type="file" name="'.esc_attr(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS).'[import_options]" /></p>'."\n";
            echo '      <hr />'."\n";
            echo '      <h3>'.sprintf(__('Export Existing Options from this %1$s Installation?', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</h3>'."\n";
            echo '      <button type="button" class="plugin-menu-page-export-options" style="float:right; margin: 0 0 0 25px;"'.// Exports existing options from this installation.
             '         data-action="'.esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, '_wpnonce' => wp_create_nonce(), MEGAOPTIM_RAPID_CACHE_GLOBAL_NS => ['exportOptions' => '1']]), self_admin_url('/admin.php'))).'">'.
             '         '.__('options.json', 'rapid-cache').' <i class="si si-arrow-circle-o-down"></i></button>'."\n";
            echo '      <p>'.sprintf(__('Download your existing options and import them all into another %1$s installation; saves time on future installs.', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME)).'</p>'."\n";
            echo '   </div>'."\n";

            echo '</div>'."\n";
        }
        /* ----------------------------------------------------------------------------------------- */

        echo '<div class="plugin-menu-page-save">'."\n";
        echo '   <button type="submit">'.__('Save All Changes', 'rapid-cache').' <i class="si si-save"></i></button>'."\n";
        echo '</div>'."\n";

        /* ----------------------------------------------------------------------------------------- */

        echo '</div>'."\n";
        echo '</form>';
    }
}
