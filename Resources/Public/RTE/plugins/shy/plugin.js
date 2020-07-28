/**
 * @file insert Soft Hyphen for CKEditor
 * Copyright (C) 2017 Michael Nuﬂbaumer <m.nussbaumer@go-west.at>
 * Create a button to insert &shy
 *
 */

CKEDITOR.plugins.add( 'shy',
{
    icons: 'softhyphen',
    entities: 'shy',
	init : function( editor )
	{
		// Insert &shy;
		editor.addCommand( 'insertShy', {
    entities: 'shy',
			exec: function( editor ) {
				editor.insertHtml( '&shy;' );
			}
		});
		
		
        editor.ui.addButton( 'SoftHyphen', {
            label: 'Soft Hyphen',
            command: 'insertShy',
            toolbar: 'insert'
        });
		
	}

} );
