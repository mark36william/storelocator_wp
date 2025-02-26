import {RichText} from '@wordpress/block-editor';

/**
 * Save function.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save ({attributes,className}){
        const {
        	shortcode,
        } = attributes;

        return(

	    	<div className="sl-shortcode-block">
				<RichText.Content
					tagName="div"
					className= 'input-control blocks-shortcode-textarea sl_shortcode_area'
					value= {shortcode}
				/>
	    	</div>
	        );
}