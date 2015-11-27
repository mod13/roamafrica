<?php

    /**
     * 主菜单 Admin Options
     */
     
    $this->admin_option(array('主菜单', 21), 
        '主菜单', 'menu_primary_info', 
        'content', 'Please, use the <a href="nav-menus.php"><strong>menus panel</strong></a> to manage and organize menu items for the <strong>Primary Menu</strong>.<br />The Primary Menu will display the pages list if no menu is selected from the menus panel. <a href="http://codex.wordpress.org/Appearance_Menus_Screen" target="_blank">More info.</a>'
    );
    
    $this->admin_option('主菜单', 
        '主菜单启用?', 'menu_primary', 
        'checkbox', $this->options['menus']['menu-primary']['active'], 
        array('display'=>'inline')
    );
    
     $this->admin_option('主菜单',
        '下拉设置', 'menu_primary_drop_down', 
        'content', ''
    );
    
    $this->admin_option('主菜单',
        '下拉菜单', 'menu_primary_depth', 
        'text', $this->options['menus']['menu-primary']['depth'], 
        array('help'=>'下拉菜单的层数. 0 = 无限制', 'display'=>'inline', 'style'=>'width: 80px;')
    );
    
    $this->admin_option('主菜单',
        '效果', 'menu_primary_effect', 
        'select', $this->options['menus']['menu-primary']['effect'],
        array('help'=>'下拉动画效果.', 'display'=>'inline', 'options'=>array('standart' => '标准', 'slide' => '向下滑动', 'fade' => '淡出', 'fade_slide_right' => '从右边淡出及滑动', 'fade_slide_left' => '从左边淡出及滑动'))
    );
    
    $this->admin_option('主菜单',
        '速度', 'menu_primary_speed', 
        'text', $this->options['menus']['menu-primary']['speed'], 
        array('help'=>'下拉动作的速度.', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>毫秒</em>')
    );
    
    $this->admin_option('主菜单',
        '延迟', 'menu_primary_delay', 
        'text', $this->options['menus']['menu-primary']['delay'], 
        array('help'=>'当鼠标停留在子菜单上时，菜单延迟关闭的时间 ', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>毫秒</em>')
    );
    
    $this->admin_option('主菜单', 
        '箭头', 'menu_primary_arrows', 
        'checkbox', $this->options['menus']['menu-primary']['arrows'], 
        array('help'=>'显示菜单指示箭头', 'display'=>'inline')
    );
    
     $this->admin_option('主菜单',
        '子菜单底色', 'menu_primary_shadows', 
        'checkbox', $this->options['menus']['menu-primary']['shadows'], 
        array('help'=>'显示子菜单的背景颜色', 'display'=>'inline')
    );
    
    
    /**
     * Display 主菜单
     */
     
    if($this->display('menu_primary')) {
        
        // Register
        register_nav_menu( 'primary',  __( '主菜单', 'themater' ) );
        
        // Display Hook
        $this->add_hook($this->options['menus']['menu-primary']['hook'], 'themater_menu_primary_display');

        function themater_menu_primary_scripts() {
            wp_enqueue_script( 'hoverIntent', THEMATER_URL . '/js/hoverIntent.js', array('jquery') );
            wp_enqueue_script( 'superfish', THEMATER_URL . '/js/superfish.js', array('jquery') );
        }
        add_action('wp_enqueue_scripts', 'themater_menu_primary_scripts'); 
        
        $this->custom_js(themater_menu_primary_js());
    }
    
    /**
     * 主菜单 Functions
     */
    
    function themater_menu_primary_display()
    {
        global $theme;
        ?>
			<?php wp_nav_menu( 'depth=' . $theme->get_option('menu_primary_depth') . '&theme_location=' . $theme->options['menus']['menu-primary']['theme_location'] . '&container_class=' . $theme->options['menus']['menu-primary']['wrap_class'] . '&menu_class=' . $theme->options['menus']['menu-primary']['menu_class'] . '&fallback_cb=' . $theme->options['menus']['menu-primary']['fallback'] . ''); ?>
              <!--.主菜单--> 	
        <?php
    }
    
    function themater_menu_primary_default()
    {
        global $theme;
        ?>
        <div class="<?php echo $theme->options['menus']['menu-primary']['wrap_class']; ?>">
			<ul class="<?php echo $theme->options['menus']['menu-primary']['menu_class']; ?>">
                <li <?php if(is_home() || is_front_page()) { ?>class="current_page_item"<?php } ?>><a href="<?php echo home_url(); ?>"><?php _e('Home','themater'); ?></a></li>
				<?php wp_list_pages('depth=' .  $theme->get_option('menu_primary_depth') . '&sort_column=menu_order&title_li=' ); ?>
			</ul>
		</div>
        <?php
    }
    
    function themater_menu_primary_js()
    {
        global $theme;

        $return = '';
        
            $menu_primary_arrows = $theme->display('menu_primary_arrows') ? 'true' : 'false';
            $menu_primary_shadows = $theme->display('menu_primary_shadows') ? 'true' : 'false';
            $menu_primary_delay = $theme->display('menu_primary_delay') ? $theme->get_option('menu_primary_delay') : '800';
            $menu_primary_speed = $theme->display('menu_primary_speed') ? $theme->get_option('menu_primary_speed') : '200';
            
            switch ($theme->get_option('menu_primary_effect')) {
                case 'standart' :
                $menu_primary_effect = "animation: {width:'show'},\n";
                break;
                
                case 'slide' :
                $menu_primary_effect = "animation: {height:'show'},\n";
                break;
                
                case 'fade' :
                $menu_primary_effect = "animation: {opacity:'show'},\n";
                break;
                
                case 'fade_slide_right' :
                $menu_primary_effect = "onBeforeShow: function(){ this.css('marginLeft','20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                case 'fade_slide_left' :
                $menu_primary_effect = "onBeforeShow: function(){ this.css('marginLeft','-20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                default:
                $menu_primary_effect = "animation: {opacity:'show'},\n";
            }
            
            $return .= "jQuery(function(){ \n\tjQuery('ul." . $theme->options['menus']['menu-primary']['superfish_class'] . "').superfish({ \n\t";
            $return .= $menu_primary_effect;
            $return .= "autoArrows:  $menu_primary_arrows,
                dropShadows: $menu_primary_shadows, 
                speed: $menu_primary_speed,
                delay: $menu_primary_delay
                });
            });\n";
   
        return $return;
    }
?>