<?php

class Store_locator_wpress_display {
	
	function Store_locator_wpress_display() {
		add_filter('the_content', array(__CLASS__, 'display_linked_store'), 11);
	}
	
	function display_linked_store($content) {
		
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS_posts);
		
		//display linked store only if the option is active
		if($options['linked_stores_display']=='on') {
			$post_id = get_the_ID();
			
			$sdb1 = new Store_locator_wpress_db();
			$stores = $sdb1->return_stores(array('post_id'=>$post_id));
			$stores = self::display_stores_list($stores, array('no_info_links'=>1));
			
			if($options['linked_stores_position']=='top') $content = $stores.$content;
			else $content = $content.'<p>'.$stores.'</p>';
		}
		
		return $content;
	}
	
	function get_store_details_display($stores) {
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS_store_details);
		$width = $options['width'];
		$height = $options['height'];
		$streetview = $options['streetview'];
		$current_url = get_permalink();
		
		$display .= '<style type="text/css">#map img { max-width: none; }</style>';
		
		$streetview_thumbnail = 'http://cbk0.google.com/cbk?output=thumbnail&w=316&h=208&ll='.$stores[0]['lat'].','.$stores[0]['lng'];
		
		if($stores[0]['logo']!='') $display .= '<img src="'.$stores[0]['logo'].'" style="padding-bottom:10px; padding-top:10px;"><br>';
		
		$display .= '<b>'.$GLOBALS['store_locator_lang']['store_name'].':</b> '.$stores[0]['name'].' <small>(<a href="'.$current_url.'">'.$GLOBALS['store_locator_lang']['view_all_stores'].'</a>)</small><br>';
		$display .= '<b>'.$GLOBALS['store_locator_lang']['address'].':</b> '.$stores[0]['address'].'<br>';
		$display .= '<div id="map" style="overflow: hidden; width:'.$width.'; height:'.$height.'; max-width: none;"></div><br>';
		
		if($streetview=='on') $display .= '<div><img src="'.$streetview_thumbnail.'" style="overflow: hidden; width:'.$width.'; height:'.$height.'"></div>';
		//if($streetview=='on') $display .= '<div id="streetview" style="overflow: hidden; width:'.$width.'; height:'.$height.'"></div><br>';
		
		if($stores[0]['url']!='') $display .= '<b>'.$GLOBALS['store_locator_lang']['url'].':</b> <a href="'.$stores[0]['url'].'" target="_blank">'.$stores[0]['url'].'</a><br>';
		if($stores[0]['tel']!='') $display .= '<b>'.$GLOBALS['store_locator_lang']['tel'].':</b> '.$stores[0]['tel'].'<br>';
		if($stores[0]['email']!='') $display .= '<b>'.$GLOBALS['store_locator_lang']['email'].':</b> '.$stores[0]['email'].'<br>';
		if($stores[0]['description']!='') $display .= '<br><b>'.$GLOBALS['store_locator_lang']['description'].'</b><br>'.$stores[0]['description'].'<br>';
		
		return $display;
	}
	
	function display_stores_list($stores,$criteria=array()) {
		$no_info_links = $criteria['no_info_links']; //display info links or no
		$distance_display = $criteria['distance_display']; //display distance or no
		$widget_display = $criteria['widget_display'];
		
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
		
		$current_url = get_permalink();
		
		for($i=0; $i<count($stores); $i++) {
			
			$map_url = 'http://maps.google.com/maps/api/staticmap?center='.$stores[$i]['lat'].','.$stores[$i]['lng'].'&zoom=15&size=160x90&markers=color:red|'.$stores[$i]['lat'].','.$stores[$i]['lng'].'&sensor=false';
			
			if(count($stores)>1) $content .= '<div style="padding-bottom:10px; border-bottom: 1px solid #e7e7e7; overflow:hidden;">';
			else $content .= '<div style="padding-bottom:10px; overflow:hidden;">';
			$content .= '<img src="'.$map_url.'" style="float:left; margin-right:25px; margin-bottom:5px;">';
			
			$content .= '<a href="'.$options['store_locator_link'].'?store_id='.$stores[$i]['id'].'"><b>'.$stores[$i]['name'].'</b></a>';
			
			if($distance_display) $content .= ' (<font color="red">'.number_format($stores[$i]['distance'],1).' '.$options['distance'].'</font>)';
			$content .= '<br>';
			$content .= $stores[$i]['address'].'';
			
			//more info links
			if($no_info_links!=1) {
				$content .= '<br><small><a href="'.$current_url.'?store_id='.$stores[$i]['id'].'">'.$GLOBALS['store_locator_lang']['more_information'].'</a>';
				if($stores[$i]['post_id']>0) {
					$post_url = get_permalink($stores[$i]['post_id']);
					$content .= ' - <a href="'.$post_url.'">'.$GLOBALS['store_locator_lang']['related_post'].'</a>';
				}
				$content .= '</small>';
			}
			
			$content .= '</div>';
			$content .= '<br>';
		}
		return $content;
	}
	
	function display_stores_list2($stores,$criteria=array()) {
		$no_info_links = $criteria['no_info_links']; //display info links or no
		$distance_display = $criteria['distance_display']; //display distance or no
		$widget_display = $criteria['widget_display'];
		
		$options = get_option(STORE_LOCATOR_WPRESS_SETTINGS);
				
		$current_url = get_permalink();
		
		$content .= '<table style="width:100%; padding:0px; margin:0px; border:0px; margin-bottom:10px;">';
		
		$content .= '<tr>
		<th width="33%" style="border:0px; border-bottom: 1px solid #DDDDDD;">Name</th>
		<th width="43%" style="border:0px; border-bottom: 1px solid #DDDDDD;">Address</th>
		<th width="24%" style="border:0px; border-bottom: 1px solid #DDDDDD;">Category</th>
		</tr>';
		
		for($i=0; $i<count($stores); $i++) {
			$id = $stores[$i]['id'];
			$name = $stores[$i]['name'];
			$logo = $stores[$i]['logo'];
			$address = $stores[$i]['address'];
			$url = $stores[$i]['url'];
			$lat = $stores[$i]['lat'];
			$lng = $stores[$i]['lng'];
			$tel = $stores[$i]['tel'];
			$distance = $stores[$i]['distance'];
			$category_name = $stores[$i]['category_name'];
			$marker_icon = $stores[$i]['marker_icon'];
			
			$marker_text = self::getMarkerInfowindowDisplay(array('id'=>$id, 'name'=>$name, 'logo'=>$logo, 'address'=>$address, 'url'=>$url, 'streetview'=>$options['streetview'], 'directions'=>$options['directions'], 'more_details'=>1));
			$content .= '<div style="display:none;" id="infowindow_'.$id.'">'.$marker_text.'</div>';
			$content .= '<div style="display:none;" id="marker_icon_'.$id.'">'.$marker_icon.'</div>';
			
			$content .= '<tr class="displayStoreMap" id="'.$id.'" lat="'.$lat.'" lng="'.$lng.'"
			style="border:0px; cursor:pointer;" onMouseOver="this.style.backgroundColor=\'#eee\'"; onMouseOut="this.style.backgroundColor=\'#fff\'">';
			
				$content .= '<td width="33%" style="padding-right:15px; vertical-align:top; border:0px;">';
					
					$content .= '<a href="'.$current_url.'?store_id='.$id.'">'.$name.'</a>';
					
				$content .= '</td>';
				
				$content .= '<td style="padding-right:15px; vertical-align:top; width:43%; border:0px;">'.$address.'</td>';
				
				$content .= '<td style="padding-right:15px; vertical-align:top; width:24%; border:0px;">'.$category_name.'</td>';
				
			$content .= '</tr>';
		}
		
		$content .= '<tr style="border:0px; margin:0px; padding:0px;">
		<td colspan=3 style="border:0px; border-bottom: 1px solid #DDDDDD; margin:0px; padding:0px;"></td>
		</tr>';
		
		$content .= '</table>';
		
		return $content;
	}
	
	function getMarkerInfowindowDisplay($criteria=array()) {
		$id = $criteria['id'];
		$name = $criteria['name'];
		$address = $criteria['address'];
		$url = $criteria['url'];
		$logo = $criteria['logo'];
		$streetview = $criteria['streetview'];
		$directions = $criteria['directions'];
		$more_details = $criteria['more_details'];
		
		$d .= '<div style="font-size: 12px !important; overflow:hidden !important; padding: 0px !important; margin: 0px !important; color: black !important; font-family: arial,sans-serif !important; line-height: normal !important; width:360px;">';
			
			if($logo!='') {
				if($url!='') $d .= '<a href="'.$url.'" target="_blank">';
				$d .= '<img src="'.$logo.'" align="left" style="padding-right:10px;" border=0>';
				if($url!='') $d .= '</a>';
			}
			
			if($url!='') $d .= '<a href="'.$url.'" target="_blank">';
			$d .= '<b>'.$name.'</b>';
			if($url!='') $d .= '</a>';
			
			$d .= '<br>'.$address;
			
			if($more_details==1 || $streetview=='on') {
				$detail_page = get_permalink();
				$d .= '<div style="margin-top:5px;">';
					if($more_details==1) $d .= '<a href="'.$detail_page.'?store_id='.$id.'">'.$GLOBALS['store_locator_lang']['more_details'].'</a>';
					if($streetview=='on') $d .= ' - <a href="#" id="displayStreetView">'.$GLOBALS['store_locator_lang']['streetview'].'</a>';
				$d .= '</div>';
			}
			
			if($directions=='on') {
				$d .= '<div style="margin-top:5px;">';
				$address = str_replace('<br />', ' ', $address);
				$d .= $GLOBALS['store_locator_lang']['get_directions'].': <a href="http://maps.google.com/maps?f=d&z=13&daddr='.urlencode($address).'" target="_blank">'.$GLOBALS['store_locator_lang']['to_here'].'</a> - <a href="http://maps.google.com/maps?f=d&z=13&saddr='.urlencode($address).'" target="_blank">'.$GLOBALS['store_locator_lang']['from_here'].'</a>';
				$d .= '</div>';
			}
			
		$d .= '</div>';
		
		return $d;
	}
}

new Store_locator_wpress_display();

?>