<?php
/**
 * Setting the default admin theme options and menus
*/

    /*********************************************
     * 标识来源 Options
     *********************************************
    */

        // 标识来源 Settings
        $this->admin_option('标识来源',
            '标识来源', 'themater_logo_source', 
            'callback', 'image', 
            array('callback' =>'themater_logo_source')
        );
        
        $this->admin_option('标识来源', 
            'Logo Image Wrap', 'themater_logo_iamge_wrap', 
            'raw', '<div id="themater_logo_image">', 
            array('display'=>'clean')
        );
            
        $this->admin_option('标识来源', 
            'Logo', 'logo', 
            'imageupload', get_template_directory_uri()  . "/images/logo.png", 
            array('display' => 'minimal', 'help' => "Enter the full url to your logo image or upload now.")
        );
        
        $this->admin_option('标识来源', 
            'Logo Image Wrap End', 'themater_logo_iamge_wrap_end', 
            'raw', '</div>', 
            array('display'=>'clean')
        );
        
        $this->admin_option('标识来源', 
            'Logo Text Wrap', 'themater_logo_text_wrap', 
            'raw', '<div id="themater_logo_text">', 
            array('display'=>'clean')
        );
            
        $this->admin_option('标识来源',
            '网站标题', 'site_title', 
            'text', get_bloginfo('name'),
            array('display'=>'inline')
        );
        
        $this->admin_option('标识来源',
            '网站描述', 'site_description', 
            'text', '', 
            array('display'=>'inline')
        );
        
        $this->admin_option('标识来源', 
            'Logo Text Wrap End', 'themater_logo_text_wrap_end', 
            'raw', '</div>', 
            array('display'=>'clean')
        );
            
        $this->admin_option('标识来源', 
            'Favicon', 'favicon', 
            'imageupload', get_template_directory_uri() . "/images/favicon.png", 
            array('help' => "Enter the full url to your favicon file. Leave it blank if you don't want to use a favicon.")
        );
        
        $this->admin_option('标识来源',
            'Email', 'contact_form_email', 
            'text', get_option('admin_email'),
            array('display' => 'extended', 'help' => '信息提交的联络表格将被发送到这个电子邮件地址。')
        );
        
    
    /*********************************************
     * 布局选项 Options
     *********************************************
    */
 
        $this->admin_option('布局选项',
            '"更多阅读" 文字', 'read_more', 
            'text', 'Read More'
        );
        
        
        $this->admin_option('布局选项', 
            '图片选项', 'featured_image_settings', 
            'content', ''
        );
        
        $this->admin_option('布局选项', 
            'Featured Image Options Homepage', 'featured_image_settings_homepage', 
            'raw', '<b>&raquo;</b> 选择的特色图片将会出现在（网页，类别页面，标签页，搜索结果和存档页）.<br /><br />'
        );
        
        $this->admin_option('布局选项',
            '图片宽度', 'featured_image_width', 
            'text', '200', 
            array('display'=>'inline', 'style'=>'width: 100px;', 'suffix'=>' px.')
        );
        
        $this->admin_option('布局选项',
            '图片高度', 'featured_image_height', 
            'text', '160', 
            array('display'=>'inline', 'style'=>'width: 100px;', 'suffix'=>' px.')
        );
        
        $this->admin_option('布局选项',
            '图片位置', 'featured_image_position', 
            'radio', 'alignleft', 
            array('options'=>array('alignleft' => '左边', 'alignright'=> '右边', 'aligncenter'=>'中心') , 'display'=>'inline')
        );
        
        $this->admin_option('布局选项', 
            'Featured Image Options Single', 'featured_image_settings_single', 
            'raw', '<b>&raquo;</b> 选择的特色图片将会出现在单页面里.<br /><br />'
        );
        
        $this->admin_option('布局选项',
            '图片宽度', 'featured_image_width_single', 
            'text', '300', 
            array('display'=>'inline', 'style'=>'width: 100px;', 'suffix'=>' px.')
        );
        
        $this->admin_option('布局选项',
            '图片高度', 'featured_image_height_single', 
            'text', '225', 
            array('display'=>'inline', 'style'=>'width: 100px;', 'suffix'=>' px.')
        );
        
        $this->admin_option('布局选项',
            '图片位置', 'featured_image_position_single', 
            'radio', 'alignleft', 
            array('options'=>array('alignleft' => '左边', 'alignright'=> '右边', 'aligncenter'=>'中心') , 'display'=>'inline')
        );
        
        $this->admin_option('布局选项', 
            '自定义底部文本', 'footer_custom_text', 
            'textarea', '', 
            array('help' => '添加自定义底部文本。将覆盖默认主题生成文本。.', 'display'=>'extended-top', 'style'=>'height: 140px;')
        );

    /*********************************************
     * 自定义代码
     *********************************************
    */
        $this->admin_option('自定义代码',
            'RSS订阅网址', 'rss_url', 
            'text', '', 
            array('help' => '输入您的自定义订阅网址，Feed Burner或其他.', 'display'=>'extended-top')
        );
        
        $this->admin_option('自定义代码',
            '自定义CSS', 'custom_css', 
            'textarea', '', 
            array('help' => '在这里添加任何代码将出现在头部分的每一页你的网站。只添加的代码没有&lt;style&gt;&lt;/style&gt;风格，它们是自动插入.', 'display'=>'extended-top', 'style'=>'height: 180px;')
        );
        
        $this->admin_option('自定义代码',
            '头部代码', 'head_code', 
            'textarea', '', 
            array('help' => '您在此处添加任何代码将出现在头部，在您的网站页面的 &lt;/head&gt; .', 'display'=>'extended-top', 'style'=>'height: 180px;')
        );
        
        $this->admin_option('自定义代码',
            '底部代码', 'footer_code', 
            'textarea', '', 
            array('help' => '您在此处添加任何代码将出现在底部，在您的网站页面的.&lt;/body&gt;.', 'display'=>'extended-top', 'style'=>'height: 180px;')
        );
        
    
   /*********************************************
     * 广告
     *********************************************
    */

    $this->admin_option('广告', 
        '头部横幅广告', 'header_banner', 
        'textarea', '', 
        array('help' => 'Enter your 468x60 px. ad code. You may use any html code here, including your 468x60 px Adsense code.', 'style'=>'height: 120px;')
    ); 
    
    /*********************************************
     * Reset Options
     *********************************************
    */
    
    $this->admin_option('重置主题',
        '重置主题选项', 'reset_options', 
        'content', '
        <div id="fp_reset_options" style="margin-bottom:40px; display:none;"></div>
        <div style="margin-bottom:40px;"><a class="button-primary tt-button-red" onclick="if (confirm(\'All the saved settings will be lost! Do you really want to continue?\')) { themater_form(\'admin_options&do=reset\', \'fpForm\',\'fp_reset_options\',\'true\'); } return false;">开始重置</a></div>', 
        array('help' => '将主题选项重置为默认值. <span style="color:red;"><strong>注：</strong> 所有以前保存的设置都将丢失！</span>', 'display'=>'extended-top')
    );
    
    /*********************************************
     * Support
     *********************************************
    */
    $get_theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
    $this->admin_option('主题支持',
        'Support', 'support',
        'raw', '<ul>
        <li><strong>Theme:</strong> ' . $get_theme_data['Name'] . ' ' . $get_theme_data['Version']  .' </li>
        <li><strong>Theme Author:</strong> <a href="' . $get_theme_data['AuthorURI'] . '" target="_blank">' . $get_theme_data['Author'] . '</a></li>
        <li><strong>Theme Homepage:</strong> <a href="' . $get_theme_data['URI'] . '" target="_blank">' . $get_theme_data['URI'] . '</a></li>
        <li><strong>Support Forums:</strong> <a href="' . $get_theme_data['AuthorURI'] . '/forum/" target="_blank">' . $get_theme_data['AuthorURI'] . '/forum/</a></li>
        </ul>'
    );
    
    $the_theme_slug_url =  str_replace(' ', '-', trim(strtolower($get_theme_data['Name'])));
    $this->admin_option('标识来源',
        'Link Free Version', 'link_free', 
        'raw', '<div class="tt-notice">您可以通过下面的这个地址去购买此主题 <a href="' . $get_theme_data['AuthorURI'] . '/buy/?theme=' . $the_theme_slug_url . '" target="_blank">' . $get_theme_data['AuthorURI'] . '/buy/?theme=' . $the_theme_slug_url . '</a><br />购买以后可以免费升级，并且升级不会影响您现在对主题的各种设置.</div>', 
        array('priority' => '1')
    ); //the_theme_slug_url
    
    
    /*********************************************
     * FUNCTIONS
     *********************************************
    */
        
    function themater_logo_source()
    {
        global $theme;
        $get_logo_source = $theme->get_option('themater_logo_source');
        $logo_sources = array('image'=> '站标图片', 'text'=> '自定义文本');
        
        foreach($logo_sources as $key=>$val) {
            $logo_source_selected = $get_logo_source == $key ? 'checked="checked"' : '';
            ?>
            <div id="select_logo_source_<?php echo $key; ?>" class="tt_radio_button_container">
                <input type="radio" name="themater_logo_source" value="<?php echo $key; ?>" <?php echo $logo_source_selected; ?> id="logo_source_id_<?php echo $key; ?>" /> <a href="javascript:themater_logo_source_js('<?php echo $key; ?>');" class="tt_radio_button"><?php echo $val; ?></a>
            </div>
            <?php
        }
        ?>
            <script type="text/javascript">
                function themater_logo_source_js(source)
                {
                    $thematerjQ("#themater_logo_image").hide();
                    $thematerjQ("#select_logo_source_image a").removeClass('tt_radio_button_current');
                    $thematerjQ("#select_logo_source_image").find(":radio").removeAttr("checked");
                    
                    $thematerjQ("#themater_logo_text").hide();
                    $thematerjQ("#select_logo_source_text a").removeClass('tt_radio_button_current');
                    $thematerjQ("#select_logo_source_text").find(":radio").removeAttr("checked");
                    
                    
                    $thematerjQ("#themater_logo_"+source+"").fadeIn();
                    $thematerjQ("#select_logo_source_"+source+" a").addClass('tt_radio_button_current');
                    $thematerjQ("#select_logo_source_"+source+"").find(":radio").attr("checked","checked");
                }
                jQuery(document).ready(function(){
                    themater_logo_source_js('<?php echo $get_logo_source; ?>');
                });
                
            </script>
        <?php
    }
    
?>