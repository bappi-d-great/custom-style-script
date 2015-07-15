<?php
/*
  Plugin Name: Custom Style Script
  Plugin URI: http://bappi-d-great.com
  Description: Provides option to add styles and scripts in your website header, footer and admin's header and footer.
  Author: Bappi D Great
  Version: 1.4
  Author URI: http://bappi-d-great.com
  Text Domain: csslang
  Domain Path: /lang/
 */

/*
  Copyright 2013-2014 Bappi D Great (http://bappi-d-great.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
  the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

 
if(!class_exists('Css_class')) {
 class Css_class {
    
    public $domain;
    public $options;
    public $location;
    
    public function __construct() {
        $this->domain = 'csslang';
        $this->location = 'plugins';
        $this->options = get_option('css_options');
        
        add_action('plugin_loaded', array($this, 'localization'));
        add_action('admin_menu', array($this, 'style_script_menu'));
        add_action('admin_init', array($this, 'register_settings_and_fields'));
        add_action('init', array($this, 'set_all_data'));
        add_action('admin_head', array($this, 'plugin_style'));
    }
    
    //Language define
    public function localization() {
        if($this->location == 'plugins') {
            load_plugin_textdomain('csslang', FALSE, '/lang/');
        }
        $temp_locale = explode('_', get_locale());
        $this->language = ($temp_locale) ? $temp_locale[0] : 'en';
    }
    
    public function plugin_style() {
        ?>
        <style>
        .css-panel textarea{
            resize: none;
            width: 99%;
            height: 200px;
        }
        </style>
        <?php
    }
    
    //Adding menu
    public function style_script_menu() {
        add_menu_page(
                      'Custom Style Script',
                      'Style Script',
                      'manage_options',
                      'custom-style-script',
                      array($this, 'custom_style_script_page'),
                      '',
                      101
                      );
    }
    
    //Adding Form
    public function custom_style_script_page() {
        if (!current_user_can('manage_options')) {  
            wp_die(__('You do not have sufficient permissions to access this page.', $this->domain));  
        }
        $this->update_css();
        $this->options = get_option('css_options');
        ?>
        <div class="wrap css-panel">
            <?php screen_icon('themes'); ?>
            <h2><?php _e('Custom Style Script', $this->domain); ?></h2>
            <form method="post" action="">
                <?php wp_nonce_field('css_nonce_update','css_nonce_update_value'); ?>
                <h3><?php _e("Panel for adding styles and scripts in your website", $this->domain); ?></h3>
                <table cellpadding="10" cellspacing="10" width="100%">
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Styles in Website Header'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[style_site_head]"><?php echo (isset($this->options['style_site_head'])) ? stripcslashes($this->options['style_site_head']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;style&gt;&lt;/style&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Styles in Website Footer'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[style_site_footer]"><?php echo (isset($this->options['style_site_footer'])) ? stripcslashes($this->options['style_site_footer']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;style&gt;&lt;/style&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Scripts in Website Header'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[script_site_head]"><?php echo (isset($this->options['script_site_head'])) ? stripcslashes($this->options['script_site_head']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;script&gt;&lt;/script&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Scripts in Website Footer'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[script_site_footer]"><?php echo (isset($this->options['script_site_footer'])) ? stripcslashes($this->options['script_site_footer']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;script&gt;&lt;/script&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                </table>
                <h3><?php _e("Panel for adding styles and scripts in your admin dashboard", $this->domain); ?></h3>
                <table cellpadding="10" cellspacing="10" width="100%">
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Styles in Admin Header'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[style_admin_head]"><?php echo (isset($this->options['style_admin_head'])) ? stripcslashes($this->options['style_admin_head']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;style&gt;&lt;/style&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Styles in Admin Footer'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[style_admin_footer]"><?php echo (isset($this->options['style_admin_footer'])) ? stripcslashes($this->options['style_admin_footer']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;style&gt;&lt;/style&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Scripts in Admin Header'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[script_admin_head]"><?php echo (isset($this->options['script_admin_head'])) ? stripcslashes($this->options['script_admin_head']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;script&gt;&lt;/script&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" valign="top">
                            <?php _e('Add Scripts in Admin Footer'); ?>
                        </td>
                        <td>
                            <textarea name="css_options[script_admin_footer]"><?php echo (isset($this->options['script_admin_footer'])) ? stripcslashes($this->options['script_admin_footer']) : ""; ?></textarea><br>
                            <span><?php _e("You don't need to add &lt;script&gt;&lt;/script&gt; tag.", $this->domain); ?></span>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="update_css_settings" value="Y" />
                <p>
                    <input type="submit" value="Update" class="button-primary"/>
                </p>
            </form>
        </div>
        <?php
    }
    
    public function update_css() {
        if(isset($_POST['update_css_settings']) && $_POST['update_css_settings'] == 'Y') {
            if (!check_admin_referer( 'css_nonce_update', 'css_nonce_update_value' )) {
                wp_die(__('Sorry, your nonce is not verified.', $this->domain));
            }
            else {
                $data = $_POST["css_options"];
                update_option("css_options", $data);
            }
        }
    }
    
    //Rendering data
    public function set_all_data() {
        
        add_action('wp_head', array($this, 'set_wp_head_style_script'));
        add_action('wp_footer', array($this, 'set_wp_footer_style_script'));
        
        add_action('admin_head', array($this, 'set_admin_head_style_script'));
        add_action('admin_footer', array($this, 'set_admin_footer_style_script'));
        
    }
    
    public function set_wp_head_style_script() {
        ?>
        <style type="text/css">
        <?php echo stripcslashes($this->options['style_site_head']); ?>
        </style>
        <script type="text/javascript">
        <?php echo stripcslashes($this->options['script_site_head']); ?>
        </script>
        <?php
    }
    
    public function set_wp_footer_style_script() {
        ?>
        <style type="text/css">
        <?php echo stripcslashes($this->options['style_site_footer']); ?>
        </style>
        <script type="text/javascript">
        <?php echo stripcslashes($this->options['script_site_footer']); ?>
        </script>
        <?php
    }
    
    public function set_admin_head_style_script() {
        ?>
        <style type="text/css">dsd
        <?php echo stripcslashes($this->options['style_admin_head']); ?>
        </style>
        <script type="text/javascript">
        <?php echo stripcslashes($this->options['script_admin_head']); ?>
        </script>
        <?php
    }
    
    public function set_admin_footer_style_script() {
        ?>
        <style type="text/css">
        <?php echo stripcslashes($this->options['style_admin_footer']); ?>
        </style>
        <script type="text/javascript">
        <?php echo stripcslashes($this->options['script_admin_footer']); ?>
        </script>
        <?php
    }
    
 }
}
$var = new Css_class();
 
 