<?php

/**
 * Plugin Name: Google Trends & Charts
 * Plugin URI:  http://internet-pr-beratung.de/google-trends-wordpress/ 
 * Description: Das Plugin gibt Google Trends Graphen per Shortcode aus, zudem kannst Du die Top-Suchanfragen bei Google in einem Widget und im Dashboard ausgeben.
 * Version:     1.1
 * Author:      Sammy Zimmermanns
 * Author URI:  http://internet-pr-beratung.de
 * License:     GPL-2.0+
 */
 /*  Copyright 2014  Sammy Zimmermanns  (email : zimmermanns@internet-pr-beratung.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//The Google Trends Shortcode

function google_trends_charts_sc($atts){
        extract( shortcode_atts( array(
                'w' => '500',           // width of the graph
                'h' => '330',           // height of the graph
                'q' => '',              // query separated by comas
                'geo' => 'de',          // location
        ), $atts ) );
        
        //format input
        
        $h=(int)$h;
        $w=(int)$w;
        $q=esc_attr($q);
        $geo=esc_attr($geo);
        ob_start();
?>
<script type="text/javascript" src="http://www.google.de/trends/embed.js?hl=de-DE&q=<?php echo $q;?>&geo=<?php echo $geo;?>&cmpt=q&content=1&cid=TIMESERIES_GRAPH_0&export=5&w=<?php echo $w;?>&h=<?php echo $h;?>"></script>
<?php
return ob_get_clean();
}
add_shortcode("trends","google_trends_charts_sc");
?>
<?php
//The Google Trends Top-Searches Shortcode

function topsearches($atts, $content = null) {
       extract( shortcode_atts( array(
	   'w' => '250',           // width of the graph
       'h' => '413',           // height of the graph
       'lang' => 'de',
       'pn' => '15',              // query separated by comas
       'tn' => '10',          // location
        ), $atts ) );
		
		//format input
        
        $h=(int)$h;
        $w=(int)$w;
        $lang=esc_attr($lang);
        $pn=esc_attr($pn);
        $tn=esc_attr($tn);
        
?>
  <iframe scrolling="no" style="border:none;" width="<?php echo $w;?>" height="<?php echo $h;?>" src="http://www.google.<?php echo $lang;?>/trends/hottrends/widget?pn=p<?php echo $pn;?>&amp;tn=<?php echo $tn;?>&amp;h=<?php echo $h;?>"></iframe>
 <?php

}

add_shortcode('topsearches', 'topsearches' );
add_filter('widget_text', 'do_shortcode', 11);
?>
<?php
//The widget code starts here
function widget_topsearches($args) {
    extract($args);
?>	
        <?php echo $before_widget; ?>
            <?php echo $before_title
                . 'Google Top-Suchanfragen'
                . $after_title; ?>
           <iframe scrolling="no" style="border:none;" width="250" height="413" src="http://www.google.de/trends/hottrends/widget?pn=p15&amp;tn=10&amp;h=413"></iframe>
        <?php echo $after_widget; ?>
<?php
}
register_sidebar_widget('Google Trends & Charts',
    'widget_topsearches');
?>
<?php
/**Google Trends Top-Searches Dashboard Widget */
if (is_admin()){
  add_action('wp_dashboard_setup', 'add_topsearches_widget');
}

function add_topsearches_widget() {
   wp_add_dashboard_widget('topsearches_dashboard_widget',
                           'Google Trends Top-Suchbegriffe des Tages',
                           'insert_topsearches_dashboard_widget_data'
                          );
}
function insert_topsearches_dashboard_widget_data() {
  // Informationen über den aktuellen Benutzer ermitteln.
  echo '<iframe scrolling="no" style="border:none;" width="250" height="413" src="http://www.google.de/trends/hottrends/widget?pn=p15&amp;tn=10&amp;h=413"></iframe>';
}
?>
<?php
/** Admin Panel */
add_action( 'admin_menu', 'google_trends_charts_menu' );

function google_trends_charts_menu() {
	add_options_page( 'Google Trends & Charts Options', 'Google & Trends Charts', 'manage_options', 'google-trends-charts', 'google_trends_charts_options' );
}

function google_trends_charts_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
        echo '<h1>Shortcode Beispiele:</h1>';
        echo '<code>[trends h="500" w="500" q="katzen,hunde,+süß+hunde,+süß+katze,+katzen+und+hunde" geo="de"]</code>';
	echo '</div>';      
        echo '<table>
<tbody>
<tr>
<td>
<h2>Shortcode für Top Suchbegriffe des Tages</h2>
</td>
</tr>
<tr>
<td><code>[topsearches h=826 w=400 pn=1 tn=20]</code></td>
</tr>
<tr>
<td>h = Höhe in Pixeln
W = Breite in Pixeln
pn = Das Land
tn = Anzahl der Suchbegriffe</td>
</tr>
<tr>
<td>
<h3>Länder Variablen</h3>
</td>
</tr>
</tbody>
</table>
<table>
<tbody>
<tr>
<td>
pn=1 USA<br />
pn=3 Indien<br />
pn=4 Japan<br />
pn=5 Singapur<br />
pn=6 Israel<br />
pn=8 Australien<br />
pn=9 Vereinigtes Königreich<br />
pn=10 Hongkong<br />
pn=12 Taiwan<br />
pn=13 Kanada<br />
pn=14 Russische Föderation<br />
pn=15 Deutschland<br />
pn=16 Frankreich<br />
pn=17 Niederlande<br />
pn=18 Brasilien<br />
pn=19 Indonesien<br /></td>
<td>pn=21 Mexiko<br />
pn=23 Republik Korea<br />
pn=24 Türkei<br />
pn=25 Philippinen<br />
pn=26 Spanien<br />
pn=27 Italien<br />
pn=28 Vietnam<br />
pn=29 Ägypten<br />
pn=30 Argentinien<br />
pn=31 Polen<br />
pn=32 Kolumbien<br />
pn=34 Malaysia<br />
pn=35 Ukraine<br />
pn=36 Saudi-Arabien<br />
pn=37 Kenia<br /></td>
<td>pn=38 Chile<br />
pn=39 Rumänien<br />
pn=40 Südafrika<br />
pn=41 Belgien<br />
pn=42 Schweden<br />
pn=43 Tschechische Republik<br />
pn=44 Österreich<br />
pn=45 Ungarn<br />
pn=46 Schweiz<br />
pn=47 Portugal<br />
pn=48 Griechenland<br />
pn=49 Dänemark<br />
pn=50 Finnland<br />
pn=51 Norwegen<br />
pn=52 Nigeria</td>
</tr>
</tbody>
</table>';
        echo '<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><h2>Autor</h2></th>
					<td>
						<p>
							<a href="http://internet-pr-beratung.de">
								<img class="sgnde-about-logo" src="/wp-content/plugins/simple-google-news-de/images/internet-pr-beratung-logo.png" alt="Zimmermanns Internet & PR-Beratung">
							</a>
						</p>
						<p>
							Sammy Zimmermanns<br>Waldheimer Str. 16a<br>01159 Dresden						</p>
						<p>
							E-Mail: <a href="mailto:zimmermanns@internet-pr-beratung.de">zimmermanns@internet-pr-beratung.de</a><br>Website: <a title="internet-pr-beratung.de" href="http://internet-pr-beratung.de">internet-pr-beratung.de</a>						</p>
					</td>
				</tr>
			

			</tbody>
		</table>';
	echo '</div>';
}
?>