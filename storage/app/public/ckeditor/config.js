/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */


//https://ckeditor.com/latest/samples/toolbarconfigurat..
CKEDITOR.editorConfig = function( config ) {
    config.language = 'ru';
//config.uiColor = '#AADC6E';
    config.toolbar = [
        { name: 'document', items: [ 'Source', '-', 'Preview', '-','Print', '-' ] },
        { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
        { name: 'editing', items: [ 'Find', 'Replace'] },
        '/',
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley',  'SpecialChar', 'PageBreak', 'Iframe', 'Youtube', 'EqnEditor', 'addlatex' ] },
        '/',
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
        { name: 'about', items: [ 'About' ] }
    ];
    config.allowedContent = true;
    config.extraPlugins = 'addlatex, eqneditor';
};

CKEDITOR.config.allowedContent = {
    $1: {
// Use the ability to specify elements as an object.
        elements: CKEDITOR.dtd,
        attributes: true,
        styles: true,
        classes: true
    }
};


CKEDITOR.config.contentsCss = ["/css/bootstrap.css",];
