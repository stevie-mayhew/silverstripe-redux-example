
<% if $WebpackDevServer %>
    <link rel="stylesheet" href="http://localhost:3000/production/css/main.css">
<% else %>
    <link rel="stylesheet" href="$HashPath('production/css/main.css')">
<% end_if %>

<% if $SiteConfig.SupportedBrowser %>
	<!--[if lt IE 9]>
	    <link rel="stylesheet" href="/silverstripe-browsersupport/css/style.css">
	<![endif]-->
<% end_if %>
