// Importing code libraries for this block
import { RichText} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';



/**
 * Editor styles.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
//import './editor.scss';

/**
 * Edit function.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function edit( {
	className,
	attributes,
	setAttributes
} ) {

const {shortcode} = attributes;
	const set_gutenberg_Attrs = (  ) => {
		window.asl_gutenberg_attrs = {
					className,
					attributes,
					setAttributes
				};

	};   
    return(

    	<div className="sl-shortcode-block">

			<Button
				tagName="strong"
				className='components-button is-secondary sl-shortcode-button'
				data-toggle="smodal"
				data-target="#insert-sl-shortcode"
				id="sl-shortcode-insert"
				onClick = { set_gutenberg_Attrs }
			>Add Shortcode</Button>
			<RichText
				tagName='div'

				placeholder= '[ASL_STORELOCATOR]'
				className= 'input-control blocks-shortcode-textarea sl_shortcode_area'
				value= {shortcode}
				onChange = { (value) => setAttributes({shortcode:value }) }
			/>
    	</div>
    );
}