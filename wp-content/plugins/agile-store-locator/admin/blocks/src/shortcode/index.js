/**
 * Block 1.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
 import { __ } from '@wordpress/i18n';
 import { registerBlockType } from '@wordpress/blocks';
 import ASLIcon from './icons';


/**
 * Internal dependencies
 */
import edit from './edit';
import save from './save';

let metadata = {
    title: __('Store Locator'),
    icon: ASLIcon,
    category: 'layout',
    keywords: [
        __('Store Locator'),
        __('Google Maps'),
        __('Location Finder'),
        __('Direction'),
        __('Map'),
    ],
    supports: {
        html: false,
        className: false,
        customClassName: false,
    },
    attributes: {
      shortcode: {
        string: 'string',
        source: 'text',
      }
    },
	/**
	 * @see ./edit.js
	 */
	edit,
	
	/**
	 * @see ./save.js
	 */
	save,

};

registerBlockType( 'agile-store-locator/shortcode', metadata );




   