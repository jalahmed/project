/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        config.toolbar = 'Full';
config.protectedSource.push(/<\?[\s\S]*?\?>/g);
config.toolbar_Full =
[
    ['Source'],
    ['Undo','Redo'],
    ['Bold','Italic','Underline','Strike'],
    ['NumberedList','BulletedList'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Table','HorizontalRule','Smiley','SpecialChar'],
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor']
];

config.toolbar_Basic =
[
    ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
];



};
