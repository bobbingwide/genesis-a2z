/**
 * editor.js from Bill Erickson's documentation
 * This needs to be enqueued in functions.php
 */


wp.domReady( () => {
    wp.blocks.registerBlockStyle( 'core/list', [
        {
            name: 'default',
            label: 'Default',
            isDefault: true,
        },
        {
            name: 'cw250',
            label: 'Column Width 250',
        },
        {
            name: 'cw150',
            label: 'Column Width 150',
        }
    ]);
} );