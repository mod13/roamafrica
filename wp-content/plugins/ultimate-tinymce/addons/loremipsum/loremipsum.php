<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{#loremipsum.desc}</title>
    <?php
	$file = dirname(__FILE__);
	$file = substr($file, 0, stripos($file, "wp-content") );
	require( $file . "/wp-load.php");
	$url = includes_url();
	echo '<script type="text/javascript" src="'.$url.'js/tinymce/tiny_mce_popup.js'.'"></script>';
	echo '<script type="text/javascript" src="'.$url.'js/tinymce/utils/mctabs.js'.'"></script>';
	echo '<script type="text/javascript" src="'.$url.'js/tinymce/utils/form_utils.js'.'"></script>';
	?>
    <!--
	<script type="text/javascript" src="../../tinymce/tiny_mce_popup.js"></script>
    -->
	<script type="text/javascript" src="js/loremipsum.js"></script>
	<base target="_self" />
</head>
<body>

<form onsubmit="LoremIpsumDialog.insert();return false;" action="#">
	<div>
		<div id="general_panel" class="panel current">
			<table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><label for="text">{#loremipsum_dlg.sentences}:</label></td>
                        <td nowrap="nowrap">
                            <select name="sentences" id="sentences" style="width: 175px;">
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="amount">{#loremipsum_dlg.amount}:</label></td>
                        <td><input id="amount" name="amount" type="text" size="2" value="5" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="formating">{#loremipsum_dlg.formating}:</label></td>
                        <td nowrap="nowrap">
                            <select name="formating" id="formating" style="width: 175px;">
                                <option value="0">{#loremipsum_dlg.unformated}</option>
                                <option value="1">{#loremipsum_dlg.paragraphs}</option>
                                <option value="2">{#loremipsum_dlg.list}</option>
                                <option value="3">{#loremipsum_dlg.orderedlist}</option>
                            </select>
                        </td>
                    </tr>
            </table>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
		</div>

		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
</body>
</html>
