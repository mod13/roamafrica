<?php

    /**
     * 子菜单 Admin Options
     */
     
    $this->admin_option(array('子菜单', 22), 
        '子菜单', 'menu_secondary_info', 
        'content', 'Please, use the <a href="nav-menus.php"><strong>menus panel</strong></a> to manage and organize menu items for the <strong>Secondary Menu</strong>.<br />The Secondary Menu will display the categories list if no menu is selected from the menus panel. <a href="http://codex.wordpress.org/Appearance_Menus_Screen" target="_blank">More info.</a>'
    );
    
    $this->admin_option('子菜单', 
        '子菜单启用?', 'menu_secondary', 
        'checkbox', $this->options['menus']['menu-secondary']['active'], 
        array('display'=>'inline')
    );
    
     $this->admin_option('子菜单', 
        '下拉设置', 'menu_secondary_drop_down', 
        'content', ''
    );
    
    $this->admin_option('子菜单', 
        '下拉菜单', 'menu_secondary_depth', 
        'text', $this->options['menus']['menu-secondary']['depth'], 
        array('help'=>'下拉菜单的层数. 0 = 无限制', 'display'=>'inline', 'style'=>'width: 80px;')
    );
    
    $this->admin_option('子菜单', 
        '效果', 'menu_secondary_effect', 
        'select', $this->options['menus']['menu-secondary']['effect'],
        array('help'=>'下拉动画效果。', 'display'=>'inline', 'options'=>array('standart' => '标准', 'slide' => '向下滑动', 'fade' => '淡出', 'fade_slide_right' => '从右边淡出及滑动', 'fade_slide_left' => '从左边淡出及滑动'))
    );
    
    $this->admin_option('子菜单', 
        '速度', 'menu_secondary_speed', 
        'text', $this->options['menus']['menu-secondary']['speed'], 
        array('help'=>'下拉动作的速度.', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>毫秒</em>')
    );
    
    $this->admin_option('子菜单', 
        '延迟', 'menu_secondary_delay', 
        'text', $this->options['menus']['menu-secondary']['delay'], 
        array('help'=>'当鼠标停留在子菜单上时，菜单延迟关闭的时间 ', 'display'=>'inline', 'style'=>'width: 80px;', 'suffix'=> ' <em>毫秒</em>')
    );
    
    $this->admin_option('子菜单', 
        '箭头', 'menu_secondary_arrows', 
        'checkbox', $this->options['menus']['menu-secondary']['arrows'], 
        array('help'=>'显示菜单指示箭头', 'display'=>'inline')
    );
    
     $this->admin_option('子菜单', 
        '子菜单底色', 'menu_secondary_shadows', 
        'checkbox', $this->options['menus']['menu-secondary']['shadows'], 
        array('help'=>'显示子菜单的背景颜色', 'display'=>'inline')
    );
    
    
    /**
     * Display 子菜单
     */
     
    if($this->display('menu_secondary')) {
        
        // Register
        register_nav_menu( 'secondary',  __( '子菜单', 'themater' ) );
        
        // Display Hook
        $this->add_hook($this->options['menus']['menu-secondary']['hook'], 'themater_menu_secondary_display');

        function themater_menu_secondary_scripts() {
            wp_enqueue_script( 'hoverIntent', THEMATER_URL . '/js/hoverIntent.js', array('jquery') );
            wp_enqueue_script( 'superfish', THEMATER_URL . '/js/superfish.js', array('jquery') );
        }
        add_action('wp_enqueue_scripts', 'themater_menu_secondary_scripts'); 
        
        $this->custom_js(themater_menu_secondary_js());
    }
    
    /**
     * 子菜单 Functions
     */
    
    function themater_menu_secondary_display()
    {
        global $theme;
        ?>
			<?php wp_nav_menu( 'depth=' . $theme->get_option('menu_secondary_depth') . '&theme_location=' . $theme->options['menus']['menu-secondary']['theme_location'] . '&container_class=' . $theme->options['menus']['menu-secondary']['wrap_class'] . '&menu_class=' . $theme->options['menus']['menu-secondary']['menu_class'] . '&fallback_cb=' . $theme->options['menus']['menu-secondary']['fallback'] . ''); ?>
              <!--.子菜单--> 	
        <?php
    }
    
    function themater_menu_secondary_default()
    {
        global $theme;
        ?>
        <div class="<?php echo $theme->options['menus']['menu-secondary']['wrap_class']; ?>">
			<ul class="<?php echo $theme->options['menus']['menu-secondary']['menu_class']; ?>">
				<?php wp_list_categories('depth=' .  $theme->get_option('menu_secondary_depth') . '&hide_empty=0&orderby=name&show_count=0&use_desc_for_title=1&title_li='); ?>
			</ul>
		</div>
        <?php
    }
    
    function themater_menu_secondary_js()
    {
        global $theme;

        $return = '';
        
            $menu_secondary_arrows = $theme->display('menu_secondary_arrows') ? 'true' : 'false';
            $menu_secondary_shadows = $theme->display('menu_secondary_shadows') ? 'true' : 'false';
            $menu_secondary_delay = $theme->display('menu_secondary_delay') ? $theme->get_option('menu_secondary_delay') : '800';
            $menu_secondary_speed = $theme->display('menu_secondary_speed') ? $theme->get_option('menu_secondary_speed') : '200';
            
            switch ($theme->get_option('menu_secondary_effect')) {
                case 'standart' :
                $menu_secondary_effect = "animation: {width:'show'},\n";
                break;
                
                case 'slide' :
                $menu_secondary_effect = "animation: {height:'show'},\n";
                break;
                
                case 'fade' :
                $menu_secondary_effect = "animation: {opacity:'show'},\n";
                break;
                
                case 'fade_slide_right' :
                $menu_secondary_effect = "onBeforeShow: function(){ this.css('marginLeft','20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                case 'fade_slide_left' :
                $menu_secondary_effect = "onBeforeShow: function(){ this.css('marginLeft','-20px'); },\n animation: {'marginLeft':'0px',opacity:'show'},\n";
                break;
                
                default:
                $menu_secondary_effect = "animation: {opacity:'show'},\n";
            }
            
            $return .= "jQuery(function(){ \n\tjQuery('ul." . $theme->options['menus']['menu-secondary']['superfish_class'] . "').superfish({ \n\t";
            $return .= $menu_secondary_effect;
            $return .= "autoArrows:  $menu_secondary_arrows,
                dropShadows: $menu_secondary_shadows, 
                speed: $menu_secondary_speed,
                delay: $menu_secondary_delay
                });
            });\n";
   
        return $return;
    }
?>