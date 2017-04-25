{* tutorial simpleinvoices extension *}
<style>{literal}
img {			display: block;	margin: 0 auto;	max-width:100%;	max-height:100%;	}
.indent {		padding-left: 4%;	}
.hideimg, 
.hideimgtog {	color: #bbb;	padding: 0 1%;	border: 1px solid #bbb;	margin: 1%;	cursor: pointer;	display: block;	}
h1, h2 {		border-bottom: 1px solid blue;	padding-top: 2%;	}
h3 {			border-top: 1px solid #ccc;	text-decoration: underline;	width: 80%;	margin: auto;	padding: 1%;	}
hr {			background-color: #ccc;	width: 80%;	margin: 1% auto;	}
</style>{/literal}

<small>current as of April 2017</small>
<br />
<small class="hideimgtog">show/hide all images</small>

<h1>Tutorial</h1>


<h3>Table of Contents</h3>
<ul>
	<li><a href="#Installation">Installation</a>
	<ul>
		<li><a href="#Download">Download</a></li>
		<li><a href="#Upload">Upload - optional</a></li>
		<li><a href="#Database">Database</a></li>
		<li><a href="#Modify">Modify the config</a></li>
		<li><a href="#Install">Install with Browser</a>
		<ul>
			<li><a href="#configured">These items can be configured:</a>
			<li><a href="#sample">install the sample data</a>
		</ul></li>
		<li><a href="#Set">Set-up</a>
		<li><a href="#Notes">Notes</a></li>
	</ul></li>
</ul>
<hr />
<br /><br /><br /><br />
<h2><a name="Installation">Installation</a></h2>


<h3><a name="Download">Download</a></h3>

<small class="hidedlimg hideimg">show/hide all download images</small>

<p>First you must download the application:</p>
<p>I have chosen to download the <a href="https://github.com/fearless359/simpleinvoices">Fearless359 version</a> via Github.</p>
<img src="{$path|dirname|dirname|dirname}/images/download/setDownload.png" alt="set download" class="dl" />
<p>Then press the <a href="https://github.com/fearless359/simpleinvoices/archive/master.zip">Download</a> ZIP button</p>
<img src="{$path|dirname|dirname|dirname}/images/download/download.png" alt="download" class="dl" />
<p>Most browsers will give you the choice to save the file on your computer, or open it using a suitable program.
(I used WinRar to open the archive and extract the files to my computer.)</p>


<h3><a name="Upload">Upload - optional</a></h3>

<small class="hideulimg hideimg">show/hide all upload images</small>

<p>If you plan to place the application on a network, an easy option is to use <a href="https://filezilla-project.org/">FileZilla</a> which will use FTP to upload the files.</p>
<img src="{$path|dirname|dirname|dirname}/images/download/filezilla.png" alt="FileZilla" class="ul" />
<p>This may take a while to complete.</p>


<h3><a name="Database">Database</a></h3>

<small class="hidedbimg hideimg">show/hide all databse images</small>

<p>You need a database for the application:</p>
<ul>
	<li>Login to your host - if appliable - and find the database host/server name (usually 'localhost')
		{*<img src="{$path|dirname|dirname|dirname}/images/database/serverName.png" alt="serverName" />*}</li>
	<li>create a blank database
		<img src="{$path|dirname|dirname|dirname}/images/database/makeNewDB.png" alt="makeNewDB" class="db" /></li>
</ul>
<p>(Enter the database details, yours will probably be different.)</p>
<p>If you try to visit it in your browser, only a message will show - unless your database is configured link in the standard config file.</p>
<img src="{$path|dirname|dirname|dirname}/images/database/webSiteDBsettings.png" alt="webSiteDBsettings" class="db" /></li>
<p>So the next step is important.</p>


<h3><a name="Modify">Modify the config</a></h3>

<small class="hidecfgimg hideimg">show/hide all config images</small>

<p>Now you need to let the application how to connect to the database.<p>
<p>Load the config.php, within the config directory, into your text editor.</p>
<img src="{$path|dirname|dirname|dirname}/images/install/config-php.png" alt="config-php" class="cfg" />
<p>Change the "database.params..." to reflect you database configuration.
(Most settings will not need to be changed, eg the port number is usually always 3306.)</p>
<p>If you use FileZilla, a config prompt may appear.</p>
<img src="{$path|dirname|dirname|dirname}/images/install/confirmFTP.png" alt="confirmFTP" class="cfg" />


<h3><a name="Install">Install with Browser</a></h3>

<small class="hidebrimg hideimg">show/hide all browser install images</small>

<p>Open your web browser and type the application url.
(Usually it will be "http://", then your "database.params.host", then the instation directory - no need to put a file name.
eg. http://localhost/simpleinvoices )</p>
<p>The main installation screen should appear, like so</p>
<img src="{$path|dirname|dirname|dirname}/images/install/start.png" alt="start" class="br" />
<p>(I have blurred the database settings, because yours are most likely different.)</p>
<br />
<p>Next the essential data is put into the database.</p>
<img src="{$path|dirname|dirname|dirname}/images/install/essentialData.png" alt="essentialData" class="br" />
<p>Click 'Install Essential Data' button.</p>
<p>Next you will be asked if you want sample data is put into the database.</p>
<img src="{$path|dirname|dirname|dirname}/images/install/sampleData.png" alt="sampleData" class="br" />

<p>If you choose to 'Start using SimpleInvoices', the initial screen, the dashboard will appear.</p>
<div class="indent">
	<img src="{$path|dirname|dirname|dirname}/images/install/sampleDataNo.png" alt="sampleDataNo" class="br" />
</div>
<p>&nbsp;</p>
<a name="configured">
<p>These items can be configured:</p></a>
<small class="hidecfgdimg hideimg">show/hide all configure images</small>

<div class="indent">
	<img src="{$path|dirname|dirname|dirname}/images/setup/addNew.png" alt="addNew" class="cfgd" />

	<p>Add an initial invoice biller (payee).</p>
	<img src="{$path|dirname|dirname|dirname}/images/setup/addNewBiller.png" alt="addNewBiller" class="cfgd" />

	<p>Add an initial Customer (payer).</p>
	<img src="{$path|dirname|dirname|dirname}/images/setup/addNewCust.png" alt="addNewCust" class="cfgd" />

	<p>Add an initial Product.</p>
	<img src="{$path|dirname|dirname|dirname}/images/setup/addNewProd.png" alt="addNewProd" class="cfgd" />

	<p>Add an initial Invoice.</p>
	<img src="{$path|dirname|dirname|dirname}/images/setup/addNewInv.png" alt="addNewInv" class="cfgd" />

	<p>Settings.</p>
	<img src="{$path|dirname|dirname|dirname}/images/setup/doSettings.png" alt="doSettings" class="cfgd" />
</div>
<p>&nbsp;</p>
<a name="sample">
<p>Otherwise if you install the sample data, it will display a message telling you it was done.</p></a>
<small class="hidesamimg hideimg">show/hide all sample data images</small>

<div class="indent">
	<img src="{$path|dirname|dirname|dirname}/images/install/sampleDataYes.png" alt="sampleDataYes" class="sam" />
	<img src="{$path|dirname|dirname|dirname}/images/install/sampleDataDone.png" alt="sampleDataDone" class="sam" />
	<p>And the page will automatically bring the application to the list of invoices, in Money menu.</p>
	<img src="{$path|dirname|dirname|dirname}/images/money-invoices.png" alt="money-invoices" class="sam" />
</div>
<p>&nbsp;</p>


<h2><a name="Set">Set-up</a></h2>


<small class="hidesetimg hideimg">show/hide all set-up images</small>

<p>Now you have at least one Biller, Customer, Product and have probably created an invoice.
(May be you have also adjusted some settings.)</p>
<p>If not <a href="#configured">Click here to learn how to configure them</a>.</p>
<p>So far, everything have been done under the demo user, as indicated on the top bar.</p>
<img src="{$path|dirname|dirname|dirname}/images/setup/userName.png" alt="userName" class="set" />
<p>That is fine if there is only one user and no passwords are required.
Otherwise, create another user, on this screen.</p>
<img src="{$path|dirname|dirname|dirname}/images/setup/addNewUser.png" alt="addNewUser" class="set" />
<p>Also you need to tell simpleinvoices to use authentication.<p>
<p>Load the config.php, within the config directory, into your text editor.</p>
<img src="{$path|dirname|dirname|dirname}/images/install/config-php.png" alt="config-php" class="set" />
<p>Change the "authentication.enabled" option to true.
<p>When this option is saved and an application page is reload or navigated to, this screen will appear.</p>
<img src="{$path|dirname|dirname|dirname}/images/login.png" alt="login" class="set" />
<p>Also this will be displayed when the session times-out.</p>


<h2><a name="Notes">Notes</a></h2>


<small class="hidenimg hideimg">show/hide all notes images</small>

<p>The user's email address is used during login.</p>
<img src="{$path|dirname|dirname|dirname}/images/setup/userEmail.png" alt="userEmail" class="n" />
<br />
<p>Each Biller can have a logo and they are stored in the templates/invoices/logos directory. Selection here,</p>
<img src="{$path|dirname|dirname|dirname}/images/setup/addBillerLogo.png" alt="addBillerLogo" class="n" />
<br />
<p>Extensions are stored in the extensions directory.  The list can be viewed by selecting Extensions from Settings>Settings.</p>
<img src="{$path|dirname|dirname|dirname}/images/setup/doExtensions.png" alt="doExtensions" class="n" />

<p>Extensions can be regeristed if not regeristed, by clicking on the jigsaw piece.<p>
<img src="{$path|dirname|dirname|dirname}/images/setup/clickToReg.png" alt="clickToReg" class="n" />
<p>(Notice the right jigsaw piece becomes colored.)<p>
<img src="{$path|dirname|dirname|dirname}/images/setup/extReg.png" alt="extReg" class="n" />

<p>Extensions can be enabled if registered, by clicking on the switch.<p>
<img src="{$path|dirname|dirname|dirname}/images/setup/clickToEn.png" alt="clickToEn" class="n" />
<p>(Notice the lightbulb becomes colored.)<p>
<img src="{$path|dirname|dirname|dirname}/images/setup/extEn.png" alt="extEn" class="n" />

<p>Extensions can be unregeristed if registered, by clicking on the (delete) jigsaw piece.<p>
<img src="{$path|dirname|dirname|dirname}/images/setup/clickToUnreg.png" alt="clickToUnreg" class="n" />
<p>(Notice the right jigsaw piece looses color.)<p>

<p>Extensions can be disabled if enabled, by clicking on the switch.<p>
<img src="{$path|dirname|dirname|dirname}/images/setup/clickToDis.png" alt="clickToDis" class="n" />
<p>(Notice the lightbulb looses color.)<p>

<br />

<script type="text/javascript">{literal}
$('.hideimgtog').click(function(){
	$('img').toggle();
});
$('.hidedlimg').click(function(){
	$('.dl').toggle();
});
$('.hideulimg').click(function(){
	$('.ul').toggle();
});
$('.hidedbimg').click(function(){
	$('.db').toggle();
});
$('.hidecfgimg').click(function(){
	$('.cfg').toggle();
});
$('.hidebrimg').click(function(){
	$('.br').toggle();
});
$('.hidecfgdimg').click(function(){
	$('.cfgd').toggle();
});
$('.hidesamimg').click(function(){
	$('.sam').toggle();
});
$('.hidesetimg').click(function(){
	$('.set').toggle();
});
$('.hidenimg').click(function(){
	$('.n').toggle();
});
</script>{/literal}