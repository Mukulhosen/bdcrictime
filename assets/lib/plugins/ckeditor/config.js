CKEDITOR.editorConfig = function (config) {
    var uploadUrl = $("#upload_url").val();
    config.allowedContent = true;
    config.removeFormatAttributes = '';
    CKEDITOR.dtd.$removeEmpty['i'] = false;
    var uploadUrl = $("#upload_url").val();

    //'assets/lib/ckeditor/plugins/kcfinder/browse.php?type=files';
    config.filebrowserBrowseUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl 	= uploadUrl;
    config.filebrowserImageUploadUrl 	= uploadUrl;
    config.filebrowserFlashUploadUrl 	= uploadUrl;
};
