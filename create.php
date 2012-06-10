<title>XWIS Signature Creator 0.2</title>
<script src="http://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript"></script>
<style type="text/css">
body, td, p {
	font: normal 13px "Segoe UI",Segoe,helvetica,arial,sans-serif;
	color: #5A5A5A;
}
div#sig-code {
	color: black;
	white-space: pre-wrap;
	background: #FAF8F0;
	padding: 5px;
	border: 1px solid #BEBAB0;
	height: 21px;
	margin-bottom: 15px;
}
select, input {
	width: 120px;
}

input#sig-submit {
	width: auto;
	margin-top: 10px;
}

tr td:first-child {
	width: 50px;
}

h2 {
	margin-top: 0;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('form#signature').submit(function(){
		var n =  $('input#sig-name').val(), g = $('select#sig-game :selected').val(), f = $('select#sig-faction :selected').val();
		var s1 = $('select#stat1 :selected').val(), s2 = $('select#stat2 :selected').val();
		if (n.length < 3) { return false; }
		var uri = 'http://xwis.co.uk/sig/'+ n.toLowerCase() +'/'+ g +'/'+ f +'/'+ s1 +'/'+ s2 +'.png' 
		document.getElementById('sig-preview').innerHTML = '<img src="'+ uri +'" />';
		document.getElementById('sig-code').innerHTML = '[url=http://xwis.net/'+ g +'/pl/'+ n.toLowerCase() +'/][img]'+ uri +'[/img][/url]';
		return false;
	});
});
</script>
<h1>XWIS Signature Creator</h1>

<div style="float:left; margin-bottom: 15px; padding-right: 50px; border-right: 1px solid grey;">
	<form id="signature">
		<table>
		<tr>
			<td>
				Nick:
			</td>
			<td>
				<input type="text" id="sig-name" maxlength="9"/>
			</td>
		</tr>
		<tr>
			<td>
				Game:
			</td>
			<td>
				<select id="sig-game">
					<option value="ra2">Red Alert 2</option>
					<option value="yr">Yuri's Revenge</option>
					<option value="ts">Tiberian Sun</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Design:
			</td>
			<td>
				<select id="sig-faction">
					<option value="allied">Allies</option>
					<option value="soviet">Soviet</option>
					<option value="yr">Yuri's Revenge</option>
					<optgroup label="GDI">
						<option value="gdi">Modern</option>
						<option value="gdic">Classic</option>
					</optgroup>
					<optgroup label="Nod">
						<option value="nod">Modern</option>
						<option value="nodc">Classic</option>
					</optgroup>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Stat 1:
			</td>
			<td>
				<select id="stat1">
					<option value="la" selected>Ladder Position</option>
					<option value="wi">Total Wins</option>
					<option value="lo">Total Losses</option>
					<option value="po">Points</option>
					<option value="di">Disconnects</option>
					<option value="re">Reconnects</option>
					<option value="co">Favorite Country</option>
					<option value="ma">Favorite Map</option>
					<option value="fp">Average FPS</option>
					<option value="ti">Time Played</option>
					<option value="ra">Win/Loss Ratio</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Stat 2:
			</td>
			<td>
				<select id="stat2">
					<option value="la">Ladder Position</option>
					<option value="wi">Total Wins</option>
					<option value="lo">Total Losses</option>
					<option value="po">Points</option>
					<option value="di">Disconnects</option>
					<option value="re">Reconnects</option>
					<option value="co">Favorite Country</option>
					<option value="ma">Favorite Map</option>
					<option value="fp">Average FPS</option>
					<option value="ti">Time Played</option>
					<option value="ra" selected>Win/Loss Ratio</option>
				</select>
			</td>
		</tr>
		</table>
		<input type="submit" id="sig-submit" value="Generate Signature">
	</form>
</div>

<div style="float:left; margin-left: 50px; margin-top: -15px; width: 800px;">
	<h2>Preview</h2>
	<div id="sig-preview"></div>
	<h2>Signature Code</h2>
	<div id="sig-code"></div>
</div>

<div style="clear:both;">
	<h2>About</h2>
	<p>
		Created by Sean3z; service restored under new domain.
	</p>
</div>
