/**
 * @file insert Non-Breaking SPace for CKEditor
 * Copyright (C) 2014 Alfonso Martínez de Lizarrondo
 * Create a command and enable the Ctrl+Space shortcut to insert a non-breaking space in CKEditor
 *
 */

CKEDITOR.plugins.add( 'nbsp',
{
    icons: 'nonbreakingspace',
	init : function( editor )
	{
		// Insert &nbsp;
		editor.addCommand( 'insertNbsp', {
			exec: function( editor ) {
				editor.insertHtml( '&nbsp;' );
			}
		});
		
		
        editor.ui.addButton( 'NonBreakingSpace', {
            label: 'Non Breaking Space',
            command: 'insertNbsp',
            toolbar: 'insert'
        });
		
	}

} );
